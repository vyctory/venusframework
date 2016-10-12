<?php

/**
 * bootstrap of the demo
 *
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/vyctory)
 * @license   	https://github.com/vyctory/venusframework/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 3.0.0
 * @filesource	https://github.com/vyctory/venusframework
 * @link      	https://github.com/vyctory
 * @since     	3.0
 * @tutorial    https://vyctory.github.io/venusframework/index.html
 */
declare(strict_types = 1);

set_include_path(get_include_path().PATH_SEPARATOR.str_replace('public', 'bundles', __DIR__).PATH_SEPARATOR.str_replace('public', 'kernel', __DIR__));

require 'AutoLoad.php';

$oRouter = new \Venus\core\Router();
$oRouter->run();
