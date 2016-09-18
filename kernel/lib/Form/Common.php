<?php

/**
 * The common part of each element in a form
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
 * The common part of each element in a form
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
abstract class Common
{
	/**
	 * the name of element
	 *
	 * @access private
	 * @var    string
	 */
	private $_sName = null;
	
	/**
	 * constraints on the element
	 *
	 * @access private
	 * @var    string
	 */
	private $_aConstraints = null;

	/**
	 * get the name
	 *
	 * @access public
	 * @return string
	 */
	public function getName() : string
	{
		return $this->_sName;
	}

	/**
	 * get the name
	 *
	 * @access public
	 * @param  string $sName name;
	 * @return object
	 */
	public function setName(string $sName)
	{
		$this->_sName = $sName;
		return $this;
	}

	/**
	 * get the <html>
	 *
	 * @access public
	 * @return string
	 */
	abstract public function fetch() : string;

	/**
	 * set the constraint
	 *
	 * @access public
	 * @param  object $oConstraint constraint;
	 * @return object
	 */
	public function setConstraint($oConstraint)
	{
		$this->_aConstraints[] = $oConstraint;
		return $this;
	}

	/**
	 * get the constraint
	 *
	 * @access public
	 * @return array
	 */
	public function getConstraint() : array
	{
		return $this->_aConstraints;
	}
}
