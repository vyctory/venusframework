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
class Radio extends Common
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
	 * @var    string
	 */
	private $_sValueChecked = null;

	/**
	 * constructor that it increment (static) for all use
	 *
	 * @access public
	 * @param  string $sName name
	 * @param  string $sLabel label of radio
	 * @param  string $sValue value of radio
	 * @param  string $sValueChecked value checked of radio
	 * @return \Venus\lib\Form\Input
	 */
	public function __construct(string $sName, string $sLabel, string $sValue, string $sValueChecked = null)
	{
		$this->setName($sName);
		$this->setValue($sValue);
		$this->setValueChecked($sValueChecked);
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
	 * @return Radio
	 */
	public function setValue(string $sValue) : Radio
	{
		$this->_sValue = $sValue;
		return $this;
	}

	/**
	 * get the Value Checked
	 *
	 * @access public
	 * @return string
	 */
	public function getValueChecked() : string
	{
		return $this->_sValueChecked;
	}

	/**
	 * set the Value Checked
	 *
	 * @access public
	 * @param  string $sValueChecked Value of input;
	 * @return Radio
	 */
	public function setValueChecked(string $sValueChecked) : Radio
	{
		$this->_sValueChecked = $sValueChecked;
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
	 * @return Radio
	 */
	public function setLabel(string $sLabel) : Radio
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
		$sContent = '<input type="radio" name="'.$this->getName().'" value="'.$this->getValue().'"';
		
		if ($this->getValueChecked() == $this->getValue()) { $sContent .= ' checked="checked"'; }
		
		$sContent .= '/> '.$this->getLabel();

		return $sContent;
	}
}
