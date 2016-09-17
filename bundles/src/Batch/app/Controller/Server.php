<?php

namespace Venus\src\Batch\Controller;

use \Venus\lib\Bash as Bash;
use \Venus\src\Batch\common\Controller as Controller;

class Server extends Controller
{
    /**
     * new method to launch a web server
     * @param array $options
     * @tutorial php bin/console server:run
     *           php bin/console server:run -a 192.168.0.1:8000
     */
    public function run(array $options = array())
    {
        ob_get_clean();

        if (!isset($options['a'])) {
            $options['a'] = 'localhost:8000';
        }

        echo "\n\n";
        echo Bash::setBackground("                                                                            ", 'green');
        echo Bash::setBackground("          [OK] Start web server                                             ", 'green');
        echo Bash::setBackground("                                                                            ", 'green');
        echo "\n\n";
        echo "        > Use ".$options['a']." in browser";
        echo "\n\n";
        echo "        > Clic Ctrl+C to stop the web server";
        echo "\n\n";

        exec('php -S '.$options['a'].' /public/index.php');
    }

    /**
     * check if a server web is launched
     * @param array $options
     * @tutorial php bin/console server:status
     *           php bin/console server:status -a 192.168.0.1:8000
     */
    public function status(array $options = array())
    {
        ob_get_clean();

        if (!isset($options['a'])) {
            $options['a'] = 'localhost:8000';
        }

        list($hostname, $port) = explode(':', $options['a']);

        if (false !== $fp = @fsockopen($hostname, $port, $errno, $errstr, 1)) {
            fclose($fp);

            echo "\n\n";
            echo Bash::setBackground("                                                                            ", 'green');
            echo Bash::setBackground("          [OK] A web server is launched                                     ", 'green');
            echo Bash::setBackground("                                                                            ", 'green');
            echo "\n\n";
            echo "        > Check realized on ".$options['a'];
            echo "\n\n";
        }
        else {
            echo "\n\n";
            echo Bash::setBackground("                                                                            ", 'red');
            echo Bash::setBackground("          [WARNING] A web server is not used                                ", 'red');
            echo Bash::setBackground("                                                                            ", 'red');
            echo "\n\n";
            echo "        > Check realized on ".$options['a'];
            echo "\n\n";
        }
    }
}
