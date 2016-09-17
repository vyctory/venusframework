<?php

/**
 * The security of app
 *
 * @category  core
 * @author    Judicaël Paquet <judicael.paquet@rueducommerce.com>
 * @copyright Copyright (c) 2005-2013 RueDuCommerce.com FR Inc. (http://www.rueducommerce.com)
 * @license   http://www.rueducommerce.com Tout droit réservé à Rueducommerce.com
 * @filesource
 * @link      http://www.rueducommerce.com
 * @since     1.0rc1
 */

namespace Venus\core;

use \Venus\core\Config as Config;
use \Venus\lib\Request as Request;

/**
 * This class manage the security
 *
 * @category  core
 * @author    Judicaël Paquet <judicael.paquet@rueducommerce.com>
 * @copyright Copyright (c) 2005-2013 RueDuCommerce.com FR Inc. (http://www.rueducommerce.com)
 * @license   http://www.rueducommerce.com Tout droit réservé à Rueducommerce.com
 * @version   3.0.0
 * @link      http://www.rueducommerce.com
 * @since     1.0rc1
 */

class Security {

	/**
	 * The base Uri to construct the route
	 *
	 * @access private
	 * @var    string
	 */

	private $_sBaseUri = '';

	/**
	 * Actual user
	 *
	 * @access private
	 * @var    string
	 */

	private static $_sLogin = '';

	/**
	 * Actual user
	 *
	 * @access private
	 * @var    string
	 */

	private static $_sPassword = '';

	/**
	 * check security of access
	 *
	 * @access public
	 * @return null|boolean
	 */

	public function checkSecurity() {

		foreach (Config::get('Route') as $sHost => $oHost) {

			if ((!strstr($sHost, '/') && $sHost == $_SERVER['HTTP_HOST'])
				|| (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost))) {

				if (strstr($sHost, '/') && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $sHost)) {

					$this->_sBaseUri = preg_replace('#^[^/]+#', '', $sHost);
				}

				if (isset($oSecurity->firewall)) { $oSecurity = $oHost->firewall; }
			}
		}

		if (isset($oSecurity)) {

			if (isset($oSecurity->authentification) && $oSecurity->authentification === 'http_basic') {

				if (!isset($_SERVER['PHP_AUTH_USER'])) {

					if (!isset($oSecurity->realm)) { $oSecurity->realm = 'Access'; }
					if (!isset($oSecurity->cancelled)) { $oSecurity->cancelled = 'Cancelled'; }

					header('WWW-Authenticate: Basic realm="'.$oSecurity->realm.'"');
					header('HTTP/1.0 401 Unauthorized');
					echo $oSecurity->cancelled;
					exit;
				} else {

					self::$_sLogin = $_SERVER['PHP_AUTH_USER'];
					self::$_sPassword = $_SERVER['PHP_AUTH_PW'];

					if (!$this->_checkPasswordIsGood()) { return false; }
					if (!$this->_checkAccess()) { return false; }
					if (!$this->_checkBlackListIps()) { return false; }
				}
			}
			else if (isset($oSecurity->authentification) && $oSecurity->authentification === 'http_basic_validate_by_controller') {

				if (!isset($_SERVER['PHP_AUTH_USER'])) {

					if (!isset($oSecurity->realm)) { $oSecurity->realm = 'Access'; }
					if (!isset($oSecurity->cancelled)) { $oSecurity->cancelled = 'Cancelled'; }

					header('WWW-Authenticate: Basic realm="'.$oSecurity->realm.'"');
					header('HTTP/1.0 401 Unauthorized');
					echo $oSecurity->cancelled;
					exit;
				} else {

					self::$_sLogin = $_SERVER['PHP_AUTH_USER'];
					self::$_sPassword = $_SERVER['PHP_AUTH_PW'];

					$sControllerName = $oSecurity->controller;
					$sActionName = $oSecurity->action;

					$oController = new $sControllerName;

					if (!$oController->$sActionName(self::$_sLogin, self::$_sPassword)) { return false; }
					if (!$this->_checkAccess()) { return false; }
					if (!$this->_checkBlackListIps()) { return false; }
				}
			} else if (isset($oSecurity->authentification) && $oSecurity->authentification === 'controller') {

				// it's an action of one controller that it return true or false for the authentification

				$sControllerName = $oSecurity->controller;
				$sActionName = $oSecurity->action;

				$oController = new $sControllerName;

				if (!$oController->$sActionName) { return false; }
				if (!$this->_checkAccess()) { return false; }
				if (!$this->_checkBlackListIps()) { return false; }
			}

			if (isset($oSecurity->ips) && !in_array($_SERVER['REMOTE_ADDR'], $oSecurity->ips)) { return false; }

			if (isset($oSecurity->requires_channel) && $oSecurity->requires_channel == 'https' && !Request::isHttpsRequest()) {

				return false;
			} else if (isset($oSecurity->requires_channel) && $oSecurity->requires_channel == 'http' && ((Request::isHttpRequest()
				&& Request::isHttpsRequest()) || !Request::isHttpRequest())) {

				return false;
			}
		}

		return true;
	}

	/**
	 * check access
	 *
	 * @access private
	 * @return bool
	 */

	private function _checkAccess() : bool {

		$oSecurity = Config::get('Security');

		if (isset($oSecurity->access)) {

			foreach ($oSecurity->access as $sPathAccess => $aParams) {

				if (preg_match('#'.$sPathAccess.'#', str_replace($this->_sBaseUri, '', $_SERVER['REQUEST_URI']))) {

					if (in_array($this->getUserRole(), $aParams->roles)) { return true; } else { return false; }
				}
			}
		}

		return true;
	}

	/**
	 * check if the ips is not in the blacklist
	 *
	 * @access private
	 * @return bool
	 */

	private function _checkBlackListIps() : bool {

		$oSecurity = Config::get('Security');

		if (isset($oSecurity->blacklist_ips)) {

			foreach ($oSecurity->blacklist_ips as $sIp) {

				if ($_SERVER['REMOTE_ADDR'] == $sIp) { return false; }
			}
		}

		return true;
	}

	/**
	 * check if the password is good
	 *
	 * @access private
	 * @return bool
	 */

	private function _checkPasswordIsGood() : bool {

		$sLogin = self::$_sLogin;
		$sPassword = Config::get('Security')->users->$sLogin->password;

		if ($sPassword == self::$_sPassword) { return true; }
		else if ($sPassword == md5(self::$_sPassword)) { return true; }
		else { return false; }
	}

	/**
	 * get the user roles
	 *
	 * @access public
	 * @return string
	 */

	public function getUserRole() : string {

		if (self::$_sLogin) {

			$sLogin = self::$_sLogin;
			return Config::get('Security')->users->$sLogin->roles;
		} else {

			return '';
		}
	}


	/**
	 * get the user roles
	 *
	 * @access public
	 * @param  string $sRole role to test
	 * @return bool
	 */

	public function isGranted(string $sRole) : bool {

		if ($sRole == $this->getUserRole() || $this->getUserRole() == '') { return true; } else { return false; }
	}

	/**
	 * set base uri
	 *
	 * @access public
	 * @param  string $sBaseUri
	 * @return \Venus\core\Security
	 */

	public function setBaseUri($sBaseUri) {

		$this->_sBaseUri = $sBaseUri;
	}
}
