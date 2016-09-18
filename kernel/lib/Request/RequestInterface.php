<?php
/**
 * Created by PhpStorm.
 * User: las
 * Date: 29/06/2016
 * Time: 19:41
 */
namespace Venus\lib\Request;

interface RequestInterface
{
    /**
     * get parameter
     * @param string $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = null) : string;
}
