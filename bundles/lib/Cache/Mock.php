<?php

/**
 * Manage Cache by Mock -> just for simulate the cache object
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
 * Manage Cache by Mock -> just for simulate the cache object
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
class Mock implements CacheInterface
{
    /**
     * get a value
     *
     * @access public
     * @param  string $sName name of the session
     * @param  int $iFlags flags
     * @param  int $iTimeout expiration of cache
     * @return boolean
     */
    public function get(string $sName, int&$iFlags = null, int $iTimeout = 0)
    {
        return false;
    }

    /**
     * set a value
     *
     * @access public
     * @param  string $sName name of the session
     * @param  mixed $mValue value of this sesion var
     * @param  int $iFlag unused
     * @param  int $iExpire expiration of cache
     * @return boolean
     */
    public function set(string $sName, $mValue, int $iFlag = 0, int $iExpire = false)
    {
        return true;
    }

    /**
     * flush the cache
     *
     * @access public
     * @return boolean
     */
    public function flush()
    {
        return false;
    }

    /**
     * delete a value
     *
     * @access public
     * @param  string $sName name of the session
     * @return boolean
     */
    public function delete(string $sName)
    {
        return false;
    }

    /**
     * add
     *
     * @access public
     * @param  string $sName name of the session
     * @param  mixed $mValue value of this sesion var
     * @param  int $iExpire expiration of cache
     * @return boolean
     */
    public function add($sName, $mValue, $iExpire = false)
    {
        return true;
    }
}
