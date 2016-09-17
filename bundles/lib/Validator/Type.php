<?php

/**
 * Type validator
 *
 * @category    Venus
 * @package     Venus\lib\Validator
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/venus2
 * @link        https://github.com/las93
 * @since       1.0
 */
namespace Venus\lib\Validator;

/**
 * Type validator
 *
 * @category    Venus
 * @package     Venus\lib\Validator
 * @author      Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright   Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license     https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version     Release: 1.0.0
 * @filesource  https://github.com/las93/venus2
 * @link        https://github.com/las93
 * @since       1.0
 */
class Type extends Common
{
    /**
     * type
     * @var type
     */
    private $_sType = '';
    
    /**
     * constructor
     *
     * @access public
     * @param  string $sType type
     * @return Type
     */
    public function __construct(string $sType)
    {
        $this->_sType = $sType;
    }
    
    /**
     * validate the Type
     *
     * @access public
     * @param  string $sValue
     * @return bool
     */
    public function validate(string $sValue = null) : bool
    {
        if ($this->_sType == 'DateTime') {
            
            if (preg_match('#^[0-9]{4}/[0-9]{2}/[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}#', $sValue)) {
                
                return true;
            }
        }
        
        return false;
    }
}
