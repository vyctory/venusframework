<?php

/**
 * Manage upload
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
 * This class manage the upload
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
class Upload
{
	/**
	 * max size of upload
	 * @var int
	 */
	private $_iMaxFile = 100000;

	/**
	 * error while upload
	 * @var string
	 */
	private $_sError = '';

	/**
	 * array of extensions allowed
	 * @var array
	 */
	private $_aAllowExtension = array();

	/**
	 * final extension
	 * @var string
	 */
	private $_sExtension = null;

	/**
	 * height of image
	 * @var int
	 */
	private $_iHeight = null;

	/**
	 * width of image
	 * @var int
	 */
	private $_iWidth = null;

	/**
	 * image name
	 * @var string
	 */
	private $_sName = null;

	/**
	 * if the crop or resize
	 * @var bool
	 */
	private $_bProportion = true;

	/**
	 * get an upload file
	 *
	 * @access public
	 * @param  string $sFile
	 * @return bool|object
	 */
	public function upload(string $sFile)
	{
		if ($_FILES[$sFile]['error'] > 0) {

			$this->_sError = "Error while the upload";
			return false;
		}

		if ($_FILES[$sFile]['size'] > $this->_iMaxFile) {

			$this->_sError = "The file is too big";
			return false;
		}

		$sExtension = strtolower(substr(strrchr($_FILES[$sFile]['name'], '.'), 1));

		if (count($this->_aAllowExtension) > 0 && !in_array($sExtension ,$this->_aAllowExtension)) {

			$this->_sError = "The extension is not good";
			return false;
		}

		$sPath = str_replace('bundles'.DIRECTORY_SEPARATOR.'lib',
			'data'.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR, __DIR__);

		if ($this->_sExtension === null) { $this->setExtension($sExtension); }

		if ($this->_sName) { $sName = $sPath.$this->_sName.'.'.$this->_sExtension; }
		else { $sName = $sPath.md5(uniqid(rand(), true)).'.'.$this->_sExtension;}

		if ($this->_bProportion === true && ($this->_iWidth || $this->_iHeight)) {

			$aImageSizes = getimagesize($_FILES[$sFile]['tmp_name']);

			$fRatio = min($aImageSizes[0] / $this->_iWidth, $aImageSizes[1] / $this->_iHeight);
    		$iHeight =  $aImageSizes[1] / $fRatio;
    		$iWidth =  $aImageSizes[0] / $fRatio;
    		$fY = ($iHeight - $this->_iHeight) / 2 * $fRatio;
    		$fX = ($iWidth - $this->_iWidth) / 2 * $fRatio;

			$rNewImage = imagecreatefromjpeg($_FILES[$sFile]['tmp_name']);

			$rNewImgTrueColor = imagecreatetruecolor($this->_iWidth , $this->_iHeight);
			imagecopyresampled($rNewImgTrueColor , $rNewImage, 0, 0, $fX, $fY, $this->_iWidth, $this->_iHeight, $iWidth * $fRatio - $fX * 2, $iHeight * $fRatio - $fY * 2);

			imagejpeg($rNewImgTrueColor , $sName, 100);
		}
		else {

			$bResultat = move_uploaded_file($_FILES[$sFile]['tmp_name'], $sName);

			if ($bResultat) { return true; }
		}


	}

	/**
	 * set max size of upload
	 *
	 * @access public
	 * @param  int $iMaxFile
	 * @return \Venus\lib\Upload
	 */
	public function setMaxSize(int $iMaxFile) : Upload
	{
		$this->_iMaxFile = $iMaxFile;
		return $this;
	}

	/**
	 * set allow extensions to upload
	 *
	 * @access public
	 * @param  array $aAllowExtension
	 * @return \Venus\lib\Upload
	 */
	public function setAllowExtension(array $aAllowExtension) : Upload
	{
		$this->_aAllowExtension = $aAllowExtension;
		return $this;
	}

	/**
	 * set extension of final file
	 *
	 * @access public
	 * @param  string $sExtension
	 * @return \Venus\lib\Upload
	 */
	public function setExtension(string $sExtension) : Upload
	{
		$this->_sExtension = $sExtension;
		return $this;
	}

	/**
	 * set height of image
	 *
	 * @access public
	 * @param  int $iHeight
	 * @return \Venus\lib\Upload
	 */
	public function setHeight(int $iHeight) : Upload
	{
		$this->_iHeight = $iHeight;
		return $this;
	}

	/**
	 * set width of image
	 *
	 * @access public
	 * @param  int $iWidth
	 * @return \Venus\lib\Upload
	 */
	public function setWidth(int $iWidth) : Upload
	{
		$this->_iWidth = $iWidth;
		return $this;
	}

	/**
	 * set name of image
	 *
	 * @access public
	 * @param  string $sName
	 * @return \Venus\lib\Upload
	 */
	public function setName(string $sName) : Upload
	{
		$this->_sName = $sName;
		return $this;
	}
}
