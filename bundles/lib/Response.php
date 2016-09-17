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

use \Venus\core\Mother as Mother;
use \Venus\lib\Request as Request;
use \Venus\lib\Response\Json as Json;
use \Venus\lib\Response\Mock as Mock;
use \Venus\lib\Response\Yaml as Yaml;

/**
 * This class manage the Form
 *
 * @property \Venus\lib\Request\Headers headers
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Response extends Mother
{
	const HTTP_CONTINUE = 100;
	const HTTP_SWITCHING_PROTOCOLS = 101;
	const HTTP_PROCESSING = 102;
	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
	const HTTP_NO_CONTENT = 204;
	const HTTP_RESET_CONTENT = 205;
	const HTTP_PARTIAL_CONTENT = 206;
	const HTTP_MULTI_STATUS = 207;
	const HTTP_ALREADY_REPORTED = 208;
	const HTTP_CONTENT_DIFFERENT = 210;
	const HTTP_IM_USED = 226;
	const HTTP_MULTIPLE_CHOICES = 300;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_MOVED_TEMPORARILY = 302;
	const HTTP_SEE_OTHER = 303;
	const HTTP_NOT_MODIFIED = 304;
	const HTTP_USE_PROXY = 305;
	const HTTP_RESERVED = 306;
	const HTTP_TEMPORARY_REDIRECT = 307;
	const HTTP_PERMANENTLY_REDIRECT = 308;
	const HTTP_TOO_MANY_REDIRECTS = 310;
	const HTTP_BAD_REQUEST = 400;
	const HTTP_UNAUTHORIZED = 401;
	const HTTP_PAYMENT_REQUIRED = 402;
	const HTTP_FORBIDDEN = 403;
	const HTTP_NOT_FOUND = 404;
	const HTTP_METHOD_NOT_ALLOWED = 405;
	const HTTP_NOT_ACCEPTABLE = 406;
	const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	const HTTP_REQUEST_TIMEOUT = 408;
	const HTTP_CONFLICT = 409;
	const HTTP_GONE = 410;
	const HTTP_LENGTH_REQUIRED = 411;
	const HTTP_PRECONDITION_FAILED = 412;
	const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
	const HTTP_REQUEST_URI_TOO_LONG = 414;
	const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const HTTP_EXPECTATION_FAILED = 417;
	const HTTP_I_AM_A_TEAPOT = 418;
	const HTTP_MISDIRECTED_REQUEST = 421;
	const HTTP_UNPROCESSABLE_ENTITY = 422;
	const HTTP_LOCKED = 423;
	const HTTP_METHOD_FAILURE = 424;
	const HTTP_UNORDERED_COLLECTION = 425;
	const HTTP_UPGRADE_REQUIRED = 426;
	const HTTP_PRECONDITION_REQUIRED = 428;
	const HTTP_TOO_MANY_REQUESTS = 429;
	const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
	const HTTP_RETRY_WITH = 449;
	const HTTP_BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS = 450;
	const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
	const HTTP_UNRECOVERABLE_ERROR = 456;
	const HTTP_CLIENT_HAS_CLOSED_CONNECTION = 499;
	const HTTP_INTERNAL_SERVER_ERROR = 500;
	const HTTP_NOT_IMPLEMENTED = 501;
	const HTTP_BAD_GATEWAY = 502;
	const HTTP_SERVICE_UNAVAILABLE = 503;
	const HTTP_GATEWAY_TIMEOUT = 504;
	const HTTP_VERSION_NOT_SUPPORTED = 505;
	const HTTP_VARIANT_ALSO_NEGOTIATES = 506;
	const HTTP_INSUFFICIENT_STORAGE = 507;
	const HTTP_LOOP_DETECTED = 508;
	const HTTP_BANDWIDTH_LIMIT_EXCEEDED = 509;
	const HTTP_NOT_EXTENDED = 510;
	const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
	const HTTP_WEB_SERVER_IS_RETURNING_AN_UNKNOWN_ERROR = 520;

	public static $statusTexts = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',		
		210 => 'Content Different',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Moved Temporarily',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		310 => 'Too many Redirects',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Payload Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Method failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		451 => 'Unavailable For Legal Reasons',
		456 => 'Unrecoverable Error',
		499 => 'Client has closed connection',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		520 => 'Web server is returning an unknown error',
	];

	/**
	 * the translation language
	 * @var string
	 */
	private static $_sKindOfReturn = 'json';

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var int
	 */
	private $statusCode = 0;

	/**
	 * Response constructor.
     */
	public function __construct()
	{
		/**
		 * @return \Venus\lib\Request
         */
		$this->headers = function() { $request = new Request(); return $request->headers; };
	}

	/**
	 * set the language if you don't want take the default language of the configuration file
	 *
	 * @access public
	 * @param  string $sKindOfReturn
	 * @return void
	 */
	public static function setKindOfReturn(string $sKindOfReturn)
	{
		self::$_sKindOfReturn = $sKindOfReturn;
	}

	/**
	 * translate the content
	 *
	 * @access public
	 * @param  mixed $mContent content to translate
	 * @return mixed
	 */
	public function translate($mContent)
	{
		if (self::$_sKindOfReturn === 'yaml') { return Yaml::translate($mContent); }
		else if (self::$_sKindOfReturn === 'mock') { return Mock::translate($mContent); }
		else { return Json::translate($mContent); }
	}

	/**
	 * @return string
     */
	public function getContent() : string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 * @return Response
     */
	public function setContent(string $content) : Response
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @return int
     */
	public function getStatusCode() : int
	{
		return $this->statusCode;
	}

	/**
	 * @param int $statusCode
	 * @return Response
     */
	public function setStatusCode(int $statusCode) : Response
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	public function send()
	{
		if ($this->getStatusCode() > 0) {
			$this->headers->set('HTTP/1.1 '.$this->getStatusCode().' '.self::$statusTexts[$this->getStatusCode()]);
		}

		echo $this->getContent();
	}
}
