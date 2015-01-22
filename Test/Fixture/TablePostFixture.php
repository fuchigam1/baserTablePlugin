<?php

/**
 * TablePostFixture
 *
 */
App::uses('TablePost', 'Table.Model');

class TablePostFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TablePost',
		'records' => true,
		'connection' => 'plugin'
	);

}
