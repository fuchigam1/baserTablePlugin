<?php

/**
 * TableCategoryFixture
 *
 */
App::uses('TableCategory', 'Table.Model');

class TableCategoryFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TableCategory',
		'records' => true,
		'connection' => 'plugin'
	);

}
