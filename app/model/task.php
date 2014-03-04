<?php

namespace Model;

class Task extends \DB\SQL\Mapper {

	function __construct() {

		$db = \Registry::get('DB');

		parent::__construct($db, 'tasks');

	}
} 