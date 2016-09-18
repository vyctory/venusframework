<?php

/**
 * Url Manager
 *
 * @category  	core
 * @package   	core\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\core;

/**
 * Url Manager
 *
 * @category  	core
 * @package   	core\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class UrlManager
{
    /**
     * The base Uri to construct the route
     * @var string
     */
    private $_sBaseUri = '';

    /**
     * create an URL
     *
     * @access public
     * @param  string $sCode code of the url between "routes" and "route" in Route.conf
     * @param  array $aParams parameters to create the url
     * @return string
     *
     * @tutorial	If I have this route I could make my URL:
     *
     * 				"menu_edit": {
     *					"route": "[/:language]/menu[/:id]/edit/",
     *					"controller": "\\src\\BackOffice\\Controller\\MenuManager",
     *					"action": "edit",
     *					"constraints": {
     * 						"language": "[a-z]{0,2}",
     * 						"id": "[0-9]+"
     *					},
     *					"content_type": "html"
     *				},
     *
     *				I must write this:
     *
     *				$oUrlManager = new \Venus\core\UrlManager;
     *				$sUrl = $oUrlManager->getUrl('menu_edit', array('language' => 'vn', 'id' => 125));
     */
    public function getUrl(string $sCode, array $aParams = array()) : string
    {
        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {

            foreach (Config::get('Route') as $sHost => $oHost) {

                if ((!strstr($sHost, '/') && $sHost == $_SERVER['HTTP_HOST'])
                    || (strstr($sHost, '/')
                    && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost))) {

                    if (strstr($sHost, '/')
                        && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost)) {

                        $this->_sBaseUri = preg_replace('#^[^/]+#', '', $sHost);
                    }

                    if (isset($oHost->routes)) {

                        foreach($oHost->routes as $sKey => $oRoute) {

                            if ($sKey === $sCode) {

                                $sRoute = $this->_sBaseUri.$oRoute->route;

                                if (isset($oRoute->constraints)) {

                                    foreach ($oRoute->constraints as $sName => $sType) {

                                        if (!isset($aParams[$sName])) { $aParams[$sName] = ''; }

                                        if (preg_match('#'.$sType.'#', $aParams[$sName])) {

                                            if ($aParams[$sName]) { $sRoute = str_replace('[/:'.$sName.']', '/'.$aParams[$sName], $sRoute); } else { $sRoute = str_replace('[/:'.$sName.']', '', $sRoute); }
                                            $sRoute = str_replace('[:'.$sName.']', $aParams[$sName], $sRoute);
                                            continue;
                                        } else if (isset($oRoute->defaults_constraints)
                                            && isset($oRoute->defaults_constraints->{$sName})
                                            && preg_match('#'.$sType.'#', $oRoute->defaults_constraints->{$sName})) {

                                            continue;
                                        }

                                        throw new \Exception('For the route '.$sCode.' the parameter '.$sName.' is not good!');
                                    }
                                }

                                return $sRoute;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * encode text for the url
     *
     * @access public
     * @param  string $sStringToEncode text
     * @return string
     */
    public function encodeToUrl(string $sStringToEncode) : string
    {
        if (!is_string($sStringToEncode)) {

            throw new \Exception();
        }

        $sStringToEncode = str_replace(['à','á','â','ã','ä','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ','À','Á','Â','Ã','Ä','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý'],
            ['a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','y','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','U','Y'],
                $sStringToEncode);

        $sStringToEncode = preg_replace('/[^a-zA-Z0-9_]+/', '_', preg_quote($sStringToEncode));
        return strtolower($sStringToEncode);
    }
}
