<?php

/**
 * テーブルコメントコントローラー
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
 * テーブルコメントコントローラー
 *
 * @package Table.Controller
 */
class TableCommentsController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableComments';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('Table.TableCategory', 'Table.TableComment', 'Table.TablePost');

/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array();

/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure', 'RequestHandler', 'BcEmail', 'Security', 'BcCaptcha');

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

		$this->BcAuth->allow('add', 'captcha', 'smartphone_add', 'smartphone_captcha', 'get_token');

		$crumbs = array();
		if (!empty($this->params['pass'][1])) {

			$dbDatas = $this->TablePost->read(null, $this->params['pass'][1]);

			if (!$dbDatas) {
				$this->notFound();
			}

			$this->tablePost = array('TablePost' => $dbDatas['TablePost']);
			$this->tableContent = array('TableContent' => $dbDatas['TableContent']);

			$crumbs[] = array('name' => $this->tableContent['TableContent']['title'] . '管理', 'url' => array('controller' => 'table_posts', 'action' => 'index', $this->tableContent['TableContent']['id']));
			$crumbs[] = array('name' => $this->tablePost['TablePost']['name'], 'url' => array('controller' => 'table_posts', 'action' => 'edit', $this->tableContent['TableContent']['id'], $this->tablePost['TablePost']['id']));
		} elseif (!empty($this->params['pass'][0])) {

			$dbDatas = $this->TablePost->TableContent->read(null, $this->params['pass'][0]);
			$this->tableContent = array('TableContent' => $dbDatas['TableContent']);
			$crumbs[] = array('name' => $this->tableContent['TableContent']['title'] . '管理', 'url' => array('controller' => 'table_posts', 'action' => 'index', $this->tableContent['TableContent']['id']));
		}

		$this->crumbs = am($this->crumbs, $crumbs);
		if (!empty($this->params['prefix']) && $this->params['prefix'] == 'admin') {
			$this->subMenuElements = array('table_posts', 'table_categories', 'table_common');
		}

		if (empty($this->params['admin'])) {
			$this->Security->enabled = true;
			$this->Security->requireAuth('add');
		}
	}

/**
 * beforeRender
 *
 * @return void
 * @access public
 */
	public function beforeRender() {
		parent::beforeRender();
		if (!empty($this->tableContent)) {
			$this->set('tableContent', $this->tableContent);
		}
	}

/**
 * [ADMIN] テーブルを一覧表示する
 *
 * @return void
 * @access public
 */
	public function admin_index($tableContentId, $tablePostId = null) {
		if (!$tableContentId || empty($this->tableContent['TableContent'])) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		/* 検索条件 */
		if ($tablePostId) {
			$conditions['TableComment.table_post_id'] = $tablePostId;
			$this->pageTitle = '記事 [' . $this->tablePost['TablePost']['name'] . '] のコメント一覧';
		} else {
			$conditions['TableComment.table_content_id'] = $tableContentId;
			$this->pageTitle = 'テーブル [' . $this->tableContent['TableContent']['title'] . '] のコメント一覧';
		}

		/* 画面情報設定 */
		$default = array('named' => array('num' => $this->siteConfigs['admin_list_num']));
		$this->setViewConditions('TablePost', array('group' => $tableContentId, 'default' => $default));

		// データを取得
		$this->paginate = array('conditions' => $conditions,
			'fields' => array(),
			'order' => 'TableComment.created DESC',
			'limit' => $this->passedArgs['num']
		);

		$dbDatas = $this->paginate('TableComment');
		$this->set('dbDatas', $dbDatas);
		$this->help = 'table_comments_index';
	}

/**
 * [ADMIN] 一括削除
 *
 * @param int $tableContentId
 * @param int $tablePostId
 * @param int $id
 * @return void
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
 * @param int $tablePostId
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_delete($tableContentId, $tablePostId, $id = null) {
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
 * @param int $tablePostId
 * @param int $id
 * @return void
 * @access public
 */
	protected function _del($id = null) {
		/* 削除処理 */
		if ($this->TableComment->delete($id)) {
			if (isset($this->tablePost['TablePost']['name'])) {
				$message = '記事「' . $this->tablePost['TablePost']['name'] . '」へのコメントを削除しました。';
			} else {
				$message = '記事「' . $this->tableContent['TableContent']['title'] . '」へのコメントを削除しました。';
			}
			$this->TableComment->saveDbLog($message);
			return true;
		} else {
			return false;
		}
	}

/**
 * [ADMIN] 削除処理
 *
 * @param int $tableContentId
 * @param int $tablePostId
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_delete($tableContentId, $tablePostId, $id = null) {
		/* 除外処理 */
		if (!$tableContentId || !$id) {
			$this->notFound();
		}

		/* 削除処理 */
		if ($this->TableComment->delete($id)) {
			if (isset($this->tablePost['TablePost']['name'])) {
				$message = '記事「' . $this->tablePost['TablePost']['name'] . '」へのコメントを削除しました。';
			} else {
				$message = '記事「' . $this->tableContent['TableContent']['title'] . '」へのコメントを削除しました。';
			}
			$this->setMessage($message, false, true);
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		if ($tablePostId) {
			$this->redirect(array('action' => 'index', $tableContentId, $tablePostId));
		} else {
			$this->redirect(array('action' => 'index', $tableContentId));
		}
	}

/**
 * [ADMIN] 無効状態にする（AJAX）
 *
 * @param string $tableContentId
 * @param string $tablePostId beforeFilterで利用
 * @param string $tableCommentId
 * @return void
 * @access public
 */
	public function admin_ajax_unpublish($tableContentId, $tablePostId, $id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->TableComment->validationErrors);
		}
		exit();
	}

/**
 * [ADMIN] 有効状態にする（AJAX）
 *
 * @param string $tableContentId
 * @param string $tablePostId beforeFilterで利用
 * @param string $tableCommentId
 * @return void
 * @access public
 */
	public function admin_ajax_publish($tableContentId, $tablePostId, $id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->TableComment->validationErrors);
		}
		exit();
	}

/**
 * 一括公開
 *
 * @param array $ids
 * @return boolean
 * @access protected
 */
	protected function _batch_publish($ids) {
		if ($ids) {
			foreach ($ids as $id) {
				$this->_changeStatus($id, true);
			}
		}
		clearViewCache();
		return true;
	}

/**
 * 一括非公開
 *
 * @param array $ids
 * @return boolean
 * @access protected
 */
	protected function _batch_unpublish($ids) {
		if ($ids) {
			foreach ($ids as $id) {
				$this->_changeStatus($id, false);
			}
		}
		clearViewCache();
		return true;
	}

/**
 * ステータスを変更する
 *
 * @param int $id
 * @param boolean $status
 * @return boolean
 */
	protected function _changeStatus($id, $status) {
		$statusTexts = array(0 => '非公開状態', 1 => '公開状態');
		$data = $this->TableComment->find('first', array('conditions' => array('TableComment.id' => $id), 'recursive' => -1));
		$data['TableComment']['status'] = $status;
		$this->TableComment->set($data);

		if ($this->TableComment->save()) {
			$statusText = $statusTexts[$status];
			if (isset($this->tablePost['TablePost']['name'])) {
				$message = '記事「' . $this->tablePost['TablePost']['name'] . '」へのコメントを' . $statusText . 'に設定しました。';
			} else {
				$message = '記事「' . $this->tableContent['TableContent']['title'] . '」へのコメントを' . $statusText . 'に設定しました。';
			}
			$this->TableComment->saveDbLog($message);
			return true;
		} else {
			return false;
		}
	}

/**
 * [AJAX] テーブルコメントを登録する
 *
 * @param string $tableContentId
 * @param string $tablePostId
 * @return boolean
 * @access public
 */
	public function add($tableContentId, $tablePostId) {
		Configure::write('debug', 0);

		if (!$this->request->data || !$tableContentId || !$tablePostId || empty($this->tableContent) || !$this->tableContent['TableContent']['comment_use']) {
			$this->notFound();
		} else {

			// 画像認証を行う
			$captchaResult = true;
			if ($this->tableContent['TableContent']['auth_captcha']) {
				$captchaResult = $this->BcCaptcha->check($this->request->data['TableComment']['auth_captcha']);
				if (!$captchaResult) {
					$this->set('dbData', false);
					return false;
				} else {
					unset($this->request->data['TableComment']['auth_captcha']);
				}
			}

			$result = $this->TableComment->add($this->request->data, $tableContentId, $tablePostId, $this->tableContent['TableContent']['comment_approve']);
			if ($result && $captchaResult) {
				$this->_sendCommentAdmin($tablePostId, $this->request->data);
				// コメント承認機能を利用していない場合は、公開されているコメント投稿者にアラートを送信
				if (!$this->tableContent['TableContent']['comment_approve']) {
					$this->_sendCommentContributor($tablePostId, $this->request->data);
				}
				$this->set('dbData', $result['TableComment']);
			} else {
				$this->set('dbData', false);
			}
		}
	}

/**
 * [AJAX] テーブルコメントを登録する
 *
 * @param string $tableContentId
 * @param string $tablePostId
 * @return boolean
 * @access public
 */
	public function smartphone_add($tableContentId, $tablePostId) {
		$this->setAction('add', $tableContentId, $tablePostId);
	}

/**
 * 認証用のキャプチャ画像を表示する
 *
 * @return void
 * @access public
 */
	public function captcha() {
		$this->BcCaptcha->render();
		exit();
	}

/**
 * [SMARTPHONE] 認証用のキャプチャ画像を表示する
 *
 * @return void
 * @access public
 */
	public function smartphone_captcha() {
		$this->BcCaptcha->render();
		exit();
	}

/**
 * コメント送信用にAjax経由でトークンを取得する
 */
	public function get_token() {
		if (!preg_match('/^' . preg_quote(Configure::read('BcEnv.siteUrl'), '/') . '/', $_SERVER['HTTP_REFERER'])) {
			$this->notFound();
		}
		echo $this->Session->read('_Token.key');
		exit();
	}

}
