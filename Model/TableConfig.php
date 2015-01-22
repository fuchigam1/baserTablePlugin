<?php

/**
 * テーブル設定モデル
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
 * テーブル設定モデル
 *
 * @package Table.Model
 */
class TableConfig extends TableAppModel {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableConfig';

/**
 * ビヘイビア
 *
 * @var array
 * @access public
 */
	public $actsAs = array('BcCache');

}
