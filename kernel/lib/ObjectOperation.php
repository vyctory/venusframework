<?php

/**
 * Manipulate objects
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 */
namespace Venus\lib;

/**
 * This class manage object
 *
 * @category  	lib
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 */
class ObjectOperation
{
	/**
	 * create an array with an object
	 *
	 * @access public
	 * @param $mObject
	 * @return array
	 */
	public static function objectToArray($mObject) : array
	{
		if ( is_object($mObject)) {
			
		    $mObject = (array) $mObject;
		}


		if (is_array($mObject)) {

			$aNew = array();

			foreach($mObject as $sKey => $mValues) {

				$sKey = preg_replace("/^\\0(.*)\\0/", "", $sKey);
				$aNew[$sKey] = self::objectToArray($mValues);
			}
		}
		else {

			$aNew = $mObject;
		}

		return $aNew;
	}
}
