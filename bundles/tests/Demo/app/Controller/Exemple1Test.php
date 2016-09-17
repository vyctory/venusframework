<?php

/**
 * Controller to test
 *
 * @category  	tests
 * @package   	tests\Demo\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2016 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus3/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus3
 * @link      	https://github.com/las93
 * @since     	1.0
 */

namespace Venus\tests\Demo\Controller;

use Venus\src\Demo\Controller\Exemple1;

/**
 * Controller to test
 *
 * @category  	tests
 * @package   	tests\Demo\Controller
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2016 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus3/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus3
 * @link      	https://github.com/las93
 * @since     	1.0
 */

class Exemple1Test extends \PHPUnit_Framework_TestCase
{
	/**
	 * the main page
	 *
	 * @access public
	 * @return void
     * @test
	 */

	public function testShow()
    {
	    try {

            ob_start();
            $exemple1 = new Exemple1;
            $exemple1->show();
            $content = ob_get_clean();

            //var_dump(debug_backtrace());

            if ($content) {

                $this->assertTrue(true);
            }
            else {
                $this->assertTrue(false);
            }
        }
        catch(\Exception $e) {
            $this->assertTrue(false);
        }
	}
}