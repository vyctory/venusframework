<?php

/**
 * Ldap
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0.2
 */
namespace Venus\lib;

use \Venus\core\Config as Config;

/**
 * Ldap library
 *
 * @category  	core
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0.2
 */
class Ldap
{
    /**
     * connection at ldap server
     * 
     * @access private
     * @var    resource
     */
	private $_rConnect;
	
	/**
	 * the databse to connect
	 * 
	 * @access private
	 * @var    string
	 */
	private $_sBase;
	
	/**
	 * if the user is connected or not
	 * 
	 * @access private
	 * @var    bool
	 */
	private $_bConnected = false;

	/**
	 * constructor of class
	 * 
	 * @access public
	 * @return \Venus\lib\Ldap
	 */
	public function __construct()
	{
	    $oDbConf = Config::get('Ldap')->configuration;

		$this->_sBase = $oDbConf->base;

		$this->_rConnect = ldap_connect($oDbConf->host, $oDbConf->port);

		$this->set_option(LDAP_OPT_REFERRALS, 0);
	}

	/**
	 * Get groups
	 *
	 * @access public
	 * @return array
	 */
    public function getGroups() : array
    {
        $rSearch = ldap_search( $this->_rConnect , $this->_sBase , "objectclass=group" , array("cn") );
        $aEntries = ldap_get_entries($this->_rConnect, $rSearch);
        $aGroups = array();

        for ( $i = 0 ; $i < $aEntries["count"] ; $i++ ) {
            
            $aGroups[] = utf8_encode($aEntries[$i]["dn"]);
        }

        return $aGroups;
    }

    /**
     * Authentification in Ldap
     * 
     * @access public
     * @param  string $sUser
     * @param  string $sPassword
     * @return \Venus\lib\Ldap
     */
	public function bind($sUser, $sPassword) : Ldap
	{
		return $this->_bConnected = ldap_bind($this->_rConnect, $sUser, $sPassword);
		return $this;
	}

	/**
	 * Close authentification in Ldap
	 *
	 * @access public
	 * @return bool
	 */
	public function unbind() : bool
	{
	    if ($this->_bConnected) { return $this->_bConnected = ldap_unbind($this->_rConnect); }
	    else { return true; }
	}

	/**
	 * destructor of the class
	 *
	 * @access public
	 * @return void
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * Call a classic ldap method. You have to ignore the ldap_ part
	 * You put all parameters without the connector
	 *
	 * @access public
	 * @param  string $sFunctionName
	 * @param  array $aArgv
	 * @return void
	 */
	public function __call(string $sFunctionName, array $aArgv)
	{
		array_unshift($argv, $this->_rConnect);
		return call_user_func_array('ldap_'.$sFunctionName, $aArgv);
	}

	/**
	 * get in Ldap
	 *
	 * @access public
	 * @param  string $sFilter
	 * @param  array $aAttributes
	 * @return array
	 */
	public function get(string $sFilter, array $aAttributes)
	{
		$res = $this->search($sFilter, $aAttributes);

		return $this->getEntries($res, $aAttributes);
	}

	/**
	 * search in Ldap
	 *
	 * @access public
	 * @param  string $sFilter
	 * @param  array $aAttributes
	 * @return resource
	 */
	public function search(string $sFilter, array $aAttributes)
	{
		return ldap_search($this->_rConnect, $this->_sBase, $sFilter, $aAttributes);
	}

	/**
	 * get in Ldap
	 *
	 * @access public
	 * @param  resource $rResultIdentifier
	 * @param  array $aAttributes
	 * @return array
	 */
	public function getEntries($rResultIdentifier, array $aAttributes) : array
	{
		$aEntries = ldap_get_entries($this->_rConnect, $rResultIdentifier);

		$aMask = array_flip($aAttributes);

		$aResultSet = array();

		for ($i = 0, $count = $aEntries['count']; $i < $count; ++$i) {
		    
			$aResultSet[$i] = array_intersect_key($aEntries[$i], $aMask);

			foreach($aResultSet[$i] as &$aValues) {
			    
				unset($aValues['count']);
			}
		}

		return $aResultSet;
	}
}
