<?php

/**
 * Dependency Injection Manager
 *
 * @category  	core
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib;

/**
 * Dependency Injection Manager
 *
 * @category  	core
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Di
{
	/**
	 * contener of depency injector for all instances
	 *
	 * @access private
	 * @var    array
	 */
	private static $_aSharedDependencyInjectorContener = null;
	
	/**
	 * contener of depency injector just for this instance
	 *
	 * @access private
	 * @var    array
	 */
	private $_aDependencyInjectorContener = null;

	/**
	 * get the injection (no replace it if it exists)
	 *
	 * @access public
	 * @param  string $sNameOfDi name of injection
	 * @return mixed
	 */
	public function get(string $sNameOfDi)
	{
		if (isset(self::$_aSharedDependencyInjectorContener[md5($sNameOfDi)])) {

			return self::$_aSharedDependencyInjectorContener[md5($sNameOfDi)];
		}
		else if (isset($this->_aDependencyInjectorContener[md5($sNameOfDi)])) {
		    
		    return $this->_aDependencyInjectorContener[md5($sNameOfDi)];
		}

		return false;
	}

	/**
	 * get a property
	 *
	 * @access public
	 * @param  string $sNameOfDi name of di
	 * @param  callable $cFunction functrion to use when you get this dependance injection
	 * @param  bool $bShared indicate if you want shares or not this injection with others instances
	 * @return \Venus\core\Di
	 */
	public function set(string $sNameOfDi, callable $cFunction, bool $bShared = false) : Di
	{
	    if ($bShared === true) { self::$_aSharedDependencyInjectorContener[md5($sNameOfDi)] = $cFunction; }
	    else { $this->_aDependencyInjectorContener[md5($sNameOfDi)] = $cFunction; }
		return $this;
	}
}
