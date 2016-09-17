<?php

namespace Venus\src\Batch\Controller;

use \Venus\src\Batch\common\Controller as Controller;
use \Venus\core\Config;

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

        foreach ($BatchConf as $key => $batchContent) {

            echo '-----------------------------------------------------------------------------------------------------'."\n";
            echo '|'.$key.' => '.$batchContent->description."\n|\n";

            foreach ($batchContent->options as $keyOption => $optionContent) {

                echo '|   '.$keyOption.' : '.$optionContent->description."\n";
            }
            echo '-----------------------------------------------------------------------------------------------------'."\n";
        }
    }
}
