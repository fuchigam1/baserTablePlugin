<?php

/**
 * テーブルタグモデル
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2014, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Table.Model
 * @since			baserCMS v 0.1.0
 * @license			http://basercms.net/license/index.html
 */
/**
 * Include files
 */
App::uses('TableAppModel', 'Table.Model');

/**
 * テーブルタグモデル
 *
 * @package Table.Model
 */
class TableTag extends TableAppModel {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableTag';

/**
 * ビヘイビア
 *
 * @var array
 * @access public
 */
	public $actsAs = array('BcCache');

/**
 * HABTM
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'TablePost' => array(
			'className' => 'Table.TablePost',
			'joinTable' => 'table_posts_table_tags',
			'foreignKey' => 'table_tag_id',
			'associationForeignKey' => 'table_post_id',
			'conditions' => '',
			'order' => '',
			'limit' => '',
			'unique' => true,
			'finderQuery' => '',
			'deleteQuery' => ''
	));

/**
 * validate
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'テーブルタグを入力してください。'
			),
			'duplicate' => array(
				'rule' => array('duplicate', 'name'),
				'message' => '既に登録のあるタグです。'
			)
	));

}
