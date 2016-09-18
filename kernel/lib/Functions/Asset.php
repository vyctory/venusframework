<?php

/**
 * Manage Template
 *
 * @category  	Venus
 * @package     Venus\lib\Functions
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	3.0.0
 */
namespace Venus\lib\Functions;

/**
 * This class manage the Template
 *
 * @category  	Venus
 * @package     Venus\lib\Functions
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	3.0.0
 */
class Asset 
{
	/**
	 * run before
	 *
	 * @access public
	 * @param  array $aParams parameters
	 * @return string
	 */
	public function replaceBy(array $aParams = array()) : string
	{
	    $aParams['template'] = trim(str_replace(["'", '"'], "", $aParams['template']));
	    
		if (isset($aParams['template'])) {

		    $aTemplates = explode(';', $aParams['template']);
		    $sGetUrl = 'getCss?';
		    
		    foreach ($aTemplates as $sTemplate) {
		     
    		    if (strstr($sTemplate, 'css/')) {
    			
    		        $sGetUrl .= $sTemplate.'&';
    		    }
    		    else if (strstr($sTemplate, 'js/')) {
    		        
    		        $sGetUrl .= $sTemplate.'&';
    		    }
		    }
		   
		    if (defined('ASSET_VERSION') && ASSET_VERSION !== false) {
		        
		        $sGetUrl .= ASSET_VERSION;
		    }

			return $sGetUrl;
		}
	}
}
