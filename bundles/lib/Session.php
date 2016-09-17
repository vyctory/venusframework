<?php

/**
 * Manage Sessions
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
 * This class manage the Sessions
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
class Session
{	
	/**
	 * constructor
	 *
	 * @access public
	 * @return \Venus\lib\Session
	 */
	public function __construct()
	{	
		$this->start();
	}
	
  	/**
  	 * set a value
  	 *
  	 * @access public
  	 * @param  string $sName name of the session
  	 * @param  mixed $mValue value of this sesion var
  	 * @return Session
  	 */
  	public function set(string $sName, $mValue) : Session
	{
    	$_SESSION[$sName] = $mValue;
    	return $this;
  	}

  	/**
  	 * set a value
  	 *
  	 * @access public
  	 * @param  string $sName name of the session
  	 * @return mixed
  	 */
  	public function get(string $sName)
	{
  		if (isset($_SESSION[$sName])) { return $_SESSION[$sName]; }
  		else { return false; }
  	}

  	/**
  	 * start the session
  	 *
  	 * @access public
  	 * @return mixed
  	 */
  	public function start()
	{
		if (!session_id()) { session_start(); }
  	}

  	/**
  	 * set a flashbag value
  	 *
  	 * @access public
  	 * @param  string $sName name of the session
  	 * @param  string $sValue value of this sesion var
  	 * @return \Venus\lib\Session
	 */
 	 public function setFlashBag($sName, $sValue)
	 {
  		if (!isset($_SESSION['flashbag'])) { $_SESSION['flashbag'] = array(); }

  		$_SESSION['flashbag'][$sName] = $sValue;
  		return $this;
	}
	
	/**
	 * destroy the session
	 *
	 * @access public
	 * @return mixed
	 */
	public function destroy()
	{
		session_start();

		$_SESSION = array();
		
		if (ini_get("session.use_cookies")) {
		    
			$aParams = session_get_cookie_params();
		    
		    setcookie(session_name(), '', time() - 42000,
		        $aParams["path"], $aParams["domain"],
		        $aParams["secure"], $aParams["httponly"]
		    );
		}

		session_destroy();
	}
}
