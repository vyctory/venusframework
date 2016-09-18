<?php

/**
 * Manage Cache by Redis
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

use \Redis as RealRedis;
use \Venus\lib\Cache\CacheInterface;

/**
 * This class manage the Cache by Redis
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
class Redis extends RealRedis implements CacheInterface
{
    /**
     * constructor with the connection to Redis
     *
     * @access public
     * @param $oConf
     * @throws \Exception
     * @internal param string $sName name of the session
     * @internal param int $iFlags flags
     * @internal param int $iTimeout expiration of cache
     */
    public function __construct($oConf)
    {
        if (!$this->connect($oConf->host, $oConf->port)) {

            throw new \Exception('Redis server unavailable');
        }
    
        // Select the REDIS db index
        $this->select($oConf->index);
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
        return parent::get($sName);
    }

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
    public function set(string $sName, $mValue, int $iFlag = 0, int $iExpire = false)
    {
        if ($iExpire === false) {

            return parent::set($sName, $mValue);
        }
        else {

            return parent::setex($sName, $iExpire, $mValue);
        }
    }

    /**
     * flush the cache
     *
     * @access public
     * @return mixed
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
     * @return mixed
     */
    public function delete(string $sName)
    {
        return $this->del($sName);
    }

    /**
     * close the redis connecction
     *
     * @access public
     * @return mixed
     */
    public function __sleep()
    {
        $this->close();
    }

    /**
     * add
     *
     * @access public
     * @param  string $sName name of the session
     * @param  mixed $mValue value of this sesion var
     * @param  int $iFlag unused
     * @param  int $iExpire expiration of cache
     * @return mixed
     */
    public function add(string $sName, $mValue, int $iExpire = false)
    {
        return $this->set($sName, $mValue, 0, $iExpire);
    }
}
