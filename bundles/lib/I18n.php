<?php

/**
 * Manage Translation
 *
 * @category    lib
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/venus2
 * @link        https://github.com/las93
 * @since       1.0
 */
namespace Venus\lib;

use \Apollina\I18n      as CoreI18n;
use \Venus\core\Config  as Config;

/**
 * This class manage the Translation
 *
 * @category    lib
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/venus2
 * @link        https://github.com/las93
 * @since       1.0
 */
class I18n extends CoreI18n
{
    /**
     * constructor
     * 
     * @access public
     * @return \Venus\lib\I18n
     */
    public function __construct()
    {
        $this->setI18nDirectory(__DIR__.DIRECTORY_SEPARATOR.I18N_DIRECTORY)
             ->setI18nDomain(I18N_DOMAIN)
             ->setIntermediaiteDirectory(DIRECTORY_SEPARATOR.'LC_MESSAGES'.DIRECTORY_SEPARATOR);
        
        foreach (Config::get('Plugins')->list as $iKey => $sPlugin) {
            
            if (file_exists(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.$this->getLanguage().$this->getIntermediaiteDirectory().$sPlugin.'.json')) {

                $oJson = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.$this->getLanguage().$this->getIntermediaiteDirectory().$sPlugin.'.json'));
                
                $fCallBack = function($sValue) use ($oJson)
                {    
                    if (isset($oJson->$sValue)) { return $oJson->$sValue; }
                    else { return ''; }
                };
            }
            
            CoreI18n::addCallback($sPlugin, $fCallBack);
        }
    }

    /**
     * Hilight to add the plugin I18N
     *
     * @access public
     * @param string $sValue value of text to traduct
     * @return string
     */
    public function _(string $sValue) : string
    {
        return $this->getText($sValue);
    }
}
