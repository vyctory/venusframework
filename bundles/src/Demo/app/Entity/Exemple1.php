<?php

/**
 * Model Exemple1
 *
 * @category  	Venus\src\
 * @package   	Venus\src\Demo\Entity
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */

namespace Venus\src\Demo\Entity;

use \Venus\src\Demo\common\Entity as Entity;

/**
 * Model Exemple1
 *
 * @category  	Venus\src\
 * @package   	Venus\src\Demo\Entity
 * @author    	Judicaël Paquet <judicael.paquet@gmail.com>
 * @copyright 	Copyright (c) 2013-2014 PAQUET Judicaël FR Inc. (https://github.com/las93)
 * @license   	https://github.com/las93/venus2/blob/master/LICENSE.md Tout droit réservé à PAQUET Judicaël
 * @version   	Release: 1.0.0
 * @filesource	https://github.com/las93/venus2
 * @link      	https://github.com/las93
 * @since     	1.0
 */

class Exemple1 extends Entity {

	/**
	 * id
	 *
	 * @access private
	 * @var    int
	 *
	 * @primary_key
	 */

	private $id = null;



	/**
	 * title
	 *
	 * @access private
	 * @var    string
	 *
	 */

	private $title = null;

	/**
	 * get id of article
	 *
	 * @access public
	 * @return int
	 */

	public function get_id() {

		return $this->id;
	}

	/**
	 * set id of article
	 *
	 * @access public
	 * @param  int $id id of article
	 * @return \src\FrontOffice\Entity\article
	 */

	public function set_id($id) {

		$this->id = $id;
		return $this;
	}

	/**
	 * get title of article
	 *
	 * @access public
	 * @return string
	 */

	public function get_title() {

		return $this->title;
	}

	/**
	 * set title of article
	 *
	 * @access public
	 * @param  string $title title of article
	 * @return \src\FrontOffice\Entity\article
	 */

	public function set_title($title) {

		$this->title = $title;
		return $this;
	}
}
