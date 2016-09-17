<?php

/**
 * Router
 *
 * @category  	core
 * @package   	core\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 */
namespace Venus\core;

use \Venus\core\Security                as Security;
use \Venus\lib\Cache                    as Cache;
use \Venus\lib\Less                     as Less;
use \Venus\lib\PhpDoc                   as PhpDoc;
use \Venus\lib\Typescript               as Typescript;
use \Venus\lib\Request                  as Request;
use \Venus\lib\Vendor                   as Vendor;
use \Venus\core\UrlManager              as UrlManager;
use \Venus\lib\Debug                    as Debug;
use \Venus\lib\Log\LoggerAwareInterface as LoggerAwareInterface;
use \Venus\lib\Log\LoggerInterface      as LoggerInterface;

/**
 * Router
 *
 * @category  	core
 * @package   	core\
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 */
class Router implements LoggerAwareInterface
{
    /**
     * The base Uri to construct the route
     *
     * @access private
     * @var    string
     */
    private $_sBaseUri = '';

    /**
     * get the security of page
     *
     * @access private
     * @var    \Venus\core\Security
     */
    private $_oSecurity = null;

    /**
     * The Routes of the actual host
     *
     * @access private
     * @var    object
     */
    private $_oRoutes = null;

    /**
     * Logger
     *
     * @access private
     * @var    object
     */
    private $_oLogger = null;

    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        $oLogger = Debug::getInstance();
        $this->setLogger($oLogger);
    }

    /**
     * run the routeur
     *
     * @access public
     * @return null|boolean
     */
    public function run()
    {
        date_default_timezone_set(Config::get('Const')->timezone);

        $this->_create_constant();

        if (Request::isHttpRequest()) {
        
            // Search if a Less file exists
            if (defined('LESS_ACTIVE') && LESS_ACTIVE === true) {

                if (strstr($_SERVER['REQUEST_URI'], '.css')
                    && file_exists(preg_replace('/\.css/', '.less', $_SERVER['REQUEST_URI']))) {

                    Less::toCss($_SERVER['REQUEST_URI']);
                    exit;
                }
            }

            // Search if a typescript file exists
            if (defined('TYPESCRIPT_ACTIVE') && TYPESCRIPT_ACTIVE === true) {

                if (strstr($_SERVER['REQUEST_URI'], '.js')
                && file_exists(preg_replace('/\.js/', '.ts', $_SERVER['REQUEST_URI']))) {

                    Typescript::toJs($_SERVER['REQUEST_URI']);
                    exit;
                }
            }

            // Search public files in all plugins
            if ($_SERVER['REQUEST_URI'] !== '/') {

                foreach (Config::get('Plugins')->list as $iKey => $sPlugin) {

                    if (file_exists(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.$_SERVER['REQUEST_URI'])) {

                        echo file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.$_SERVER['REQUEST_URI']);
                        exit;
                    } else if (strstr($_SERVER['REQUEST_URI'], '.css')
                        && file_exists(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.preg_replace('/\.css/', '.less', $_SERVER['REQUEST_URI']))) {

                        Less::toCss(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.preg_replace('/\.css/', '.less', $_SERVER['REQUEST_URI']));
                        exit;
                    } else if (strstr($_SERVER['REQUEST_URI'], '.js')
                        && file_exists(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.preg_replace('/\.js/', '.ts', $_SERVER['REQUEST_URI']))) {

                        Typescript::toJs(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$sPlugin.DIRECTORY_SEPARATOR.'public'.preg_replace('/\.js/', '.ts', $_SERVER['REQUEST_URI']));
                        exit;
                    }
                }
            }

            foreach (Config::get('Route') as $sMultiHost => $oHost) {

                foreach (explode(',', $sMultiHost) as $sHost) {

                    if ((!strstr($sHost, '/') && $sHost == $_SERVER['HTTP_HOST']) || (strstr($sHost, '/')
                        && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost))) {
    
                        $this->_oRoutes = $oHost;

                        if (strstr($sHost, '/')
                            && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost)) {

                            $this->_sBaseUri = preg_replace('#^[^/]+#', '', $sHost);
                        }
    
                        if (isset($oHost->location)) {
    
                            header('Status: 301 Moved Permanently', false, 301);
                            header('Location: '.$oHost->location);
                            exit;
                        } else if (preg_match('#getCss\?#', $_SERVER['REQUEST_URI'])) {

                            foreach ($_GET as $sKey => $sValue) {

                                if (file_exists(str_replace(DIRECTORY_SEPARATOR.'core', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR, __DIR__).$sKey.'.css')) {

                                    echo file_get_contents(str_replace(DIRECTORY_SEPARATOR.'core', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR, __DIR__).$sKey.'.css')."\n";
                                }
                            }

                            exit;
                        } else if (preg_match('#getJs\?#', $_SERVER['REQUEST_URI'])) {

                            foreach ($_GET as $sKey => $sValue) {

                                if (file_exists(str_replace(DIRECTORY_SEPARATOR.'core', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR, __DIR__).$sKey.'.js')) {

                                    echo file_get_contents(str_replace(DIRECTORY_SEPARATOR.'core', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR, __DIR__).$sKey.'.js')."\n";
                                }
                            }

                            exit;
                        } else if (isset($oHost->routes)) {
    
                            foreach ($oHost->routes as $sKey => $oRoute) {

                                $mReturn = $this->_route($oRoute, $_SERVER['REQUEST_URI']);

                                if ($mReturn === 403) {
    
                                    $this->_getPage403();
                                }
                                else if ($mReturn === true) {
    
                                    if (isset($oRoute->cache)) { $this->_checkCache($oRoute->cache); }
    
                                    return true;
                                }
                            }
    
                            $this->_getPage404();
                        }
                    }
                }
            }
        } else if (Request::isCliRequest()) {

            if (isset($_SERVER['argv'])) { $aArguments = $_SERVER['argv']; }
            else { $aArguments = []; }

            define('PORTAL', 'Batch');
            set_include_path(get_include_path().PATH_SEPARATOR.'src'.PATH_SEPARATOR.PORTAL.PATH_SEPARATOR.'public');

            if (!isset($aArguments[1]) && strstr($aArguments[0], '/phpunit')) {

                $sBatchName = "phpunit";
                $aArguments[0] = "bin/console";
                $aArguments[1] = "phpunit";
            } else if (isset($aArguments[1])) {
                $sBatchName = $aArguments[1];
            }
            else {
                $aArguments[1] = 'help';
                $sBatchName = $aArguments[1];
            }

            if (isset(Config::get('Route')->batch->script->{$sBatchName})) {

                $oBatch = Config::get('Route')->batch->script->{$sBatchName};
                array_shift($aArguments);
                array_shift($aArguments);

                $aOptions = array();

                while (count($aArguments) > 0) {

                    if (preg_match('/^-[a-z]/', $aArguments[0])) {

                        $sOptionName = str_replace('-', '', $aArguments[0]);

                        if (isset($aArguments[1])) {
                            $sOptionValue = $aArguments[1];
                        } else {
                            $sOptionValue = '';
                        }

                        if (isset($oBatch->options->$sOptionName) && isset($oBatch->options->$sOptionName->type)
                            && $oBatch->options->$sOptionName->type === false) {

                            $aOptions[$sOptionName] = true;
                            array_shift($aArguments);
                        } else if (isset($oBatch->options->$sOptionName) && isset($oBatch->options->$sOptionName->type)
                            && ($oBatch->options->$sOptionName->type === 'string'
                            || $oBatch->options->$sOptionName->type === 'int')
                        ) {

                            $aOptions[$sOptionName] = $sOptionValue;
                            array_shift($aArguments);
                            array_shift($aArguments);
                        } else {

                            array_shift($aArguments);
                        }
                    } else {

                        array_shift($aArguments);
                    }
                }
            }

            if (isset($oBatch->controller) && isset($oBatch->action)) {

                echo $this->_loadController($oBatch->controller, $oBatch->action, array($aOptions));
            } else {

                if (Request::isCliRequest()) {

                    echo "Error : The batch not exists - please verify your Route or the name passed in your command name.\n";
                }
            }

        }
    }

    /**
     * run the routeur by the forwarsd metho (in the controller)
     *
     * @access public
     * @param  string $sRoute route we wantload
     * @param  array $aParams parameters to passe
     * @return void
     */
    public function runByFoward(string $sRoute, array $aParams)
    {
        $this->_create_constant();

        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {

            foreach (Config::get('Route') as $sHost => $oHost) {

                if ((!strstr($sHost, '/') && $sHost == $_SERVER['HTTP_HOST'])
                    || (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost))) {

                    $this->_oRoutes = $oHost;

                    if (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost)) {

                        $this->_sBaseUri = preg_replace('#^[^/]+#', '', $sHost);
                    }

                    foreach ($oHost->routes as $sKey => $oRoute) {

                        $this->_route($oRoute, $sRoute);
                    }
                }
            }
        }
        else if (defined('STDIN')) {

            $oBatch = Config::get('Route')->batch->script->{$sRoute};
            echo $this->_loadController($oBatch->controller, $oBatch->action, $aParams);
        }
    }

    /**
     * run the error http page
     *
     * @access public
     * @param  int iError http error
     * @return void
     */
    public function runHttpErrorPage(int $iError)
    {
        $this->_create_constant();

        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {

            foreach (Config::get('Route') as $sHost => $oHost) {

                if ((!strstr($sHost, '/') && $sHost == $_SERVER['HTTP_HOST'])
                    || (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost))) {

                    $this->_oRoutes = $oHost->routes;

                    if (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost)) {

                        $this->_sBaseUri = preg_replace('#^[^/]+#', '', $sHost);
                    }

                    $sHttpErrorPageName = '_getPage'.iError;
                    $this->$sHttpErrorPageName();
                }
            }
        }
    }

    /**
     * load a route
     *
     * @access private
     * @param  \stdClass $oRoute one route
     * @param  string $RequestUri URI
     * @return void
     */
    private function _route(\stdClass $oRoute, string $RequestUri)
    {
        $sCharset = 'UTF-8';

        if (isset($oRoute->route)) {

            $sRoute = str_replace("*", ".*", $oRoute->route);

            $sFinalRoute = preg_replace_callback(
                '|\[/{0,1}:([a-zA-Z_]+)\]|',
                function($aMatches) use ($oRoute) {
                    return "/{0,1}(?P<".$aMatches[1].">".$oRoute->constraints->{$aMatches[1]}.")";
                },
                $sRoute
            );
        }
        else {

            $sFinalRoute = '.*';
        }

        $RequestUri = preg_replace('/^([^?]+)\?.*$/', '$1', $RequestUri);
        $RequestUri = preg_replace('#^'.$this->_sBaseUri.'#', '', $RequestUri);

        if (preg_match('#^'.$sFinalRoute.'$#', $RequestUri, $aMatch)) {

            if (isset($oRoute->location)) {

                $aParamEntries = array();

                foreach ($oRoute->constraints as $sName => $sType) {

                    if (isset($aMatch[$sName])) {

                        $aParamEntries[$sName] = $aMatch[$sName];
                    }
                }

                $oUrlManager = new UrlManager;
                header('Status: 301 Moved Permanently', false, 301);
                header('Location: '.$oUrlManager->getUrl($oRoute->location, $aParamEntries));
                exit;
            }

            $this->_oSecurity = new Security;

            if (!$this->_oSecurity->checkSecurity() !== null) { return 403; }

            // create the $_GET by the URL

            foreach ($aMatch as $mKey => $sResults) {

                if (is_string($mKey)) {

                    $_GET[$mKey] = $sResults;
                }
            }

            if (isset($oRoute->methods) && $oRoute->methods != $_SERVER['REQUEST_METHOD']) { return false; }

            if (isset($oRoute->schemes) && $oRoute->schemes == 'https' && !Request::isHttpsRequest()) { return false; }

            if (isset($oRoute->cache) && isset($oRoute->cache->max_age) && !isset($_GET['flush'])) {

                $oMobileDetect = new \Mobile_Detect;

                if ($oMobileDetect->isMobile()) { $sCacheExt = '.mobi'; }
                else { $sCacheExt = ''; }

                $mCacheReturn = Cache::get($RequestUri.$sCacheExt, $oRoute->cache->max_age);

                if ($mCacheReturn && count($_POST) < 1) {

                    echo $mCacheReturn;
                    return true;
                }
            }

            if (isset($oRoute->cache)) { $this->_checkCache($oRoute->cache); }

            if (isset($oRoute->controller)) {

                define('PORTAL', preg_replace('/^\\\\Venus\\\\src\\\\([a-zA-Z0-9_]+)\\\\.+$/', '$1', $oRoute->controller));
                set_include_path(get_include_path().PATH_SEPARATOR.'src'.PATH_SEPARATOR.PORTAL.PATH_SEPARATOR.'public');

                if (isset($oRoute->content_type)) {

                    if ($oRoute->content_type == 'json') {

                        header('Content-type: application/json; charset='.$sCharset.'');
                    } else if ($oRoute->content_type == 'html') {

                        header('Content-type: text/html; charset='.$sCharset.'');
                    } else if ($oRoute->content_type == 'jpeg') {

                        header('Content-type: image/jpeg');
                    }
                }
                else {

                    header('Content-type: text/html; charset='.$sCharset.'');
                }

                $sControllerName = $oRoute->controller;
                $sActionName = $oRoute->action;

                $oController = new $sControllerName;

                $aEntries = array();

                if (isset($oRoute->constraints) && is_object($oRoute->constraints)) {

                    $mReturn = null;

                    foreach ($oRoute->constraints as $sName => $sType) {

                        if (isset($_GET[$sName]) && $_GET[$sName] != '') {

                            $aEntries[] = $_GET[$sName];
                        } else if (isset($oRoute->defaults_constraints) && is_object($oRoute->defaults_constraints)
                            && isset($oRoute->defaults_constraints->{$sName})) {

                            $aEntries[] = $oRoute->defaults_constraints->{$sName};
                        } else if (isset($_GET[$sName])) {

                            $aEntries[] = $_GET[$sName];
                        } else if (preg_match('/'.$sType.'/', '')) {

                            $aEntries[] = '';
                        } else {

                            $this->_oLogger->warning('Error: Parameter '.$sName.' not exists!');
                            break;
                        }
                    }

                    if ($mReturn === null) {

                        $mReturn = $this->_loadController($oController, $sActionName, $aEntries);

                    }
                }
                else {

                    $mReturn = $this->_loadController($oController, $sActionName, $aEntries);
                }

                if (isset($oRoute->content_type)) {

                    if ($oRoute->content_type === 'json') {

                        $mReturn = json_encode($mReturn, JSON_PRETTY_PRINT);
                    }
                }
            }
            else if (isset($oRoute->template) && isset($oRoute->layout) && $oRoute->layout === true) {

                define('PORTAL', preg_replace('/^\\\\Venus\\\\src\\\\([a-zA-Z0-9_]+)\\\\.+$/', '$1', $oRoute->template));
                set_include_path(get_include_path().PATH_SEPARATOR.'src'.PATH_SEPARATOR.PORTAL.PATH_SEPARATOR.'public');

                $oLayout = Vendor::getVendor('Apollina\Template', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.PORTAL.DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.'Layout.tpl');

                if (isset($oRoute->vars)) {

                    foreach ($oRoute->vars as $sKey => $mValue) {

                        $oLayout->assign($sKey, $mValue);
                    }
                }

                $mReturn = $oLayout->assign('model', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.PORTAL.DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$oRoute->template.'.tpl')
                                   ->fetch();
            }
            else if (isset($oRoute->template)) {

                define('PORTAL', preg_replace('/^\\\\Venus\\\\src\\\\([a-zA-Z0-9_]+)\\\\.+$/', '$1', $oRoute->template));
                set_include_path(get_include_path().PATH_SEPARATOR.'src'.PATH_SEPARATOR.PORTAL.PATH_SEPARATOR.'public');

                $oTemplate = Vendor::getVendor('Apollina\Template', DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.PORTAL.DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$oRoute->template.'.tpl');

                if (isset($oRoute->vars)) {

                    foreach ($oRoute->vars as $sKey => $mValue) {

                        $oTemplate->assign($sKey, $mValue);
                    }
                }

                $mReturn = $oTemplate->fetch();
            }

            // management of return or cache of it

            if (isset($oRoute->cache) && isset($oRoute->cache->max_age) && $mReturn) {

                $oMobileDetect = new \Mobile_Detect;

                if ($oMobileDetect->isMobile()) { $sCacheExt = '.mobi'; }
                else { $sCacheExt = ''; }

                if (defined('COMPRESS_HTML') && COMPRESS_HTML) {

                    $mReturn = str_replace(array("\t", "\r", "  "), array("", "", " "), $mReturn);
                }

                Cache::set($RequestUri.$sCacheExt, $mReturn, $oRoute->cache->max_age);
            }

            if ($mReturn) {

                echo $mReturn;
                return true;
            }
        }
    }

    /**
     * create the constants
     *
     * @access private
     * @return void
     */
    private function _create_constant()
    {
        foreach (Config::get('Const') as $sKey => $mValue) {

            if (is_string($mValue) || is_int($mValue) || is_float($mValue) || is_bool($mValue)) {

                define(strtoupper($sKey), $mValue);
            }
        }
    }

    /**
     * load the controller
     *
     * @access private
     * @param  object $oControllerName controller name
     * @param  string $sActionName method name
     * @param  array $aParams parameters
     * @return mixed
     */
    private function _loadController($oControllerName, string $sActionName, array $aParams = array())
    {
        $aPhpDoc = PhpDoc::getPhpDocOfMethod($oControllerName, $sActionName);

        if (isset($aPhpDoc['Cache'])) {

            if (!isset($aPhpDoc['Cache']['maxage'])) { $aPhpDoc['Cache']['maxage'] = 0; }

            $oMobileDetect = new \Mobile_Detect;

            if ($oMobileDetect->isMobile()) { $sCacheExt = '.mobi'; }
            else { $sCacheExt = ''; }

            $mCacheReturn = Cache::get($sActionName.$sCacheExt, $aPhpDoc['Cache']['maxage']);

            if ($mCacheReturn !== false) { return $mCacheReturn; }
        }

        if (isset($aPhpDoc['Secure'])) {

            if (isset($aPhpDoc['Secure']['roles']) && $this->_oSecurity->getUserRole() != $aPhpDoc['Secure']['roles']) {

                $this->_getPage403();
            }
        }

        $oController = new $oControllerName;

        ob_start();

        if (!defined('PORTAL')) { define('PORTAL', 'Batch'); }

        if (method_exists($oController, 'beforeExecuteRoute')) {

            call_user_func_array(array($oController, 'beforeExecuteRoute'), array());
        }

        $mReturnController = call_user_func_array(array($oController, $sActionName), $aParams);

        if (method_exists($oController, 'afterExecuteRoute')) {

            call_user_func_array(array($oController, 'afterExecuteRoute'), array());
        }

        $mReturn = ob_get_clean();

        if ($mReturn == '') { $mReturn = $mReturnController; }

        if (isset($aPhpDoc['Cache'])) {

            $oMobileDetect = new \Mobile_Detect;

            if ($oMobileDetect->isMobile()) { $sCacheExt = '.mobi'; }
            else { $sCacheExt = ''; }

            if (defined('COMPRESS_HTML') && COMPRESS_HTML) {

                $mReturn = str_replace(array("\t", "\r", "  "), array("", "", "", " "), $mReturn);
            }

            Cache::set($sActionName.$sCacheExt, $mReturn, $aPhpDoc['Cache']['maxage']);
        }

        return $mReturn;
    }

    /**
     * get the page 403
     *
     * @access private
     * @return void
     */
    private function _getPage403()
    {
        header("HTTP/1.0 403 Forbidden");

        if (isset($this->_oRoutes->e403)) {

            $this->_oRoutes->e403->route = '/';
            $_SERVER['REQUEST_URI'] = '/';
            $this->_route($this->_oRoutes->e403, $_SERVER['REQUEST_URI']);
        }

        exit;
    }

    /**
     * get the page 404
     *
     * @access private
     * @return void
     */
    private function _getPage404()
    {
        header("HTTP/1.0 404 Not Found");

        if (isset($this->_oRoutes->e404)) {

            $this->_oRoutes->e404->route = '/';
            $_SERVER['REQUEST_URI'] = '/';
            $this->_route($this->_oRoutes->e404, $_SERVER['REQUEST_URI']);
        }

        exit;
    }

    /**
     * check the cache - just if it's not yet defined
     *
     * @access private
     * @param  \stdClass $oCache object of cache configuration
     * @return void
     */
    private function _checkCache(\stdClass $oCache)
    {
        /**
         * cache-control http
         */

        $sHearderValidity = false;
        $sHeader = "Cache-Control:";

        if (isset($oCache->visibility) && ($oCache->visibility = 'public' || $oCache->visibility = 'private')) {

            $sHearderValidity = true;
            $sHeader .= " ".$oCache->visibility.",";
        }

        if (isset($oCache->max_age)) {

            $sHearderValidity = true;
            $sHeader .= " maxage=".$oCache->max_age.",";
        }

        if (isset($oCache->must_revalidate) && $oCache->must_revalidate === true) {

            $sHearderValidity = true;
            $sHeader .= " must-revalidate,";
        }

        if ($sHearderValidity === true) {

            $sHeader = substr($sHeader, 0, -1);

            if (!headers_sent()) { header($sHeader); }
        }

        /**
         * ETag http
         */

        if (isset($oCache->ETag)) { header("ETag: \"".$oCache->ETag."\""); }

        /**
         * expire
         */

        if (isset($oCache->max_age)) { if (!headers_sent()) { header('Expires: '.gmdate('D, d M Y H:i:s', time() + $oCache->max_age).' GMT'); } }

        /**
         * Last-Modified http
         */

        if (isset($oCache->last_modified)) { if (!headers_sent()) { header('Last-Modified: '.gmdate('D, d M Y H:i:s', time() + $oCache->last_modified).' GMT'); } }

        /**
         * vary http
         */

        if (isset($oCache->vary)) { header('Vary: '.$oCache->vary); }
    }

    /**
     * Sets a logger instance on the object
     *
     * @access private
     * @param  LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger) {

        $this->_oLogger = $logger;
    }
}
