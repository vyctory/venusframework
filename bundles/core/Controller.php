<?php

/**
 * Controller Manager
 *
 * @category  	core
 * @package   	core\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 */
namespace Venus\core;

use \Venus\core\Router as Router;
use \Venus\core\Security as Security;
use \Venus\core\UrlManager as UrlManager;
use \Venus\lib\I18n as I18n;
use \Venus\lib\Vendor as Vendor;
use \Venus\lib\Form as Form;
use \Venus\lib\Mail as Mail;
use \Venus\lib\Session as Session;
use \Venus\lib\Cookie as Cookie;
use \Venus\lib\Di as Di;
use \Venus\lib\Request as Request;

/**
 * Controller Manager
 *
 * @category  	core
 * @package   	core\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2016 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus3/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/las93/venus3
 * @link      	https://github.com/las93
 * @since     	3.0.0
 */
abstract class Controller extends Mother 
{

    /**
     * Cache to know if a model was initialize or not because we must initialize it just one time by script
     * 
     * @access private
     * @var    array
     */
    private static $_aInitialize = array();
    
    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {
        $aClass = explode('\\', get_called_class());
        $sClassName = $aClass[count($aClass) - 1];
        $sNamespaceName = preg_replace('/\\\\'.$sClassName.'$/', '', get_called_class());

        if (isset($sClassName)) {

            $sNamespaceBaseName = str_replace('\Controller', '', $sNamespaceName);
            $sDefaultModel = $sNamespaceBaseName.'\Model\\'.$sClassName;
            $sDefaultView = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('Venus\\', '\\', $sNamespaceBaseName)).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.$sClassName.'.tpl';
            $sDefaultLayout = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('Venus\\', '\\', $sNamespaceBaseName)).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'View'.DIRECTORY_SEPARATOR.'Layout.tpl';

            $this->model = function() use ($sDefaultModel) { return new $sDefaultModel; };

            $this->view = function() use ($sDefaultView) { return Vendor::getVendor('Apollina\Template', $sDefaultView); };

            $this->layout = function() use ($sDefaultLayout) { return Vendor::getVendor('Apollina\Template', $sDefaultLayout, true); };

            $this->layout->assign('model', $sDefaultView);
        }

        $this->form = function() { return new Form(); };
        $this->security = function() { return new Security(); };
        $this->router = function() { return new Router; };
        $this->mail = function() { return new Mail; };
        $this->session = function() { return new Session; };
        $this->translator = function() { return new I18n; };
        $this->url = function() { return new UrlManager; };
        $this->cookie = function() { return new Cookie; };
        $this->di = function() { return new Di; };
        $this->request = function() { return new Request; };

        /**
         * Trigger on a model to initialize it. You could fill entity with it.
         */
        if (method_exists(get_called_class(), 'initialize')) {

            if (!isset(self::$_aInitialize[get_called_class()])) {

                static::initialize();
                self::$_aInitialize[get_called_class()] = true;
            }
        }

        /**
         * Trigger on a model to initialize it every time you construct it
         */
        if (method_exists(get_called_class(), 'onConstruct')) { static::onConstruct(); }
    }

    /**
     * redirection HTTP
     *
     * @access public
     * @param  string $sUrl url for the redirection
     * @param int $iHttpCode code of the http request
     * @return void
     */
    public function redirect(string $sUrl, int $iHttpCode = 301)
    {
        if ($iHttpCode === 301) { header('Status: 301 Moved Permanently', false, 301); }
        else if ($iHttpCode === 302) { header('Status: Moved Temporarily', false, 301); }

        header('Location: '.$sUrl);
        exit;
    }

    /**
     * call an other action as you call action with URL/Cli
     *
     * @access public
     * @param  string $sUri uri for the redirection
     * @param  array $aParams parameters
     * @return void
     */
    public function forward(string $sUri, array $aParams = array())
    {
        $this->router->runByFoward($sUri, $aParams);
    }

    /**
     * call the 404 Not Found page
     *
     * @access public
     * @return void
     */
    public function notFound()
    {
        $$this->router->runHttpErrorPage(404);
    }

    /**
     * call the 403 Forbidden page
     *
     * @access public
     * @return void
     */
    public function forbidden()
    {
        $$this->router->runHttpErrorPage(403);
    }

    /**
     * get a property
     *
     * @access public
     * @param  unknown_type $mKey
     * @return void
     */
    public function __get($mKey)
    {
        if (isset($this->di) && property_exists($this, 'di')) {

            $mDi = $this->di->get($mKey);

            if (isset($mDi) && $mDi !== false) { return $mDi; }
        }

        return parent::__get($mKey);
    }
}
