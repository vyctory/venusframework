<?php
/**
 * Created by PhpStorm.
 * User: las
 * Date: 28/06/2016
 * Time: 22:22
 */
namespace Venus\lib\Request;

class Cookies implements RequestInterface
{
    /**
     * get parameter
     * @param string $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = null) : string
    {
        if (isset($_COOKIE[$name]) && $_COOKIE[$name] != '') {
            return $_COOKIE[$name];
        } else if ($default !== null) {
            return $default;
        }
    }
}
