<?php

/**
 * Config Manager
 *
 * @category  	core
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/vyctory)
 * @license   	https://github.com/vyctory/venusframework/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/vyctory/venusframework
 * @link      	https://github.com/vyctory
 * @since     	3.0
 * @tutorial    https://vyctory.github.io/venusframework/index.html
 */
namespace Venus\core;

/**
 * Class Config
 * @package Venus\core
 */
class Config
{
    /**
     * @var
     */
	private static $config;

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
     * @return mixed
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * @param mixed $config
     */
    public static function setConfig($config)
    {
        self::$config = $config;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return self::$config->$name;
    }

    /**
     * @param string $configFile
     * @param string $source
     */
	public static function load(string $configFile, string $source = 'Implementation by router')
    {
	    if (file_exists($configFile)) {
	        $jsonObject = json_decode(file_get_contents($configFile));
            self::analyseConfigJson($jsonObject, $configFile);
	    }
	    else {
	        new \Exception('file '.$configFile.' doesn\'t exist. Please verify it. Source : '.$source);
        }
    }

    /**
     * @param $jsonObject
     * @param $configFile
     */
    public static function analyseConfigJson($jsonObject, $configFile)
    {
        // check if it exists import file (you must enter them in an array)
        if (isset($jsonObject->import) && is_array($jsonObject->import)) {

            foreach ($jsonObject->import as $oneImport) {

                self::load(preg_replace('#^(.+[/\\\\])[a-zA-Z_\-0-9]+\.json$#', '$1', $configFile).$oneImport,
                    'Import file from json config file : '.$configFile);
            }
        }

        self::setConfig(self::mergeObject(self::getConfig(), $jsonObject));
    }

    /**
     * @param \stdClass $objectBase
     * @param \stdClass $objectToMerge
     * @return \stdClass
     */
    private static function mergeObject($objectBase, $objectToMerge) : \stdClass
    {
        if ($objectBase === null) { $objectBase = new \stdClass(); }

        foreach($objectToMerge as $key => $one) {
            if (!isset($objectBase->$key)) {
                $objectBase->$key = $one;
            }
            else if ($one instanceof \stdClass) {
                $objectBase->$key = self::mergeObject($objectBase->$key, $one);
            }
            else {
                $objectBase->$key = $one;
            }
        }

        return $objectBase;
    }
}
