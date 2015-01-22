<?php

/* TableConfigs schema generated on: 2010-11-04 18:11:11 : 1288863011 */

class TableConfigsSchema extends CakeSchema {

	public $name = 'TableConfigs';

	public $file = 'table_configs.php';

	public $connection = 'plugin';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $table_configs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 2, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
