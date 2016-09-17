<?php

/**
 * Manage php doc
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib;

/**
 * This class manage the php doc
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class PhpDoc
{
	/**
	 * add the namespace
	 *
	 * @access public
	 * @param mixed $sClassName class name
	 * @param string $sMethodName method name
	 * @return array
	 */
	public static function getPhpDocOfMethod($sClassName, string $sMethodName) : array
	{
		$oReflectionClass  = new \ReflectionClass($sClassName);
		$oReflectionMethod = $oReflectionClass->getMethod($sMethodName);
		$sCommentPhpDoc = $oReflectionMethod->getDocComment();

		/**
		 * parse this PhpDoc @cache(param=1,param=2)
		 */

		preg_match_all('/@([a-zA-Z0-9_-]+)[ \t]*\(([^\)]+)\)/', $sCommentPhpDoc, $aMatchs, PREG_SET_ORDER);
		$aParams = array();

		foreach ($aMatchs as $aOneMatch) {

			$aParams[$aOneMatch[1]] = array();

			foreach (explode(',', $aOneMatch[2]) as $sValue) {

				$aFinalExplode = explode('=', $sValue);

				if (preg_match('/^".+"$/', $aFinalExplode[1]) || preg_match("/^'.+'$/", $aFinalExplode[1])) {

					$aFinalExplode[1] = substr($aFinalExplode[1], 1);
					$aFinalExplode[1] = substr($aFinalExplode[1], 0, -1);
				}

				$aParams[$aOneMatch[1]][$aFinalExplode[0]] = $aFinalExplode[1];
			}
		}

		/**
		 * parse this PhpDoc @param  string $title ...
		 */

		preg_match_all('/@([a-zA-Z0-9_-]+)[ \t]*([^\r\n]+)/', $sCommentPhpDoc, $aMatchs, PREG_SET_ORDER);
		$aParams = array();

		foreach ($aMatchs as $aOneMatch) {

			if ($aOneMatch[1] == 'param') {

				if (!isset($aParams['param'])) { $aParams['param'] = array(); }

				$iIndex = count($aParams['param']);

				if (!isset($aParams['param'][$iIndex])) { $aParams['param'][$iIndex] = array(); }

				foreach (explode(' ', $aOneMatch[2]) as $sValue) {

					$aParams['param'][$iIndex][] = $sValue;
				}
			}
			else {

				$aParams[$aOneMatch[1]] = array();

				foreach (explode(' ', $aOneMatch[2]) as $sValue) {

					$aParams[$aOneMatch[1]][] = $sValue;
				}
			}
		}

		return $aParams;
	}
}
