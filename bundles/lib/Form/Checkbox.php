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
class Checkbox extends Common
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
	 * the value of the checked element
	 *
	 * @access private
	 * @var    array
	 */
	private $_aValuesChecked = null;

	/**
	 * constructor that it increment (static) for all use
	 *
	 * @access public
	 * @param  string $sName name
	 * @param  string $sLabel label of checkbox
	 * @param  string $sValue value of checkbox
	 * @param  array $aValuesChecked value checked of checkbox
	 */
	public function __construct(string $sName, string $sLabel, string $sValue, array $aValuesChecked = null)
	{
		$this->setName($sName);
		$this->setValue($sValue);
		$this->setValuesChecked($aValuesChecked);
		$this->setLabel($sLabel);
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
	 * @return Checkbox
	 */
	public function setValue(string $sValue) : Checkbox
	{
		$this->_sValue = $sValue;
		return $this;
	}

	/**
	 * get the Value Checked
	 *
	 * @access public
	 * @return array
	 */
	public function getValuesChecked() : array
	{
		return $this->_aValuesChecked;
	}

	/**
	 * set the Value Checked
	 *
	 * @access public
	 * @param  array $aValuesChecked Values of input;
	 * @return Checkbox
	 */
	public function setValuesChecked(array $aValuesChecked) : Checkbox
	{
		$this->_aValuesChecked = $aValuesChecked;
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
	 * @return Checkbox
	 */
	public function setLabel(string $sLabel) : Checkbox
	{
		$this->_sLabel = $sLabel;
		return $this;
	}

	/**
	 * if the button is clicked
	 *
	 * @access public
	 * @param  string $sType type of input;
	 * @return bool
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
		$sContent = '<input type="checkbox" name="'.$this->getName().'[]" value="'.$this->getValue().'"';

		if (in_array($this->getValue(), $this->getValuesChecked())) { $sContent .= ' checked="checked"'; }
		
		$sContent .= '/> '.$this->getLabel();

		return $sContent;
	}
}
