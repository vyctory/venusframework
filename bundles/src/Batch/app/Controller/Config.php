<?php

/**
 *  Batch to change configuration
 *
 * @category  	src
 * @package   	src\Batch\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 *
 * @tutorial    You could launch this Batch in /private/
 * 				php bin/console create_project -p [portal]
 * 				-p [portal] => it's the name where you want add your entities and models
 * 					by default, it's Batch
 */
namespace Venus\src\Batch\Controller;

use \Venus\src\Batch\common\Controller as Controller;

/**
 * Batch to change configuration
 *
 * @category  	src
 * @package   	src\Batch\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 */
class Config extends Controller
{
	/**
	 * run the batch to create a project in this framework
	 * @tutorial bin/console create_project
	 *
	 * @access public
	 * @param array $aOptions
	 * @throws \Exception
	 */
	public function maintenance(array $aOptions = array())
	{
		/**
		 * option -down => indiquer le site en maintenance
		 *        -up   => indiquer le site disponible
		 */
		if (!isset($aOptions['down']) && !isset($aOptions['up'])) {

			echo 'You must indicate a parameter up or down with your command line.';
			throw new \Exception('You must indicate a parameter up or down with your command line.');
		}

        
	}
}
