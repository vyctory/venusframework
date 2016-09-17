<?php

/**
 * Batch that install plugins
 *
 * @category  	src
 * @package   	src\Batch\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	http://www.iscreenway.com/framework/licence.php Tout droit réservé à http://www.iscreenway.com
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 *
 * @tutorial    You could launch this Batch in /bundles/
 * 				php bin/console scaffolding -p [portal]
 * 				-p [portal] => it's the name where you want add your entities and models
 * 				-r [rewrite] => if we force rewrite file
 * 					by default, it's Batch
 */
namespace Venus\src\Batch\Controller;

use \Venus\src\Batch\common\Controller as Controller;

/**
 * Batch that install plugins
 *
 * @category  	src
 * @package   	src\Batch\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	http://www.iscreenway.com/framework/licence.php Tout droit réservé à http://www.iscreenway.com
 * @version   	Release: 2.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	2.0.0
 */
class Plugin extends Controller
{
	/**
	 * run the batch to install plugin
	 * @tutorial bin/console plugin
	 *
	 * @access public
	 * @return void
	 */
	public function install(array $aOptions = array())
	{
	    $sFile = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'Plugins.conf');
	    $aPlugins = json_decode($sFile);
	    
	    if (isset($aOptions['p'])) {
	        
	        define('CREATE_PORTAL', $aOptions['p']);
	    }
	    
	    foreach ($aPlugins->list as $sPluginName) {
	        
	        $sClassName = 'Venus\src\plugins\\'.$sPluginName.'\Controller\\'.$sPluginName;
	        $oPlugin = new $sClassName;
	        $oPlugin->install($aOptions['p']);
	    }
	}

	/**
	 * run the batch to create plugin
	 * @tutorial bin/console plugin
	 *
	 * @access public
	 * @param array $aOptions
	 * @return void
	 */
	public function create(array $aOptions = array())
	{
	    $sDefaultFolder = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$aOptions['n'];
	    mkdir($sDefaultFolder, 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'conf', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'Controller', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'data', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'i18n', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'meta', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'public', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'css', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'img', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'js', 0777, true);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'View', 0777, true);
	    
	    file_put_contents($sDefaultFolder.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'Db.conf', '{}');
	    file_put_contents($sDefaultFolder.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.'Plugin.conf', '{}');
	    
	    $sContent = "<?php

/**
 * Controller of the plugin ".$aOptions['n']."
 *
 * @category  	Venus\src
 * @package   	Venus\src\plugins\\".$aOptions['n']."\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2015 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\src\plugins\\".$aOptions['n']."\Controller;

use \Venus\src\plugins\common\Controller as Controller;

/**
 * Controller to test
 *
 * @category    Venus\src
 * @package     Venus\src\plugins\\".$aOptions['n']."\Controller
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2015 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/venus2
 * @link        https://github.com/las93
 * @since       1.0
 */
class ".$aOptions['n']." extends Controller
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
     * @return void
     */
    public function install()
    {
        $this->installDb;
    }
}";
	    
	    file_put_contents($sDefaultFolder.DIRECTORY_SEPARATOR.'Controller'.DIRECTORY_SEPARATOR.$aOptions['n'].'.php', $sContent);
	    mkdir($sDefaultFolder.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.'en_US'.DIRECTORY_SEPARATOR.'LC_MESSAGES', 0777, true);
	    file_put_contents($sDefaultFolder.DIRECTORY_SEPARATOR.'meta'.DIRECTORY_SEPARATOR.'LICENSE.md', '');
	    file_put_contents($sDefaultFolder.DIRECTORY_SEPARATOR.'meta'.DIRECTORY_SEPARATOR.'README.md', '');
	}
}
