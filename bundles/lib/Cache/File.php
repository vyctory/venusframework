<?php

/**
 * Manage Cache by file
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
 * This class manage the Cache by file
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
class File implements CacheInterface
{
    /**
     * var containe this folder of cache
     *
     * @access private
     * @var    string
     */
    private $_sFolder = '';

    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->_sFolder = str_replace('bundles'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Cache', CACHE_DIR, __DIR__).DIRECTORY_SEPARATOR;
    }

    /**
     * set a value
     *
     * @access public
     * @param  string $sName name of the session
     * @param  mixed $mValue value of this sesion var
     * @param  int $iFlag flags
     * @param  int $iExpire expiration of cache
     * @return \Venus\lib\Cache\File
     */
    public function set(string $sName, $mValue, int $iFlag, int $iExpire)
    {
        file_put_contents($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac', serialize($mValue));
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
        if ($iTimeout > 0 && file_exists($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac')
            && time() - filemtime($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac') > $iTimeout) {

            unlink($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac');
        }

        if (file_exists($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac')) {

            return unserialize(file_get_contents($this->_sFolder . $this->_getSubDirectory($sName) . md5($sName) . '.fil.cac'));
        } else {

            return false;
        }
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
        return unlink($this->_sFolder.$this->_getSubDirectory($sName).md5($sName).'.fil.cac');
    }

    /**
     * flush the cache
     *
     * @access public
     * @param  string $sName name of the session
     * @return mixed
     */
    public function flush()
    {
        $this->_removeDirectory($this->_sFolder);
    }

    /**
     *
     *
     * @access public
     * @param  string $sName name of the session
     * @return mixed
     */
    private function _getSubDirectory($sName)
    {
        if (!file_exists($this->_sFolder.substr(md5($sName), 0, 2).DIRECTORY_SEPARATOR.substr(md5($sName), 2, 2))) {

            mkdir($this->_sFolder.substr(md5($sName), 0, 2).DIRECTORY_SEPARATOR.substr(md5($sName), 2, 2), 0777, true);
        }

        return substr(md5($sName), 0, 2).DIRECTORY_SEPARATOR.substr(md5($sName), 2, 2).DIRECTORY_SEPARATOR;
    }

    /**
     * remove a directory recursivly
     *
     * @access private
     * @param  string $sName nom du répertoire
     * @return void
     */
    private function _removeDirectory($sName)
    {
        if ($rDirectory = opendir($sName)) {

            while (($sFile = readdir($rDirectory)) !== false) {

                if ($sFile > '0' && filetype($sName.$sFile) == "file") { unlink($sName.$sFile); } elseif ($sFile > '0' && filetype($sName.$sFile) == "dir") { remove_dir($sName.$sFile."\\"); }
            }

            closedir($rDirectory);
            rmdir($sName);
        }
    }
}
