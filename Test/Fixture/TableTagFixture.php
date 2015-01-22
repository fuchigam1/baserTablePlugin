<?php

/**
 * TableTagFixture
 *
 */
App::uses('TableTag', 'Table.Model');

class TableTagFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TableTag',
		'records' => true,
		'connection' => 'plugin'
	);

}
