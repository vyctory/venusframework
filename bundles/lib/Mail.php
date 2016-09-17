<?php

/**
 * Manage Form
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
 * This class manage the Form
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
class Mail
{
	/**
	 * the recipient of mail
	 * @access private
	 * @var    array
	 */
	private $_aRecipient = array();

	/**
	 * the from of mail
	 * @access private
	 * @var    string
	*/
	private $_sFrom = "no-reply@iscreenway.com";

	/**
	 * the subject of mail
	 * @access private
	 * @var    string
	 */
	private $_sSubject = "nosubject";

	/**
	 * the content of mail
	 * @access private
	 * @var    string
	 */
	private $_sMessage = "";

	/**
	 * the format of mail
	 * @access private
	 * @var    string
	 */
	private $_sFormat = "TXT"; // valeur : TXT ou HTML;

	/**
	 * file to send with mail
	 * @access private
	 * @var    array
	 */
	private $_aAttachments = array();

	/**
	 * add a recipient of mail
	 *
	 * @access public private
	 * @param  string $sRecipient
	 * @return Mail
	 */
	public function addRecipient(string $sRecipient) : Mail
	{
		$this->_aRecipient[] = $sRecipient;
		return $this;
	}

	/**
	 * add a file to the mail
	 *
	 * @access public private
	 * @param  string $sFileName
	 * @param  string $sContent
	 * @param  string $sType
	 * @return bool
	 */
	public function attachFile(string $sFileName, string $sContent, string $sType) : bool
	{
		$this->_aAttachments[] = array(
			"name" => $sFileName,
			"content" => $sContent,
			"type" => $sType
		);
		
		return true;
	}

	/**
	 * set the from of mail
	 *
	 * @access public private
	 * @param  string $sFrom
	 * @return Mail
	 */
	public function setFrom(string $sFrom) : Mail
	{
		$this->_sFrom = $sFrom;
		return $this;
	}

	/**
	 * set the subjet of mail
	 *
	 * @access public private
	 * @param  string $sSubject
	 * @return Mail
	 */
	public function setSubject(string $sSubject) : Mail
	{
		$this->_sSubject = $sSubject;
		return $this;
	}

	/**
	 * set the message of mail
	 *
	 * @access public private
	 * @param  string $sMessage
	 * @return Mail
	 */
	public function setMessage(string $sMessage) : Mail
	{
		$this->_sMessage = $sMessage;
		return $this;
	}

	/**
	 * set the format HTML of mail
	 *
	 * @access public private
	 * @return Mail
	 */
	public function setFormatHtml() : Mail
	{
		$this->_sFormat = "HTML";
		return $this;
	}

	/**
	 * set the format HTML of mail
	 *
	 * @access public private
	 * @return Mail
	 */
	public function setFormatText() : Mail
	{
		$this->_sFormat = "TXT";
		return $this;
	}

	/**
	 * send the mail
	 *
	 * @access public private
	 * @return bool
	 */
	public function send() : bool
	{
		$sHeaders = 'From: ' . $this->_sFrom . "\r\n";

		if (empty($this->_aAttachments)) {
			
			if ($this->_sFormat == "HTML") {
				
				$sHeaders .= 'MIME-Version: 1.0' . "\r\n";
				$sHeaders .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
			}
			
			return mail(implode(',', $this->_aRecipient), $this->_sSubject, $this->_sMessage, $sHeaders);
		}
		else {

			$sBoundary = "_" . md5(uniqid(rand()));

			$sAttached = "";

			foreach ($this->_aAttachments as $aAttachment) {
				
				$sAttached_file = chunk_split(base64_encode($aAttachment["content"]));
				$sAttached = "\n\n" . "--" . $sBoundary . "\nContent-Type: application; name=\"" . $aAttachment["name"] . "\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"" . $aAttachment["name"] . "\"\r\n\n" . $sAttached_file . "--" . $sBoundary . "--";
			}

			$sHeaders = 'From: ' . $this->_sFrom . "\r\n";
			$sHeaders .= "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$sBoundary\"\r\n";

			$sBody = "--" . $sBoundary . "\nContent-Type: " . ($this->_sFormat == "HTML" ? "text/html" : "text/plain") . "; charset=UTF-8\r\n\n" . $this->_sMessage . $sAttached;

			return mail(implode(',', $this->_aRecipient), $this->_sSubject, $sBody, $sHeaders);
		}
	}
}
