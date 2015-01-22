<?php

/**
 * TableContentFixture
 *
 */
App::uses('TableContent', 'Table.Model');

class TableContentFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TableContent',
		'records' => true,
		'connection' => 'plugin'
	);

}
