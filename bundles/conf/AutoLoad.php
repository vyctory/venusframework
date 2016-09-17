<?php

/**
 * autoload of the framework
 * use the PSR-0
 *
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus3/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0.0
 * @filesource	https://github.com/las93/venus3
 * @link      	https://github.com/las93
 * @since     	3.0.0.0
 *
 * new version with SPL to have the capacity to add external autoload
 */
spl_autoload_register(function (string $sClassName)
{

    $sClassName = ltrim($sClassName, '\\');
    $sFileName  = '';
    $iLastNsPos = strrpos($sClassName, '\\');

    if ($iLastNsPos) {

        $sNamespace = substr($sClassName, 0, $iLastNsPos);
        $sClassName = substr($sClassName, $iLastNsPos + 1);
		$sFileName  = str_replace('\\', DIRECTORY_SEPARATOR, $sNamespace).DIRECTORY_SEPARATOR;
    }

    $sFileName = str_replace('/', '\\', $sFileName);
    
    $sFileName .= $sClassName.'.php';

    if (defined('PORTAL')) {

        $sFileClassName = str_replace(PORTAL, PORTAL.DIRECTORY_SEPARATOR.'app', str_replace(['\\', '/'], DIRECTORY_SEPARATOR, str_replace('conf', DIRECTORY_SEPARATOR, __DIR__).str_replace('Venus\\', '', $sFileName)));
        $sFileClassName = preg_replace('/(tests\\\\[^\\\\]+\\\\)/', '$1app\\', $sFileClassName);
        $sFileClassName = preg_replace('/(src\\\\[^\\\\]+\\\\)/', '$1app\\', $sFileClassName);
        $sFileClassName = str_replace('\\\\', '\\', $sFileClassName);
        $sFileClassName = preg_replace('#bundles//tests/([^/]+)#', 'bundles/tests/$1/app', $sFileClassName);
        $sFileClassName = preg_replace('#bundles//src/([^/]+)#', 'bundles/src/$1/app', $sFileClassName);
        $sFileClassName = str_replace('app\\app', 'app', $sFileClassName);
        $sFileClassName = str_replace('app/app', 'app', $sFileClassName);

        if (strstr($sFileName, 'Venus\\') && file_exists($sFileClassName)) {

        	require $sFileClassName;
        }
    }
    else {

        if (strstr($sFileName, 'Venus\\') && file_exists(preg_replace('#^(src/[a-zA-Z0-9_]+/)#', '$1app/', str_replace(['\\', '/'], '/', str_replace('conf', '', __DIR__).str_replace('Venus\\', '', $sFileName))))) {

            require preg_replace('#^(src/[a-zA-Z0-9_]+/)#', '$1app/', str_replace(['\\', '/'], '/', str_replace('conf', '', __DIR__).str_replace('Venus\\', '', $sFileName)));
        }
    }
});

/**
 * Load the composer autoload
 */

if (file_exists(preg_replace('#bundles[/\\\\]conf#', '', __DIR__).'vendor/autoload.php')) {

    include preg_replace('#bundles[/\\\\]conf#', '', __DIR__).'vendor/autoload.php';
}

/**
 * Load the autoload file (or simple files) defined in the Const.conf
 */

$oConfig = \Venus\core\Config::get('Const');

if (isset($oConfig) && isset($oConfig->autoload)) {
    
    $oAutoloadConf = $oConfig->autoload;
    
    foreach ($oAutoloadConf as $sFile) {
    
        require __DIR__.'/../'.$sFile;
    }
}
