<?php

/**
 * Manage Cache by Memory
 *
 * @category  	lib
 * @package		lib\Cache
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib\Cache;
use \Venus\lib\Cache\CacheInterface;

/**
 * This class manage the Cache by Memory
 *
 * @category  	lib
 * @package		lib\Cache
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Memory implements CacheInterface
{
    /**
     * A static variable to keep the cache
     * @var array
     */    
    private static $_aMemories = array();
    
	/**
	 * set a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @param  mixed $mValue value of this sesion var
	 * @param  int $iFlag unused
	 * @param  int $iExpire expiration of cache
	 * @return \Venus\lib\Cache\Apc
	 */
	public function set(string $sName, $mValue, int $iFlag = 0, int $iExpire = 0)
	{
		self::$_aMemories[$sName] = $mValue;
		return $this;
	}

	/**
	 * get a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @param  int $iFlags flags
	 * @param  int $iTimeout expiration of cache
	 * @return mixed
	 */
	public function get(string $sName, int &$iFlags = null, int $iTimeout = 0)
	{
		return self::$_aMemories[$sName];
	}

	/**
	 * delete a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @return true
	 */
	public function delete(string $sName)
	{
		unset(self::$_aMemories[$sName]);
		return true;
	}

	/**
	 * flush the cache
	 *
	 * @access public
	 * @return mixed
	 */
	public function flush()
	{
		return self::$_aMemories = array();
	}
}
