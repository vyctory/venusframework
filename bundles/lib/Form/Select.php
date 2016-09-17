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
class Select extends Common
{
	/**
	 * the name of element
	 *
	 * @access private
	 * @var    string
	 */
	private $_aOptions = null;

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
	 * @param  array $aOptions options
	 * @param  string $sLabel label of input
	 * @param  string $sValue value of input
	 * @return \Venus\lib\Form\Input
	 */
	public function __construct(string $sName, array $aOptions, string $sLabel = null, string $sValue = null)
	{
		$this->setName($sName);
		$this->setOptions($aOptions);
		$this->setValue($sValue);
		$this->setLabel($sLabel); 
	}

	/**
	 * get the Options
	 *
	 * @access public
	 * @return array
	 */
	public function getOptions() : array
	{
		return $this->_aOptions;
	}

	/**
	 * set the Options
	 *
	 * @access public
	 * @param  array $aOptions Options of input;
	 * @return Select
	 */
	public function setOptions(array $aOptions) : Select
	{
		$this->_aOptions = $aOptions;
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
	 * @return \Venus\lib\Form\Select
	 */
	public function setValue(string $sValue) : Select
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
	 * @return \Venus\lib\Form\Select
	 */
	public function setLabel(string $sLabel) : Select
	{
		$this->_sLabel = $sLabel;
		return $this;
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
		
		if ($this->getLabel() !== null) { $sContent .= '<label>'.$this->getLabel().'</label> '; }
		
		$sContent .= '<select name="'.$this->getName().'">';

		foreach ($this->getOptions() as $sKey => $sValue) {

			$sContent .= '<option value="'.$sKey.'"';
			
			if ($this->getValue() == $sKey) { $sContent .= ' selected="selected"'; }
			
			$sContent .= '>'.$sValue.'</option>';
		}

		$sContent .= '</select> ';

		return $sContent;
	}
}
