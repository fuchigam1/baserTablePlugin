<?php

/**
 * テーブル設定コントローラー
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2014, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Table.Controller
 * @since			baserCMS v 0.1.0
 * @license			http://basercms.net/license/index.html
 */
/**
 * Include files
 */

/**
 * テーブル設定コントローラー
 *
 * @package Table.Controller
 */
class TableConfigsController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableConfigs';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('User', 'Table.TableCategory', 'Table.TableConfig', 'Table.TableContent');

/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');

/**
 * サブメニューエレメント
 *
 * @var array
 * @access public
 */
	public $subMenuElements = array();

/**
 * ぱんくずナビ
 *
 * @var string
 * @access public
 */
	public $crumbs = array(
		array('name' => 'プラグイン管理', 'url' => array('plugin' => '', 'controller' => 'plugins', 'action' => 'index')),
		array('name' => 'テーブル管理', 'url' => array('controller' => 'table_contents', 'action' => 'index'))
	);

/**
 * before_filter
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->params['prefix'] == 'admin') {
			$this->subMenuElements = array('table_common');
		}
	}

/**
 * [ADMIN] サイト基本設定
 *
 * @return void
 * @access public
 */
	public function admin_form() {
		if (empty($this->request->data)) {
			$this->request->data = $this->TableConfig->read(null, 1);
			$tableContentList = $this->TableContent->find("list");
			$this->set('tableContentList', $tableContentList);
			$userList = $this->User->find("list");
			$this->set('userList', $userList);
		} else {

			/* 更新処理 */
			if ($this->TableConfig->save($this->request->data)) {
				$this->setMessage('テーブル設定を保存しました。', false, true);
				$this->redirect(array('action' => 'form'));
			} else {
				$this->setMessage('入力エラーです。内容を修正してください。', true);
			}
		}

		/* 表示設定 */
		$this->pageTitle = 'テーブル設定';
	}

}
