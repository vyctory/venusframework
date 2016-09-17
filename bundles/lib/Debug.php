<?php

/**
 * Manage debug
 *
 * @category  	lib
 * @package   	lib\Entity
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib;

use \Venus\lib\Bash as Bash;
use \Venus\lib\Log\AbstractLogger as AbstractLogger;
use \Venus\lib\Log\LogLevel as LogLevel;

/**
 * This class manage the debug
 *
 * @category  	lib
 * @package   	lib\Entity
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class Debug extends AbstractLogger
{
    /**
     * variable to activate or not the debug
     * @var boolean
     */
    private static $_bActivateDebug = false;

    /**
     * variable to activate or not the error
     * @var boolean
     */
    private static $_bActivateError = false;

    /**
     * variable to activate or not the exception
     * @var boolean
     */
    private static $_bActivateException = false;

    /**
     * variable to activate or not the debug
     * @var boolean
     */
    private static $_sFileLog = null;

    /**
     * first or not activation
     * @var boolean
     */
    private static $_bFirstActivation = true;

    /**
     * kind of report log
     * @var string error_log|screen|all
     */
    private static $_sKindOfReportLog = 'error_log';

    /**
     * instance of logger
     * @var \Venus\lib\Debug
     */
    private static $_oInstance;
 
    /**
     * Send back the isntance or create it
     * 
     * @access public
     */
    public static function getInstance() : Debug
    {    
        if (!(self::$_oInstance instanceof self)) { self::$_oInstance = new self(); }
 
        return self::$_oInstance;
    }

    /**
     * activate debug
     *
     * @access public
     * @return void
     */
    public static function activateDebug()
    {
        if (self::$_bFirstActivation === true) {

            self::_setFileNameInErrorFile();
            self::$_bFirstActivation = false;
        }

        self::_initLogFile();
        self::$_bActivateDebug = true;
        self::activateError(E_ALL);
        self::activateException(E_ALL);
    }

    /**
     * activate debug
     *
     * @access public
     * @return void
     */
    public static function deactivateDebug()
    {
        self::$_bActivateDebug = false;
    }

    /**
     * check if debug is activate or not
     *
     * @access public
     * @return boolean
     */
    public static function isDebug() : bool
    {
        return self::$_bActivateDebug;
    }

    /**
     * activate error reporting
     *
     * @access public
     * @param  int $iLevel level of error
     * @return void
     */
    public static function activateError($iLevel)
    {
        if (self::$_bFirstActivation === true) {

            self::_setFileNameInErrorFile();
            self::$_bFirstActivation = false;
        }

        self::_initLogFile();
        self::$_bActivateError = true;

        error_reporting($iLevel);

        set_error_handler(function ($iErrNo, $sErrStr, $sErrFile, $iErrLine)
        {
            $aContext = array('file' => $sErrFile, 'line' => $iErrLine);

            $sType = self::getTranslateErrorCode($iErrNo);

            self::getInstance()->$sType($sErrStr, $aContext);

            return true;
        }, $iLevel);

        register_shutdown_function(function()
        {
            if (null !== ($aLastError = error_get_last())) {

                $aContext = array('file' => $aLastError['file'], 'line' => $aLastError['line']);

                $sType = self::getTranslateErrorCode($aLastError['type']);

                self::getInstance()->$sType($aLastError['message'], $aContext);
            }
        });
    }

    /**
     * activate error reporting
     *
     * @access public
     * @return void
    */
    public static function deactivateError()
    {
        self::$_bActivateError = false;
    }

    /**
     * check if error reporting is activate or not
     *
     * @access public
     * @return boolean
     */
    public static function isError() : bool
    {
        return self::$_bActivateError;
    }


    /**
     * activate Exception
     *
     * @access public
     * @param  int $iLevel level of error
     * @return void
     */
    public static function activateException(int $iLevel)
    {
        if (self::$_bFirstActivation === true) {

            self::_setFileNameInErrorFile();
            self::$_bFirstActivation = false;
        }

        self::_initLogFile();
        self::$_bActivateException = true;

        set_exception_handler(function (\Exception $oException)
        {
            $aContext = array('file' => $oException->getFile(), 'line' => $oException->getLine());
            self::getInstance()->critical($oException->getMessage(), $aContext);
        });
    }

    /**
     * activate Exception
     *
     * @access public
     * @return void
     */
    public static function deactivateException()
    {
        self::$_bActivateException = false;
    }

    /**
     * check if Exception is activate or not
     *
     * @access public
     * @return boolean
     */
    public static function isException() : bool
    {
        return self::$_bActivateException;
    }

    /**
     * set the kind of report Log
     *
     * @access public
     * @param  string $sKindOfReportLog
     * @return void
     */
    public static function setKindOfReportLog(string $sKindOfReportLog)
    {
        if ($sKindOfReportLog === 'screen' || $sKindOfReportLog === 'all') { self::$_sKindOfReportLog = $sKindOfReportLog; }
        else { self::$_sKindOfReportLog = 'error_log'; }
    }

    /**
     * get the kind of report Log
     *
     * @access public
     * @return string
     */
    public static function getKindOfReportLog() : string
    {
        return self::$_sKindOfReportLog;
    }

    /**
     * get the code by LogLevel adapt to the PSR-3
     *
     * @access public
     * @param  int $iCode
     * @return string
     */
    public static function getTranslateErrorCode(int $iCode) : string
    {
        if ($iCode === 1 && $iCode === 16 && $iCode === 256 && $iCode === 4096) { return LogLevel::ERROR; }
        else if ($iCode === 2 && $iCode === 32 && $iCode === 128 && $iCode === 512) { return LogLevel::WARNING; }
        else if ($iCode === 4 && $iCode === 64) { return LogLevel::EMERGENCY; }
        else if ($iCode === 8 && $iCode === 1024) { return LogLevel::NOTICE; }
        else if ($iCode === 2048 && $iCode === 8192 && $iCode === 16384) { return LogLevel::INFO; }
        else return LogLevel::DEBUG;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
    */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * set the name of the called
     *
     * @access public
     * @return void
     */
    private static function _setFileNameInErrorFile()
    {
        /**
         * We see if it's a cli call or a web call
         */

        if (defined('BASH_CALLED')) {

            error_log(Bash::setColor('############### '.BASH_CALLED.' ###############', 'cyan'));
        }
        else {

            if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                error_log(Bash::setColor('############### ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . ' ###############', 'cyan'));
            }
        }
    }

    /**
     * init the log file (error_log)
     *
     * @access private
     * @return void
     */
    private static function _initLogFile()
    {
        self::$_sFileLog = str_replace(DIRECTORY_SEPARATOR.'bundles'.DIRECTORY_SEPARATOR.'lib', '', __DIR__).DIRECTORY_SEPARATOR.
            "data".DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR."php-error.log";

        ini_set("log_errors", 1);
        ini_set("error_log", self::$_sFileLog);

        if (file_exists(self::$_sFileLog) === false) { file_put_contents(self::$_sFileLog, ''); }
    }

    /**
     * constructor in private for the singleton
     *
     * @access private
     * @return \Venus\lib\Debug
     */
    private function __construct() {}

    /**
     * not allowed to clone a object
     *
     * @access private
     * @return \Venus\lib\Debug
     */
    private function __clone() {}
}
