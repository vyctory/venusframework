<?php

/**
 * Manage Less 
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
 * Manage Less
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
class Less
{
    /**
     * @var string
     */
    const LESS_WINDOWS = LESS_WINDOWS;
    
	/**
	 * translate the content
	 *
	 * @access public
	 * @param  mixed $sFile content to translate
	 * @return void
	 */
	public static function toCss(string $sFile)
	{
	    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	        
	        $sCmd = self::LESS_WINDOWS." ".$sFile." --watch";
            $sContent = shell_exec($sCmd);
	    }
	    else {
	       
	        $sCmd = "lessc ".$sFile." --no-color 2>&1";
            $sContent = shell_exec($sCmd);
	    }
	    
        header("content-type:text/css");
        echo $sContent;
	}
}
