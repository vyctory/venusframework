<?php

/**
 * Manage Asset
 *
 * @category  	lib
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
 * This class manage the Asset
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 *
 * @tutorial	$oAsset = new \Venus\lib\Asset;
 * 				$oAsset->stylesheets(array('css/style.css'));
 */
class Asset
{
	/**
	 * get URL of your javascripts
	 *
	 * @access public
	 * @return array
	 * @internal param array $aJavascript [many can be passed]
	 */
	public function javascripts() : array {
	    
	    $sDefaultPath = 'http://'.$_SERVER['HTTP_HOST'].'/getJs?';
	    $aJavascript = func_get_args();
	    $aReturns = array();
	    
	    foreach($aJavascript as $aJsCombination) {
	        
	        $sJsPath = $sDefaultPath;
	        
	        foreach ($aJsCombination as $sJsToAdd) {
	            
	            $sJsPath = $sJsToAdd.'&';
	        }
	    
    	    if (defined('ASSET_VERSION') && ASSET_VERSION) {
    	        
    	        $sJsPath = ASSET_VERSION;
    	    }
	        
	        $aReturns[] = $sJsPath;
	    }
	    
	    return $aReturns;
	}
	
	/**
	 * get URL of your javascripts
	 * 
	 * @access public
	 * @return array
	 * @internal param array $aCss [many can be passed]
	 * 
	 * @tutoriel 
	 */
	public function stylesheets() : array {
	    
	    $sDefaultPath = 'http://'.$_SERVER['HTTP_HOST'].'/getCss?';
	    $aCss = func_get_args();
	    $aReturns = array();
	    
	    foreach($aCss as $aCssCombination) {
	        
	        $sCssPath = $sDefaultPath;
	        
	        foreach ($aCssCombination as $sCssToAdd) {
	            
	            $sCssPath = $sCssToAdd.'&';
	        }
	    
    	    if (defined('ASSET_VERSION') && ASSET_VERSION) {

                $sCssPath = ASSET_VERSION;
    	    }
	        
	        $aReturns[] = $sCssPath;
	    }
	    
	    return $aReturns;
	}
}
