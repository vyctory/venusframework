<?php

namespace Venus\src\Batch\Controller;

use \Venus\src\Batch\common\Controller as Controller;
use \Venus\core\Config;
use \Venus\lib\Bash;

class Help extends Controller
{
    /**
     * Constructs a test case with the given name.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * new method to launch a web server
     * @param array $options
     * @tutorial php bin/console server:run
     *           php bin/console server:run -a 192.168.0.1:8000
     */
    public function load(array $options = array())
    {
        $BatchConf = Config::get('Route')->batch->script;

        echo "\n\n";
        echo Bash::setBackground("                                                                            ", 'red');
        echo Bash::setBackground("          [ERROR] Do you search a batch ?                                   ", 'red');
        echo Bash::setBackground("                                                                            ", 'red');
        echo "\n\n";

        foreach ($BatchConf as $key => $batchContent) {

            echo Bash::setBackground("$key", 'red');
            echo '=> '.$batchContent->description."\n\n";

            foreach ($batchContent->options as $keyOption => $optionContent) {

                echo '   '.$keyOption.' : '.$optionContent->description."\n";
            }
            echo "\n";
        }
    }

    /**
     * new method to launch a web server
     * @param array $options
     * @tutorial php bin/console server:run
     *           php bin/console server:run -a 192.168.0.1:8000
     */
    public function search(array $options = array())
    {
        $BatchConf = Config::get('Route')->batch->script;

        echo "\n\n";
        echo Bash::setBackground("                                                                            ", 'red');
        echo Bash::setBackground("          [ERROR] Do you search a batch ?                                   ", 'red');
        echo Bash::setBackground("                                                                            ", 'red');
        echo "\n\n";

        foreach ($BatchConf as $key => $batchContent) {

            if (strstr($key, $options['s'])) {
                echo Bash::setBackground("$key", 'red');
                echo '=> '.$batchContent->description."\n\n";

                foreach ($batchContent->options as $keyOption => $optionContent) {

                    echo '   '.$keyOption.' : '.$optionContent->description."\n";
                }

                echo "\n";
            }
        }
    }
}
