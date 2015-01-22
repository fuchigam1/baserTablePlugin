<?php

/**
 * テーブルタグコントローラー
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
 * テーブルタグコントローラー
 *
 * @package Table.Controller
 */
class TableTagsController extends TableAppController {

/**
 * クラス名
 *
 * @var array
 * @access public
 */
	public $name = 'TableTags';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('Table.TableCategory', 'Table.TableTag');

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
	public $subMenuElements = array('table_common');

/**
 * [ADMIN] タグ一覧
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$default = array('named' => array('num' => $this->siteConfigs['admin_list_num'], 'sort' => 'id', 'direction' => 'asc'));
		$this->setViewConditions('TableTag', array('default' => $default));

		$this->paginate = array(
			'order' => 'TableTag.id',
			'limit' => $this->passedArgs['num'],
			'recursive' => 0
		);
		$this->set('datas', $this->paginate('TableTag'));

		$this->pageTitle = 'テーブルタグ一覧';
	}

/**
 * [ADMIN] タグ登録
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->request->data)) {

			$this->TableTag->create($this->request->data);
			if ($this->TableTag->save()) {
				$this->setMessage('タグ「' . $this->request->data['TableTag']['name'] . '」を追加しました。', false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('エラーが発生しました。内容を確認してください。', true);
			}
		}

		$this->pageTitle = '新規テーブルタグ登録';
		$this->render('form');
	}

/**
 * [ADMIN] タグ編集
 *
 * @param int $id タグID
 * @return void
 * @access public
 */
	public function admin_edit($id) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->TableTag->read(null, $id);
		} else {

			$this->TableTag->set($this->request->data);
			if ($this->TableTag->save()) {
				$this->setMessage('タグ「' . $this->request->data['TableTag']['name'] . '」を更新しました。', false, true);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->setMessage('エラーが発生しました。内容を確認してください。', true);
			}
		}

		$this->pageTitle = 'テーブルタグ編集： ' . $this->request->data['TableTag']['name'];
		$this->render('form');
	}

/**
 * [ADMIN] 削除処理
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('action' => 'index'));
		}

		$data = $this->TableTag->read(null, $id);

		if ($this->TableTag->delete($id)) {
			$this->setMessage('タグ「' . $this->TableTag->data['TableTag']['name'] . '」を削除しました。', false, true);
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		$this->redirect(array('action' => 'index'));
	}

/**
 * [ADMIN] 削除処理　(ajax)
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_delete($id = null) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}

		$data = $this->TableTag->read(null, $id);
		if ($this->TableTag->delete($id)) {
			$message = 'タグ「' . $this->TableTag->data['TableTag']['name'] . '」を削除しました。';
			$this->TableTag->saveDbLog($message);
			exit(true);
		}
		exit();
	}

/**
 * [ADMIN] 一括削除
 *
 * @param int $id
 * @return void
 * @access public
 */
	protected function _batch_del($ids) {
		if ($ids) {
			foreach ($ids as $id) {
				$data = $this->TableTag->read(null, $id);
				if ($this->TableTag->delete($id)) {
					$message = 'タグ「' . $this->TableTag->data['TableTag']['name'] . '」を削除しました。';
					$this->TableTag->saveDbLog($message);
				}
			}
		}
		return true;
	}

/**
 * [ADMIN] AJAXタグ登録
 *
 * @return void
 * @access public
 */
	public function admin_ajax_add() {
		if (!empty($this->request->data)) {
			$this->TableTag->create($this->request->data);
			if ($data = $this->TableTag->save()) {
				$result = array($this->TableTag->id => $data['TableTag']['name']);
				$this->set('result', $result);
			} else {
				$this->ajaxError(500, $this->TableTag->validationErrors);
			}
		} else {
			$this->ajaxError(500, '無効な処理です。');
		}
	}

}
