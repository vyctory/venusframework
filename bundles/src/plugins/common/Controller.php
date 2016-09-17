<?php

/**
 * Controller Manager
 *
 * @category  	src\BackOffice
 * @package   	src\BackOffice\common
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/uranium/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\src\plugins\common;

use \Venus\core\Config                  as Config;
use \Venus\core\Controller              as CoreController;
use \Venus\src\Batch\Controller\Entity  as Entity;

/**
 * Controller Manager
 *
 * @category  	src\BackOffice
 * @package   	src\BackOffice\common
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/uranium/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
abstract class Controller extends CoreController
{
	/**
	 * Constructor
	 *
	 * @access public
	 * @return object
	 */
	public function __construct() 
	{
		parent::__construct();
		
		$this->installDb = function()
		{
		    $oDb = Config::get('Db');
		    $oTables = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.preg_replace('/^.*\\\\([a-zA-Z0-9]+)$/', '$1', get_called_class()).DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'Db.conf'));
		    
		    $oDb->configuration->tables = $oTables;
		    
    	    $aOptions = [
	           "p" => CREATE_PORTAL,
	           "c" => true,
	           "e" => true,
	           "b" => json_encode($oDb)
    	    ];
    	    
    	    $oEntity = new Entity;
    	    $oEntity->runScaffolding($aOptions);
		};
	}
}
