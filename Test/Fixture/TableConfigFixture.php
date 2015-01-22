<?php

/**
 * TableConfigFixture
 *
 */
App::uses('TableConfig', 'Table.Model');

class TableConfigFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TableConfig',
		'records' => true,
		'connection' => 'plugin'
	);

}
