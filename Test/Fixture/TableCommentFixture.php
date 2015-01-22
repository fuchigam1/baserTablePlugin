<?php

/**
 * TableCommentFixture
 *
 */
App::uses('TableComment', 'Table.Model');

class TableCommentFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array(
		'model' => 'table.TableComment',
		'records' => true,
		'connection' => 'plugin'
	);

}
