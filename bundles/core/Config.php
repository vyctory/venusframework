<?php

/**
 * Config Manager
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
namespace Venus\core;

use \Venus\lib\Debug as Debug;

/**
 * Config Manager
 *
 * @category  	core
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus3/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/las93/venus3
 * @link      	https://github.com/las93
 * @since     	3.0
 */
class Config
{
	/**
	 * conf in a cache array
	 *
	 * @access private
	 * @var    array
	 */
	private static $_aConfCache = array();

	/**
	 * get a configuration
	 *
	 * @access public
	 * @param  string $sName name of the configuration
	 * @param  string $sPortal portal name if you specify it
	 * @param  bool $bNoDoRedirect not allowed the redirect parameter
	 * @return void
	 */
	public static function get(string $sName, string $sPortal = null, bool $bNoDoRedirect = false)
	{
        $aDirectories = [];
        $sJsonFile='';

        if ($bNoDoRedirect === true) { $sNameCache = $sName.'_true'; } else { $sNameCache = $sName; }
	    
		if ($sPortal === null || !is_string($sPortal)) {
		    
		    if (defined('PORTAL')) {

				$sPortal = PORTAL;
				$aDirectories = array($sPortal);
			} else {

				$sPortal = '';
				$aDirectories = scandir(str_replace('core', 'src', __DIR__));
			}
		}

		if (!isset(self::$_aConfCache[$sNameCache])) {

			$base = new \stdClass;

            if (count($aDirectories) < 1) { $aDirectories = [$sPortal]; }

			foreach ($aDirectories as $sPortal) {
			
			    if ($sPortal != '..' && $sPortal != '.') {

        			if (file_exists(str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local')) {
        
        				$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local')) {
        				
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('DEV') == 1) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('DEV') == 1) {
        
        				$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('PROD') == 1) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-prod';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('PROD') == 1) {
        
        				$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-prod';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('PREPROD') == 1) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-pprod';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('PREPROD') == 1) {
        
        				$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-pprod';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('RECETTE') == 1) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-rec';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-dev') && getenv('RECETTE') == 1) {
        
        				$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-rec';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local')) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf-local';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			if (file_exists(str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf')) {
        
        				$sJsonFile = str_replace('core', 'src'.DIRECTORY_SEPARATOR.$sPortal.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf';
        				$base = self::_mergeAndGetConf($sJsonFile, $base);
        			}

        			$sJsonFile = str_replace('core', 'conf', __DIR__).DIRECTORY_SEPARATOR.$sName.'.conf';
        			$base = self::_mergeAndGetConf($sJsonFile, $base);
			    }
		    }

            if ($base === '') {
				
				trigger_error("Error in your Json format in this file : ".$sJsonFile, E_USER_NOTICE);
			}

			if (isset($base->redirect) && $bNoDoRedirect === false) {
			
                $base = self::get($sName, $base->redirect);
			}
			
			self::$_aConfCache[$sNameCache] = $base;
		}

		if (!self::$_aConfCache[$sNameCache]) {
			
			$oDebug = Debug::getInstance();
			$oDebug->error('The configuration file '.$sName.' is in error!');
		}
		
		return self::$_aConfCache[$sNameCache];
	}
	
	/**
	 * get the bundle name location or the actualy bundle name if they isn't location
	 *
	 * @access public
	 * @param  string $sName name of the configuration
	 * @return string
	 */
	public static function getBundleLocationName(string $sName): string
	{
	    $oConfig = self::get($sName, null, true);

	    if (isset($oConfig->redirect)) { return $oConfig->redirect; } else { return PORTAL; }
	}

	/**
	 * get file content and merge if not exists
	 *
	 * @access private
	 * @param  string $sFileToMerge file to get
	 * @param  \stdClass $base base
	 * @return \stdClass
	 */
	private static function  _mergeAndGetConf(string $sFileToMerge, \stdClass $base) : \stdClass
	{
		$oConfFiles = json_decode(file_get_contents($sFileToMerge));

		if (is_object($oConfFiles)) {

			list($oConfFiles, $base) = self::_recursiveGet($oConfFiles, $base);
			return $base;
		} else {

			echo "The Json ".$sFileToMerge." has an error! Please verify!\n";
			$oDebug = Debug::getInstance();
			$oDebug->error("The Json ".$sFileToMerge." has an error! Please verify!\n");
            new \Exception("The Json ".$sFileToMerge." has an error! Please verify!\n");
		}
	}

	/**
	 * recursive merge
	 *
	 * @access private
	 * @param  $oConfFiles
	 * @param  \stdClass $base
	 * @return multitype:array multitype:array
	 */
	private static function _recursiveGet($oConfFiles, \stdClass $base) : array
	{
		foreach ($oConfFiles as $sKey => $mOne) {

			if (is_object($oConfFiles) && is_object($base) && !isset($base->$sKey)) {

				$base->$sKey = $oConfFiles->$sKey;
			} else if (is_array($oConfFiles) && is_array($base) && !isset($base[$sKey])) {

				$base[$sKey] = $oConfFiles[$sKey];
            } else if (!isset($base->$sKey) && is_array($mOne)) {

				$base->$sKey = new \StdClass;
				list($oConfFiles, $base) = self::_recursiveGet($mOne, $base->$sKey);
			}
		}

		return array($oConfFiles, $base);
	}
}
