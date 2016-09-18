<?php

/**
 * Manage Log class in your application 
 *
 * @category  	Venus\lib
 * @package		Venus\lib\Log\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib\Log;

use \Venus\lib\Debug as Debug;
use \Venus\lib\Log\LoggerInterface  as LoggerInterface ;
use \Venus\lib\Log\LogLevel as LogLevel;

/**
 * Manage Log class in your application 
 *
 * @category  	Venus\lib
 * @package		Venus\lib\Log\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
abstract class AbstractLogger implements LoggerInterface 
{
	/**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */   
    public function log($level, $message, array $context = array())
    {    
        if (!isset($context['file'])) { $context['file'] = __FILE__; }
        if (!isset($context['line'])) { $context['line'] = __LINE__; }
        if ($level === null) { $level = LogLevel::INFO; }
        
        if (Debug::isDebug() === true) {

            if (Debug::getKindOfReportLog() === 'error_log' || Debug::getKindOfReportLog() === 'all') {
                 
                error_log($context['file'].'> (l.'.$context['line'].') '.$message);
            }
            if (Debug::getKindOfReportLog() === 'screen' || Debug::getKindOfReportLog() === 'all') {
    
                echo '<table width="100%" style="background:orange;border:solid red 15px;"><tr><td style="color:black;padding:10px;font-size:18px;">';
                echo $context['file'].'> (l.'.$context['line'].') '.$message.'<br/>'."\n";
                echo '</td></tr></table>';
            }
        }
	}
}
