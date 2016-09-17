<?php

/**
 * Manage Cookies
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
namespace Venus\lib;

/**
 * This class manage the Cookies
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
class Cookie
{
  	/**
  	 * set a value
  	 *
  	 * @access public
  	 * @param  string $sName name of the Cookie
  	 * @param  mixed $mValue value of this sesion var
  	 * @return \Venus\lib\Cookie
  	 */
  	public function set(string $sName, $mValue, int $iExpire = 0, string $sPath = '', string $sDomain = '', int $iSecure = false) : Cookie
	{
  		$iExpire = time() + $iExpire;
    	setcookie($sName, $mValue, $iExpire, $sPath, $sDomain, $iSecure);
    	return $this;
  	}

  	/**
  	 * set a value
  	 *
  	 * @access public
  	 * @param  string $sName name of the Cookie
  	 * @return mixed
  	 */
  	public function get(string $sName)
	{
    	return $_COOKIE[$sName];
  	}

  	/**
  	 * set a value
  	 *
  	 * @access public
  	 * @param  string $sName name of the Cookie
  	 * @return bool
  	 */
  	public function exists(string $sName) : bool
	{
    	return isset($_COOKIE[$sName]);
  	}
}
