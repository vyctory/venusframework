<?php

/**
 * Manage Cache
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

use \Venus\core\Config as Config;
use \Venus\lib\Cache\Apc as Apc;
use \Venus\lib\Cache\File as CacheFile;
use \Venus\lib\Cache\Memcache as CacheMemcache;
use \Venus\lib\Cache\Redis as Redis;
use \Venus\lib\Cache\Mock as Mock;

/**
 * This class manage the Cache
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
class Cache
{
	/**
	 * type of cache (memcache/file)
	 *
	 * @access private
	 * @var    string
	 */
	private static $_sTypeOfCache = 'file';

	/**
	 * type of cache (memcache/file)
	 *
	 * @access private
	 * @var    array
	 */
	private static $_aCache = array();

	/**
	 * set the cache that we would use
	 *
	 * @access private
	 * @param  string $sCacheName name of cache
	 * @return object
	 */
	public static function setCacheType(string $sCacheName)
	{
		if ($sCacheName === 'file') { self::$_sTypeOfCache = 'file'; }
		else if ($sCacheName === 'memcache') { self::$_sTypeOfCache = 'memcache'; }
		else if ($sCacheName === 'apc') { self::$_sTypeOfCache = 'apc'; }
		else if ($sCacheName === 'redis') { self::$_sTypeOfCache = 'redis'; }
		else { self::$_sTypeOfCache = 'mock'; }
	}

	/**
	 * set a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @param  mixed $mValue value of this sesion var
	 * @param  int $iFlag unused
	 * @param  int $iExpire expiration of cache
	 * @return void
	 */
	public static function set(string $sName, $mValue, int $iFlag = 0, int $iExpire = 0)
	{
		return self::_getCacheObject()->set($sName, $mValue, $iFlag, $iExpire);
	}

	/**
	 * get a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @param  int $iFlags flags
	 * @param  int $iTimeout expiration of cache
	 * @return bool
	 */
	public static function get(string $sName, int &$iFlags = null, int $iTimeout = 0) : bool
	{
		return self::_getCacheObject()->get($sName, $iFlags, $iTimeout);
	}

	/**
	 * delete a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @return boolean
	 */
	public static function delete(string $sName) : bool
	{
		return self::_getCacheObject()->delete($sName);
	}

	/**
	 * flush the cache
	 *
	 * @access public
	 * @return boolean
	 */
	public static function flush() : bool
	{
		return self::_getCacheObject()->flush();
	}

	/**
	 * initialize the cache class and get the good object
	 *
	 * @access private
	 * @return object
	 */
	private static function _getCacheObject()
	{
		if (self::$_sTypeOfCache === 'file') {

			if (!isset(self::$_aCache['file'])) { self::$_aCache['file'] = new CacheFile; }

			return self::$_aCache['file'];
		}
		else if (self::$_sTypeOfCache === 'memcache') {
  		  
			if (!isset(self::$_aCache['memcache'])) { 
			    
			    $oDbConf = Config::get('Memcache')->configuration;
			    
			    if (isset($oDbConf->port)) { $sPort = $oDbConf->port; }
			    else { $sPort = null; }
			    
			    if (isset($oDbConf->timeout)) { $iTimeout = $oDbConf->timeout; }
			    else { $iTimeout = null; }
			    
			    self::$_aCache['memcache'] = new CacheMemcache($oDbConf->host, $sPort, $iTimeout); 
			}

			return self::$_aCache['memcache'];
		}
		else if (self::$_sTypeOfCache === 'apc') {

			if (!isset(self::$_aCache['apc'])) { self::$_aCache['apc'] = new Apc; }

			return self::$_aCache['apc'];
		}
		else if (self::$_sTypeOfCache === 'redis') {
		    
			if (!isset(self::$_aCache['redis'])) {
			    
			    $oDbConf = Config::get('Redis')->configuration;
			    self::$_aCache['memcache'] = new Redis($oDbConf);
			}

			return self::$_aCache['redis'];
		}
		else if (self::$_sTypeOfCache === 'mock') {
		    
			if (!isset(self::$_aCache['mock'])) { self::$_aCache['mock'] = new Mock; }

			return self::$_aCache['mock'];
		}
	}
}
