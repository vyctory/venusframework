<?php

/**
 * Manage Form
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 *
 * @tutorial    == in the controller:
 *
 * 				$this->form
 * 					 ->add('task', 'text')
 *					 ->add('dueDate', 'date')
 *					 ->add('save', 'submit');
 *
 *				$this->view
 *					 ->assign('form', $this->form->getForm())
 *					 ->display();
 *
 *				in the template:
 *
 *				{$form}
 *
 *				== if you want test if the form is validated, you could do that:
 *
 *				if ($this->form->isValid()) { ... } // after the form definition
 *
 *				== check if the button is clicked:
 *
 *				if ($this->form->get('save')->isClicked()) { ... }
 */
namespace Venus\lib;

use \Attila\lib\Entity          as LibEntity;
use \Venus\lib\Form\Checkbox    as Checkbox;
use \Venus\lib\Form\Container   as Container;
use \Venus\lib\Form\Label       as Label;
use \Venus\lib\Form\Input       as Input;
use \Venus\lib\Form\Radio       as Radio;
use \Venus\lib\Form\Select      as Select;
use \Venus\lib\Form\Textarea    as Textarea;

/**
 * This class manage the Form
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Form
{
    /**
     * Elements of the form
     *
     *  @access private
     *  @var    array
     */
    private $_aElement = array();

    /**
     * Increment for form
     *
     *  @access private
     *  @var    int
     */
    private static $_iFormIncrement = 0;

    /**
     * number of form
     *
     *  @access private
     *  @var    int
     */
    private $_iFormNumber = 0;

    /**
     * Separator between fields of form
     *
     *  @access private
     *  @var    string
     */
    private $_sSeparator = '<br/>';

    /**
     * The entity to save with the formular
     *
     *  @access private
     *  @var    string
     */
    private $_sSynchronizeEntity = null;

    /**
     * The id of entity
     *
     *  @access private
     *  @var    int
     */
    private $_iIdEntity = null;

    /**
     * The entity to save with the formular
     *
     *  @access private
     *  @var    int
     */
    private $_iIdEntityCreated = null;

    /**
     * constructor that it increment (static) for all use
     *
     * @access public
     */
    public function __construct()
    {
        self::$_iFormIncrement++;
        $this->_iFormNumber = self::$_iFormIncrement;
    }

    /**
     * add an element in the form
     *
     * @access public
     * @param  string $sName name
     * @param  string|\Venus\lib\Form $mType type of field
     * @param  string $sLabel label of field
     * @param  mixed $mValue value of field
     * @parma  mixed $mOptions options (for select)
     * @return \Venus\lib\Form
     */
    public function add($sName, $mType, $sLabel = null, $mValue = null, $mOptions = null)
    {
        if ($mType instanceof Container) {

            $this->_aElement[$sName] = $mType;
        } else if ($mType === 'text' || $mType === 'submit' || $mType === 'password' || $mType === 'file' || $mType === 'tel'
            || $mType === 'url' || $mType === 'email' || $mType === 'search' || $mType === 'date' || $mType === 'time'
            || $mType === 'datetime' || $mType === 'month' || $mType === 'week' || $mType === 'number' || $mType === 'range'
            || $mType === 'color') {

            $this->_aElement[$sName] = new Input($sName, $mType, $sLabel, $mValue);
        } elseif ($mType === 'textarea') {

            $this->_aElement[$sName] = new Textarea($sName, $sLabel, $mValue);
        } else  if ($mType === 'select') {

            $this->_aElement[$sName] = new Select($sName, $mOptions, $sLabel, $mValue);
        } else  if ($mType === 'label') {

            $this->_aElement[$sName] = new Label($sName);
        } else  if ($mType === 'list_checkbox') {

            $i = 0;

            $this->_aElement[$sName.'_'.$i++] = new Label($sLabel);

            foreach ($mValue as $mKey => $sValue) {

                $this->_aElement[$sName.'_'.$i++] = new Checkbox($sName, $sValue, $mKey, $mOptions);
            }
        } else  if ($mType === 'checkbox') {

            $this->_aElement[$sName] = new Checkbox($sName, $sLabel, $mValue, $mOptions);
        } else  if ($mType === 'radio') {

            $this->_aElement[$sName.rand(100000, 999999)] = new Radio($sName, $sLabel, $mValue, $mOptions);
        } else  if ($mType === 'date') {

            $aDay = array();

            for ($i = 1; $i <= 31; $i++) {

                if ($i < 10) { $aDay['0'.$i] = '0'.$i; }
                else { $aDay[$i] = $i; }
            }

            $this->_aElement[$sName.'_day'] = new Select($sName, $aDay);

            $aMonth = array(
                '01' => 'Jan',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Apr',
                '05' => 'May',
                '06' => 'Jun',
                '07' => 'Jui',
                '08' => 'Aug',
                '09' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec',
            );

            $this->_aElement[$sName.'_month'] = new Select($sName, $aMonth);

            $aYear = array();

            for ($i = 1900; $i <= 2013; $i++) {

                $aYear[$i] = $i;
            }

            $this->_aElement[$sName.'_year'] = new Select($sName, $aMonth);
        }

        return $this;
    }

    /**
     * get id entity created by the formular
     *
     * @access public
     * @return int
     */
    public function getIdEntityCreated() : int
    {
        return $this->_iIdEntityCreated;
    }

    /**
     * set id entity created by the formular
     *
     * @access public
     * @param  int $iIdEntityCreated
     * @return Form
     */
    public function setIdEntityCreated(int $iIdEntityCreated) : Form
    {
        $this->_iIdEntityCreated = $iIdEntityCreated;
        return $this;
    }

    /**
     * get form number
     *
     * @access public
     * @return int
     */
    public function getFormNumber() : int
    {
        return $this->_iFormNumber;
    }

    /**
     * set id entity created by the formular
     *
     * @access public
     * @param  int $iFormNumber
     * @return Form
     */
    public function setFormNumber(int $iFormNumber) : Form
    {
        $this->_iFormNumber = $iFormNumber;
        return $this;
    }

    /**
     * get global form
     *
     * @access public
     * @return \Venus\lib\Form\Container
     */
    public function getForm()
    {
        $oForm = $this->getFormInObject();

        $sFormContent = $oForm->start;

        foreach ($oForm->form as $sValue) {

            $sFormContent .= $sValue.$this->_sSeparator;
        }

        $sFormContent .= $oForm->end;

        $oContainer = new Container;
        $oContainer->setView($sFormContent)
                   ->setForm($this);

        return $oContainer;
    }


    /**
     * get global object form
     *
     * @access public
     * @return \stdClass
     */
    public function getFormInObject()
    {
        $sExKey = null;

        if ($this->_iIdEntity > 0 && $this->_sSynchronizeEntity !== null && count($_POST) < 1) {

            $sModelName = str_replace('Entity', 'Model', $this->_sSynchronizeEntity);
            $oModel = new $sModelName;

            $oEntity = new $this->_sSynchronizeEntity;
            $sPrimaryKey = LibEntity::getPrimaryKeyNameWithoutMapping($oEntity);
            $sMethodName = 'findOneBy'.$sPrimaryKey;
            $oCompleteEntity = call_user_func_array(array(&$oModel, $sMethodName), array($this->_iIdEntity));

            if (is_object($oCompleteEntity)) {

                foreach ($this->_aElement as $sKey => $sValue) {

                    if ($sValue instanceof \Venus\lib\Form\Radio) {

                        $sExKey = $sKey;
                        $sKey = substr($sKey, 0, -6);
                    }

                    if ($sValue instanceof Form) {

                        ;
                    } else {

                        $sMethodNameInEntity = 'get_'.$sKey;
                        $mValue = $oCompleteEntity->$sMethodNameInEntity();

                        if ($sValue instanceof \Venus\lib\Form\Radio && method_exists($this->_aElement[$sExKey], 'setValueChecked')) {

                            $this->_aElement[$sExKey]->setValueChecked($mValue);
                        } else if (isset($mValue) && method_exists($this->_aElement[$sKey], 'setValue')) {

                            $this->_aElement[$sKey]->setValue($mValue);
                        }
                    }
                }
            }
        }

        $oForm = new \StdClass();
        $oForm->start = '<form name="form'.$this->_iFormNumber.'" method="post"><input type="hidden" value="1" name="validform'.$this->_iFormNumber.'">';
        $oForm->form = array();

        foreach ($this->_aElement as $sKey => $sValue) {

            if ($sValue instanceof Container) {

                $oForm->form[$sKey] = $sValue;
            } else {

                $oForm->form[$sKey] = $sValue->fetch();
            }
        }

        $oForm->end = '</form>';

        return $oForm;
    }

    /**
     * get an element of formular
     *
     * @access public
     * @param  string $sName name
     * @return object
     */
    public function get($sName)
    {
        return $this->_aElement[$sName];
    }

    /**
     * get the form separator
     *
     * @access public
     * @return string
     */
    public function getSeparator()
    {
        return $this->_sSeparator;
    }

    /**
     * set the form separator
     *
     * @access public
     * @param  string $sSeparator separator between the fields
     * @return \Venus\lib\Form
     */
    public function setSeparator($sSeparator)
    {
        $this->_sSeparator = $sSeparator;
        return $this;
    }

    /**
     * set the entity to synchronize with the formular
     *
     * @access public
     * @param $sSynchronizeEntity
     * @param  int $iId id of the primary key
     * @return Form
     * @internal param string $sSeparator separator between the fields
     */
    public function synchronizeEntity($sSynchronizeEntity, $iId = null)
    {
        if ($iId !== null) { $this->_iIdEntity = $iId; }

        $this->_sSynchronizeEntity = $sSynchronizeEntity;
        return $this;
    }

    /**
     * add constraint
     *
     * @access public
     * @param  string $sName field name
     * @param  object $oConstraint constraint on the field
     * @return \Venus\lib\Form
     */
    public function addConstraint($sName, $oConstraint)
    {
        if ($this->_aElement[$sName] instanceof Input || $this->_aElement[$sName] instanceof Textarea) {

            $this->_aElement[$sName]->setConstraint($oConstraint);
        }

        return $this;
    }

    /**
     * get all elements
     *
     * @access public
     * @return  array
     */
    public function getElement() {

        return $this->_aElement;
    }

    /**
     * get all elements
     *
     * @access public
     * @return int
     */
    public function getIdEntity() {

        return $this->_iIdEntity;
    }

    /**
     * get all elements
     *
     * @access public
     * @return string
     */
    public function getSynchronizeEntity() {

        return $this->_sSynchronizeEntity;
    }
}
