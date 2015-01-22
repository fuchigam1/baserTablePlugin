<?php

/**
 * カテゴリコントローラー
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
 * カテゴリコントローラー
 *
 * @package Table.Controller
 * @property TableContent $TableContent
 * @property TableCategory $TableCategory
 */
class TableCategoriesController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableCategories';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('Table.TableCategory', 'Table.TableContent');

/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array('BcText', 'BcTime', 'BcForm', 'Table.Table');

/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure');

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
 * サブメニューエレメント
 *
 * @var array
 * @access public
 */
	public $subMenuElements = array();

/**
 * beforeFilter
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->TableContent->recursive = -1;
		$this->tableContent = $this->TableContent->read(null, $this->params['pass'][0]);
		$this->crumbs[] = array('name' => $this->tableContent['TableContent']['title'] . '管理', 'url' => array('controller' => 'table_posts', 'action' => 'index', $this->params['pass'][0]));

		if ($this->params['prefix'] == 'admin') {
			$this->subMenuElements = array('table_posts', 'table_categories', 'table_common');
		}

		// バリデーション設定
		$this->TableCategory->validationParams['tableContentId'] = $this->tableContent['TableContent']['id'];
	}

/**
 * beforeRender
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		parent::beforeRender();
		$this->set('tableContent', $this->tableContent);
	}

/**
 * [ADMIN] テーブルを一覧表示する
 *
 * @return void
 * @access public
 */
	public function admin_index($tableContentId) {
		$conditions = array('TableCategory.table_content_id' => $tableContentId);
		$_dbDatas = $this->TableCategory->generateTreeList($conditions);
		$dbDatas = array();
		foreach ($_dbDatas as $key => $dbData) {
			$category = $this->TableCategory->find('first', array('conditions' => array('TableCategory.id' => $key)));
			if (preg_match("/^([_]+)/i", $dbData, $matches)) {
				$prefix = str_replace('_', '&nbsp&nbsp&nbsp', $matches[1]);
				$category['TableCategory']['title'] = $prefix . '└' . $category['TableCategory']['title'];
				$category['TableCategory']['depth'] = strlen($matches[1]);
			} else {
				$category['TableCategory']['depth'] = 0;
			}
			$dbDatas[] = $category;
		}

		/* 表示設定 */
		$this->set('owners', $this->TableCategory->getControlSource('owner_id'));
		$this->set('dbDatas', $dbDatas);
		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] テーブルカテゴリ一覧';
		$this->help = 'table_categories_index';
	}

/**
 * [ADMIN] 登録処理
 *
 * @param string $tableContentId
 * @return void
 * @access public
 */
	public function admin_add($tableContentId) {
		if (!$tableContentId) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		if (empty($this->request->data)) {

			$user = $this->BcAuth->user();
			$this->request->data = array('TableCategory' => array(
					'owner_id' => $user['user_group_id']
			));
		} else {

			/* 登録処理 */
			$this->request->data['TableCategory']['table_content_id'] = $tableContentId;
			$this->request->data['TableCategory']['no'] = $this->TableCategory->getMax('no', array('TableCategory.table_content_id' => $tableContentId)) + 1;
			$this->TableCategory->create($this->request->data);

			// データを保存
			if ($this->TableCategory->save()) {
				$this->setMessage('カテゴリー「' . $this->request->data['TableCategory']['name'] . '」を追加しました。', false, true);
				$this->redirect(array('action' => 'index', $tableContentId));
			} else {
				$this->setMessage('入力エラーです。内容を修正してください。', true);
			}
		}

		/* 表示設定 */
		$user = $this->BcAuth->user();
		$catOptions = array('tableContentId' => $this->tableContent['TableContent']['id']);
		if ($user['user_group_id'] != Configure::read('BcApp.adminGroupId')) {
			$catOptions['ownerId'] = $user['user_group_id'];
		}
		$parents = $this->TableCategory->getControlSource('parent_id', $catOptions);
		if ($this->checkRootEditable()) {
			if ($parents) {
				$parents = array('' => '指定しない') + $parents;
			} else {
				$parents = array('' => '指定しない');
			}
		}
		$this->set('parents', $parents);
		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] 新規テーブルカテゴリ登録';
		$this->help = 'table_categories_form';
		$this->render('form');
	}

/**
 * [ADMIN] 編集処理
 *
 * @param int $tableContentId
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_edit($tableContentId, $id) {
		/* 除外処理 */
		if (!$id && empty($this->request->data)) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->TableCategory->read(null, $id);
		} else {

			/* 更新処理 */
			if ($this->TableCategory->save($this->request->data)) {
				$this->setMessage('カテゴリー「' . $this->request->data['TableCategory']['name'] . '」を更新しました。', false, true);
				$this->redirect(array('action' => 'index', $tableContentId));
			} else {
				$this->setMessage('入力エラーです。内容を修正してください。', true);
			}
		}

		/* 表示設定 */
		$user = $this->BcAuth->user();
		$catOptions = array(
			'tableContentId' => $this->tableContent['TableContent']['id'],
			'excludeParentId' => $this->request->data['TableCategory']['id']
		);
		if ($user['user_group_id'] != Configure::read('BcApp.adminGroupId')) {
			$catOptions['ownerId'] = $user['user_group_id'];
		}
		$parents = $this->TableCategory->getControlSource('parent_id', $catOptions);
		if ($this->checkRootEditable()) {
			if ($parents) {
				$parents = array('' => '指定しない') + $parents;
			} else {
				$parents = array('' => '指定しない');
			}
		}
		$this->set('parents', $parents);
		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] テーブルカテゴリ編集';
		$this->help = 'table_categories_form';
		$this->render('form');
	}

/**
 * [ADMIN] 一括削除
 *
 * @param int $tableContentId
 * @param int $id
 * @return	void
 * @access public
 */
	protected function _batch_del($ids) {
		if ($ids) {
			foreach ($ids as $id) {
				$this->_del($id);
			}
		}
		return true;
	}

/**
 * [ADMIN] 削除処理　(ajax)
 *
 * @param int $tableContentId
 * @param int $id
 * @return	void
 * @access public
 */
	public function admin_ajax_delete($tableContentId, $id = null) {
		/* 除外処理 */
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}

		if ($this->_del($id)) {
			exit(true);
		} else {
			exit();
		}
	}

/**
 * 削除処理
 *
 * @param int $tableContentId
 * @param int $id
 * @return	void
 * @access public
 */
	protected function _del($id = null) {
		// メッセージ用にデータを取得
		$data = $this->TableCategory->read(null, $id);
		/* 削除処理 */
		if ($this->TableCategory->removeFromTreeRecursive($id)) {
			$this->TableCategory->saveDbLog('カテゴリー「' . $data['TableCategory']['name'] . '」を削除しました。');
			return true;
		} else {
			return false;
		}
	}

/**
 * [ADMIN] 削除処理
 *
 * @param int $tableContentId
 * @param int $id
 * @return	void
 * @access public
 */
	public function admin_delete($tableContentId, $id = null) {
		/* 除外処理 */
		if (!$id) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}

		// メッセージ用にデータを取得
		$post = $this->TableCategory->read(null, $id);

		/* 削除処理 */
		if ($this->TableCategory->removeFromTreeRecursive($id)) {
			$this->setMessage($post['TableCategory']['name'] . ' を削除しました。', false, true);
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		$this->redirect(array('action' => 'index', $tableContentId));
	}

/**
 * [ADMIN] 追加処理（AJAX）
 *
 * @param int $tableContentId
 */
	public function admin_ajax_add($tableContentId) {
		if (!empty($this->request->data)) {
			if (strlen($this->request->data['TableCategory']['name']) == mb_strlen($this->request->data['TableCategory']['name'])) {
				$this->request->data['TableCategory']['title'] = $this->request->data['TableCategory']['name'];
			} else {
				$this->request->data['TableCategory']['title'] = $this->request->data['TableCategory']['name'];
				$this->request->data['TableCategory']['name'] = substr(urlencode($this->request->data['TableCategory']['name']), 0, 49);
			}
			$this->request->data['TableCategory']['table_content_id'] = $tableContentId;
			$this->request->data['TableCategory']['no'] = $this->TableCategory->getMax('no', array('TableCategory.table_content_id' => $tableContentId)) + 1;
			$this->TableCategory->create($this->request->data);
			if ($this->TableCategory->save()) {
				echo $this->TableCategory->getInsertID();
			} else {
				$this->ajaxError(500, $this->TableCategory->validationErrors);
			}
		} else {
			$this->ajaxError(500, '無効な処理です。');
		}

		exit();
	}

}
