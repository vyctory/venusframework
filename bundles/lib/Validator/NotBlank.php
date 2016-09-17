<?php

/**
 * Not Blank validator
 *
 * @category  	Venus
 * @package		Venus\lib\Validator
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
namespace Venus\lib\Validator;

/**
 * Not Blank validator
 *
 * @category  	Venus
 * @package		Venus\lib\Validator
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */
class NotBlank extends Common
{
	/**
	 * validate the NotBlank
	 *
	 * @access public
	 * @param  string $sValue
	 * @return bool
	 */
	public function validate(string $sValue = null) : bool
	{
		if (trim($sValue) != '') { return true; }
		else { return false; }
	}
}
