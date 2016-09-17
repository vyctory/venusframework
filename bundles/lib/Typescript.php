<?php

/**
 * Manage Typescript 
 *
 * @category  	Venus
 * @package		Venus\lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib;

/**
 * Manage Typescript
 *
 * @category  	Venus
 * @package		Venus\lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Typescript
{
    /**
     * @var string
     */
    const TYPESCRIPT_WINDOWS = TYPESCRIPT_WINDOWS;
    
	/**
	 * translate the content
	 *
	 * @access public
	 * @param  string $sFile
	 * @return string
	 */
	public static function toJs(string $sFile) : string
	{
	    $aFile = pathinfo($sFile);
	    $sFolder = uniqid();
	    
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	        
	        $sCmd = self::TYPESCRIPT_WINDOWS." ".$sFile." --outDir ".__DIR__.'../../'.CACHE_DIR.$sFolder.'/';
            $sContent = shell_exec($sCmd);
	    }
	    else {
	       
	        $sCmd = "tsc ".$sFile." --outDir ".__DIR__.'../../'.CACHE_DIR.$sFolder.'/';
            $sContent = shell_exec($sCmd);
	    }
	    
        header("content-type:text/javascript");
        return file_get_contents(__DIR__.'../../'.CACHE_DIR.$sFolder.'/'.$aFile['filename'].'.js');
	}
}
