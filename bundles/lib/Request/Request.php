<?php
/**
 * Created by PhpStorm.
 * User: las
 * Date: 28/06/2016
 * Time: 22:22
 */
namespace Venus\lib\Request;

class Request implements RequestInterface
{
    /**
     * get parameter
     * @param string $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = null) : string
    {
        if (isset($_POST[$name]) && $_POST[$name] != '') {
            return $_POST[$name];
        } else if ($default !== null) {
            return $default;
        }
    }
}
