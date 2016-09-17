<?php

/**
 * Manage Form
 *
 * @category  	lib
 * @package		lib\Form
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib\Form;

/**
 * This class manage the Form
 *
 * @category  	lib
 * @package		lib\Form
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Input extends Common
{
    /**
     * the name of element
     *
     * @access private
     * @var    string
     */
    private $_sType = null;

    /**
     * the label of element
     *
     * @access private
     * @var    string
     */
    private $_sLabel = null;

    /**
     * the value of element
     *
     * @access private
     * @var    string
     */
    private $_sValue = null;

    /**
     * constructor that it increment (static) for all use
     *
     * @access public
     * @param  string $sName name
     * @param  string $sType type of input
     * @param  string $sLabel label of input
     * @param  string $sValue value of input
     */
    public function __construct(string $sName, string $sType, string $sLabel = null, string $sValue = null)
    {
        $this->setName($sName);
        $this->setType($sType);
        $this->setValue($sValue);

        if ($sLabel !== null) { $this->setLabel($sLabel); } else { $this->setLabel($sName); }
    }

    /**
     * get the type
     *
     * @access public
     * @return string
     */
    public function getType() : string
    {
        return $this->_sType;
    }

    /**
     * set the type
     *
     * @access public
     * @param  string $sType type of input;
     * @return \Venus\lib\Form\Input
     */
    public function setType(string $sType) : Input
    {
        $this->_sType = $sType;
        return $this;
    }

    /**
     * get the Value
     *
     * @access public
     * @return string
     */
    public function getValue() : string
    {
        return $this->_sValue;
    }

    /**
     * set the Value
     *
     * @access public
     * @param  string $sValue Value of input;
     * @return \Venus\lib\Form\Input
     */
    public function setValue(string $sValue) : Input
    {
        $this->_sValue = $sValue;
        return $this;
    }

    /**
     * get the Label
     *
     * @access public
     * @return string
     */
    public function getLabel() : string
    {
        return $this->_sLabel;
    }

    /**
     * set the Label
     *
     * @access public
     * @param  string $sLabel Label of input;
     * @return \Venus\lib\Form\Input
     */
    public function setLabel(string $sLabel) : Input
    {
        $this->_sLabel = $sLabel;
        return $this;
    }

    /**
     * if the button is clicked
     *
     * @access public
     * @param  string $sType type of input;
     * @return boolean
     */
    public function isClicked(string $sType) : bool
    {
        if ($this->getType() === 'submit' || $this->getType() === 'button') {

            if (isset($_POST[$this->getName()])) { return true; }
        }

        return false;
    }

    /**
     * get the <html>
     *
     * @access public
     * @return string
     */
    public function fetch() : string
    {
        $sContent = '';

        if ($this->getType() === 'text' || $this->getType() === 'password' || $this->getType() === 'file'
            || $this->getType() === 'tel' || $this->getType() === 'url' || $this->getType() === 'email'
            || $this->getType() === 'search' || $this->getType() === 'date' || $this->getType() === 'time'
            || $this->getType() === 'datetime' || $this->getType() === 'month' || $this->getType() === 'week'
            || $this->getType() === 'number' || $this->getType() === 'range' || $this->getType() === 'color') {

            $sContent .= '<label>'.$this->getLabel().'</label> ';
        }

        $sContent .= '<input type="'.$this->getType().'" name="'.$this->getName().'"';

        if ($this->getValue() !== null) { $sContent .= ' value="'.$this->getValue().'"'; }

        $sContent .= '/>';

        return $sContent;
    }
}
