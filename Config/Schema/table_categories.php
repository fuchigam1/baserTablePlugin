<?php

/* TableCategories schema generated on: 2010-11-04 18:11:11 : 1288863011 */

class TableCategoriesSchema extends CakeSchema {

	public $name = 'TableCategories';

	public $file = 'table_categories.php';

	public $connection = 'plugin';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $table_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'key' => 'primary'),
		'table_content_id' => array('type' => 'integer', 'null' => false, 'length' => 8),
		'no' => array('type' => 'integer', 'null' => false, 'length' => 8),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'owner_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
