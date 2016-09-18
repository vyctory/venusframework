<?php

/**
 * Manage Request
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

use \Venus\core\Mother as Mother;
use \Venus\lib\Request\Cookies as Cookies;
use \Venus\lib\Request\Files as Files;
use \Venus\lib\Request\Headers as Headers;
use \Venus\lib\Request\Query as Query;
use \Venus\lib\Request\Request as RequestPost;
use \Venus\lib\Request\Server as Server;

/**
 * This class manage the request
 *
 * @property 	\Venus\lib\Request\Cookies cookies
 * @property 	\Venus\lib\Request\Files files
 * @property 	\Venus\lib\Request\Headers headers
 * @property 	\Venus\lib\Request\Query query
 * @property 	\Venus\lib\Request\Request request
 * @property 	\Venus\lib\Request\Server server
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Request extends Mother
{
	/**
	 * Query constructor.
	 */
	public function __construct()
	{
        $this->cookies = function() { return new Cookies(); };
        $this->files = function() { return new Files(); };
        $this->headers = function() { return new Headers(); };
        $this->query = function() { return new Query(); };
        $this->request = function() { return new RequestPost(); };
		$this->server = function() { return new Server(); };
	}

	/**
	 * if the request is ajax
	 *
	 * @access public
	 * @param  string $sName name of the template
	 * @return bool
	 */
	public static function isXmlHttpRequest()
	{
		if (!self::isCliRequest()) {

			if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

				return true;
			}
			else {

				return false;
			}
		}
	}

	/**
	 * if the request is http (web site or web service)
	 *
	 * @access public
	 * @return bool
	 */
	public static function isHttpRequest()
	{
		if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) { return true; }
		else { return false; }
	}

	/**
	 * if the request is https (web site or web service)
	 *
	 * @access public
	 * @return bool
	 */
	public static function isHttpsRequest()
	{
		if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') { return true; }
		else { return false; }
	}

	/**
	 * if the request is http (web site or web service)
	 *
	 * @access public
	 * @return bool
	 */
	public static function isCliRequest()
	{
		$sSapiType = php_sapi_name();

		if (substr($sSapiType, 0, 3) == 'cgi' || defined('STDIN')) { return true; }
		else { return false; }
	}

	/**
	 * if the request is http (web site or web service)
	 *
	 * @access public
	 * @return bool
     * @deprecated  don't use this method because they return a false result
     *              delete in the version 5
     * @throws \Exception
	 */
	public static function getPreferredLanguage()
	{
        throw new \Exception("Use getLanguages() method now!");
	}

	/**
	 * if the request is http (web site or web service)
	 *
	 * @access public
	 * @return bool
	 */
	public static function getParameters()
	{
		if (isset($_GET)) { return $_GET; }
		else { return array(); }
	}

	/**
	 * if the request is http (web site or web service)
	 *
	 * @access public
	 * @return bool
	 */
	public static function getPostParameters()
	{
		if (isset($_POST)) { return $_POST; }
		else { return array(); }
	}

	/**
	 * if there are POST parameters
	 *
	 * @access public
	 * @return bool
	 */
	public function isPost()
	{
		if (isset($_POST) && count($_POST) > 0) { return true; }
		else { return false; }
	}

	/**
	 * get the POST for $sName
	 *
	 * @access public
	 * @param  string $name
	 * @return mixed
     * @deprecated  please use $this->request->get()
     *              delete in the version 5
	 */
	public function getPost(string $name)
	{
		return $this->request->get($name);
	}

	/**
	 * get the put method
	 *
	 * @access public
	 * @return array
	 */
	public static function getPut() 
	{	    
	    $aPut = array();
	    
	    $rPutResource = fopen("php://input", "r");
	    
	    while ($sData = fread($rPutResource, 1024)) {

	        $aSeparatePut = explode('&', $sData);
	        
	        foreach($aSeparatePut as $sOne) {
	            
	            $aOnePut = explode('=', $sOne);
	            $aPut[$aOnePut[0]] = $aOnePut[1];
	        }
	    }
	    
	    return $aPut;
	}

	/**
	 * Set the HTTP status
	 *
	 * @access public
	 * @param  int $iCode
	 * @return void
	 */
	public static function setStatus($iCode)
	{
		if ($iCode === 200) { header('HTTP/1.1 200 Ok');  }
		else if ($iCode === 201) { header('HTTP/1.1 201 Created');  }
		else if ($iCode === 204) { header("HTTP/1.0 204 No Content");  }
		else if ($iCode === 403) { header('HTTP/1.1 403 Forbidden'); }
		else if ($iCode === 404) { header('HTTP/1.1 404 Not Found'); }
	}

    /**
     * get http method
     * @return string
     */
    public function getMethod() : string
    {
        return $this->server->get('REQUEST_METHOD');
    }

    /**
     * return languages accepted by the customer
     * @return array
     */
    public function getLanguages() : array
    {
        if (!self::isCliRequest()) { return explode(',', preg_replace('/^([^;]);?.*$/', '$1', $_SERVER['HTTP_ACCEPT_LANGUAGE'])); }
        else { return array(); }
    }

	/**
	 * get path info
	 * @return string
     */
	public function getPathInfo() : string
	{
		return $this->server->get('REQUEST_URI');
	}
}
