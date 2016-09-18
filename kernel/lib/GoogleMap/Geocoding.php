<?php

/**
 * Google Map Pro
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0.2
 */
namespace Venus\lib\GoogleMap;

/**
 * Google Map Pro
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0.2
 */
class Geocoding
{
    /**
     * get the URL with the signature for the Pro account of Google Map
     * 
     * @access public
     * @param unknown $sUrlToSign
     * @param unknown $sClientId
     * @param unknown $sPrivateKey
     * @return string
     */
    public static function signUrlForGoogle(string $sUrlToSign, string $sClientId, string $sPrivateKey) : string
    {
        $aUrl = parse_url($sUrlToSign);
        $aUrl['query'] .= '&client=' .$sClientId;

        $aUrlToSign = $aUrl['path']."?".$aUrl['query'];

        $decodedKey = base64_decode(str_replace(array('-', '_'), array('+', '/'), $sPrivateKey));

        $sSignature = hash_hmac("sha1", $aUrlToSign, $decodedKey, true);

        $sEncodedSignature = str_replace(array('+', '/'), array('-', '_'), base64_encode($sSignature));

        $sOriginalUrl = $aUrl['scheme']."://".$aUrl['host'].$aUrl['path'] . "?".$aUrl['query'];

        return $sOriginalUrl. '&signature='. $sEncodedSignature;
    }
}
