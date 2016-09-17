<?php

/**
 * Manage Image
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

/**
 * This class manage the Image
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
class Image 
{
	/**
	 * the translation language
	 * @var string
	 */	
	private $_sLanguage = LANGUAGE;

    /**
     * set the language if you don't want take the default language of the configuration file
     *
     * @access public
     * @param int $iImageUri
     * @param  int $iWidth
     * @param  int $iHeight
     * @param bool $bKeepDimension
     * @return void
     */
	public static function showImageInSize(int $iImageUri, int $iWidth, int $iHeight, bool $bKeepDimension = false)
	{	
	    $aSize = getimagesize($iImageUri);
	    $rActualImage = imagecreatefromjpeg($iImageUri);
	    
	    $ImageChoisie = imagecreatefromjpeg($_FILES['ImageNews']['tmp_name']);
        $TailleImageChoisie = getimagesize($_FILES['ImageNews']['tmp_name']);

        $rNewImage = imagecreatetruecolor($iWidth , $iHeight);

        if ($bKeepDimension === false) {
        
            imagecopyresampled($rNewImage, $rActualImage, 0, 0, 0, 0, $iWidth, $iHeight, $aSize[0], $aSize[1]);
        }
        else {
        
            if ($aSize[0] > $aSize[1]) {

                $rWhite = imagecolorallocate($rNewImage, 255, 255, 255);
                imagefilledrectangle($rNewImage, 0, 0, $iWidth, $iHeight, $rWhite);
                $fCoef = $aSize[1] / $aSize[0];
                $iHeight = round($iWidth * $fCoef);
                $iDestY = round(($iWidth - $iHeight) / 2);
                $iDestX = 0;
            }
            else {

                $rWhite = imagecolorallocate($rNewImage, 255, 255, 255);
                imagefilledrectangle($rNewImage, 0, 0, $iWidth, $iHeight, $rWhite);
                $fCoef = $aSize[0] / $aSize[1];
                $iWidth = round($iHeight * $fCoef);
                $iDestX = round(($iHeight - $iWidth) / 2);
                $iDestY = 0;
            }
            
            $rWhite = imagecolorallocate($rNewImage, 255, 255, 255);
            imagefilledrectangle($rNewImage, 0, 0, $iWidth, $iHeight, $rWhite);
            imagecopyresampled($rNewImage, $rActualImage, $iDestX, $iDestY, 0, 0, $iWidth, $iHeight, $aSize[0], $aSize[1]);
        }
        
        imagedestroy($rActualImage);
        $NomImageChoisie = explode('.', $rNewImage);
        $NomImageExploitable = time();
      
        header('Content-Type: image/jpeg');
        imagejpeg($rNewImage , null, 100);
	}
}
