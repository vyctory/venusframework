<?php

/**
 * Manage Cache by Apc
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
 * This class manage the Cache by Apc
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
class Apc implements CacheInterface
{
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
		apc_add($sName, $mValue, $iExpire);
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
		return apc_fetch($sName);
	}

	/**
	 * delete a value
	 *
	 * @access public
	 * @param  string $sName name of the session
	 * @return mixed
	 */
	public function delete(string $sName)
	{
		return apc_delete($sName);
	}

	/**
	 * flush the cache
	 *
	 * @access public
	 * @return mixed
	 */
	public function flush()
	{
		return flush();
	}
}
