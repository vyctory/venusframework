<?php

/**
 * Controller of the plugin Demo
 *
 * @category  	Venus\src
 * @package   	Venus\src\plugins\Demo\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2015 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/uranium/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/uranium
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\src\plugins\Demo\Controller;

use \Venus\src\plugins\common\Controller as Controller;

/**
 * Controller to test
 *
 * @category    Venus\src
 * @package     Venus\src\plugins\Demo\Controller
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2015 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/uranium/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/uranium
 * @link        https://github.com/las93
 * @since       1.0
 */
class Demo extends Controller
{
    /**
     * Constructor
     *
     * @access public
     * @return Demo
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * bootstrap
     *
     * @access public
     * @return void
     */
    public function bootstrap()
    {
        ;
    }

    /**
     * install method
     *
     * @access public
     * @param  string $sPortal
     * @return void
     */
    public function install($sPortal)
    {
        $this->installDb;
    }
}
