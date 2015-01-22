<?php
/**
 * 記事コントローラー
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
App::uses('Xml', 'Utility');
/**
 * Include files
 */

/**
 * 記事コントローラー
 *
 * @package Table.Controller
 */
class TablePostsController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TablePosts';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('Table.TableCategory', 'Table.TablePost', 'Table.TableContent');

/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array('Table.Table');

/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure', 'BcEmail');

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
 * テーブルコンテンツデータ
 *
 * @var array
 * @access public
 */
	public $tableContent;

/**
 * beforeFilter
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (isset($this->request->params['pass'][0])) {

			$this->TableContent->recursive = -1;
			$this->tableContent = $this->TableContent->read(null, $this->request->params['pass'][0]);
			$this->crumbs[] = array('name' => $this->tableContent['TableContent']['title'] . '管理', 'url' => array('controller' => 'table_posts', 'action' => 'index', $this->request->params['pass'][0]));
			$this->TablePost->setupUpload($this->tableContent['TableContent']['id']);
			if ($this->request->params['prefix'] == 'admin') {
				$this->subMenuElements = array('table_posts', 'table_categories', 'table_common');
			}
			if (!empty($this->siteConfigs['editor']) && $this->siteConfigs['editor'] != 'none') {
				$this->helpers[] = $this->siteConfigs['editor'];
			}
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
		$this->set('tableContent', $this->tableContent);
	}

/**
 * [ADMIN] 一覧表示
 *
 * @return void
 * @access public
 */
	public function admin_index($tableContentId) {
		if (!$tableContentId || !$this->tableContent) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		/* 画面情報設定 */
		$default = array('named' => array('num' => $this->siteConfigs['admin_list_num']));
		$this->setViewConditions('TablePost', array('group' => $tableContentId, 'default' => $default));

		/* 検索条件生成 */
		$joins = array();

		if (!empty($this->request->data['TablePost']['table_tag_id'])) {
			$db = ConnectionManager::getDataSource($this->TablePost->useDbConfig);
			$datasouce = strtolower(preg_replace('/^Database\/Bc/', '', $db->config['datasource']));
			if ($datasouce != 'csv') {
				$joins = array(
					array(
						'table' => $db->config['prefix'] . 'table_posts_table_tags',
						'alias' => 'TablePostsTableTag',
						'type' => 'inner',
						'conditions' => array('TablePostsTableTag.table_post_id = TablePost.id')
					),
					array(
						'table' => $db->config['prefix'] . 'table_tags',
						'alias' => 'TableTag',
						'type' => 'inner',
						'conditions' => array('TableTag.id = TablePostsTableTag.table_tag_id', 'TableTag.id' => $this->request->data['TablePost']['table_tag_id'])
				));
			}
		}
		$conditions = $this->_createAdminIndexConditions($tableContentId, $this->request->data);
		$this->paginate = array('conditions' => $conditions,
			'joins' => $joins,
			'order' => 'TablePost.no DESC',
			'limit' => $this->passedArgs['num']
		);
		$this->set('posts', $this->paginate('TablePost'));

		$this->_setAdminIndexViewData();

		if ($this->RequestHandler->isAjax() || !empty($this->request->query['ajax'])) {
			$this->render('ajax_index');
			return;
		}

		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] 記事一覧';
		$this->search = 'table_posts_index';
		$this->help = 'table_posts_index';
	}

/**
 * 一覧の表示用データをセットする
 *
 * @return void
 * @access protected
 */
	protected function _setAdminIndexViewData() {
		$user = $this->BcAuth->user();
		$allowOwners = array();
		if (!empty($user)) {
			$allowOwners = array('', $user['user_group_id']);
		}
		$this->set('allowOwners', $allowOwners);
		$this->set('users', $this->TablePost->User->getUserList());
	}

/**
 * ページ一覧用の検索条件を生成する
 *
 * @param array $tableContentId
 * @param array $data
 * @return array $conditions
 * @access protected
 */
	protected function _createAdminIndexConditions($tableContentId, $data) {
		unset($data['ListTool']);
		$name = $tableCategoryId = '';
		if (isset($data['TablePost']['name'])) {
			$name = $data['TablePost']['name'];
		}

		unset($data['TablePost']['name']);
		unset($data['_Token']);
		if (isset($data['TablePost']['status']) && $data['TablePost']['status'] === '') {
			unset($data['TablePost']['status']);
		}
		if (isset($data['TablePost']['user_id']) && $data['TablePost']['user_id'] === '') {
			unset($data['TablePost']['user_id']);
		}
		if (!empty($data['TablePost']['table_category_id'])) {
			$tableCategoryId = $data['TablePost']['table_category_id'];
		}
		unset($data['TablePost']['table_category_id']);

		$conditions = array('TablePost.table_content_id' => $tableContentId);

		// CSVの場合はHABTM先のテーブルの条件を直接設定できない為、タグに関連するポストを抽出して条件を生成
		$db = ConnectionManager::getDataSource($this->TablePost->useDbConfig);

		if ($db->config['datasource'] == 'Database/BcCsv') {
			if (!empty($data['TablePost']['table_tag_id'])) {
				$tableTags = $this->TablePost->TableTag->read(null, $data['TablePost']['table_tag_id']);
				if ($tableTags) {
					$conditions['TablePost.id'] = Hash::extract($tableTags, '{n}.TablePost.id');
				}
			}
		}

		unset($data['TablePost']['table_tag_id']);

		// ページカテゴリ（子カテゴリも検索条件に入れる）
		if ($tableCategoryId) {
			$tableCategoryIds = array($tableCategoryId);
			$children = $this->TableCategory->children($tableCategoryId);
			if ($children) {
				foreach ($children as $child) {
					$tableCategoryIds[] = $child['TableCategory']['id'];
				}
			}
			$conditions['TablePost.table_category_id'] = $tableCategoryIds;
		} else {
			unset($data['TablePost']['table_category_id']);
		}

		$_conditions = $this->postConditions($data);
		if ($_conditions) {
			$conditions = am($conditions, $_conditions);
		}

		if ($name) {
			$conditions['TablePost.name LIKE'] = '%' . $name . '%';
		}

		return $conditions;
	}

/**
 * [ADMIN] 登録処理
 *
 * @param int $tableContentId
 * @return void
 * @access public
 */
	public function admin_add($tableContentId) {
		if (!$tableContentId || !$this->tableContent) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->TablePost->getDefaultValue($this->BcAuth->user());
		} else {

			$this->request->data['TablePost']['table_content_id'] = $tableContentId;
			$this->request->data['TablePost']['no'] = $this->TablePost->getMax('no', array('TablePost.table_content_id' => $tableContentId)) + 1;
			$this->request->data['TablePost']['posts_date'] = str_replace('/', '-', $this->request->data['TablePost']['posts_date']);

			/*			 * * TablePosts.beforeAdd ** */
			$event = $this->dispatchEvent('beforeAdd', array(
				'data' => $this->request->data
			));
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			// データを保存
			if ($this->TablePost->saveAll($this->request->data)) {
				clearViewCache();
				$id = $this->TablePost->getLastInsertId();
				$this->setMessage('記事「' . $this->request->data['TablePost']['name'] . '」を追加しました。', false, true);

				// 下のTablePost::read()で、TableTagデータ無しのキャッシュを作ってしまわないように
				// recursiveを設定
				$this->TablePost->recursive = 1;

				/*				 * * afterAdd ** */
				$this->dispatchEvent('afterAdd', array(
					'data' => $this->TablePost->read(null, $id)
				));

				// 編集画面にリダイレクト
				$this->redirect(array('action' => 'edit', $tableContentId, $id));
			} else {
				$this->setMessage('エラーが発生しました。内容を確認してください。', true);
			}
		}

		// 表示設定
		$user = $this->BcAuth->user();
		$categories = $this->TablePost->getControlSource('table_category_id', array(
			'tableContentId' => $this->tableContent['TableContent']['id'],
			'rootEditable' => $this->checkRootEditable(),
			'userGroupId' => $user['user_group_id'],
			'postEditable' => true,
			'empty' => '指定しない'
		));

		$editorOptions = array('editorDisableDraft' => true);
		if (!empty($this->siteConfigs['editor_styles'])) {
			App::uses('CKEditorStyleParser', 'Vendor');
			$CKEditorStyleParser = new CKEditorStyleParser();
			$editorStyles = array('default' => $CKEditorStyleParser->parse($this->siteConfigs['editor_styles']));
			$editorOptions = array_merge($editorOptions, array(
				'editorStylesSet' => 'default',
				'editorStyles' => $editorStyles
			));
		}

		$this->set('editable', true);
		$this->set('categories', $categories);
		$this->set('previewId', 'add_' . mt_rand(0, 99999999));
		$this->set('editorOptions', $editorOptions);
		$this->set('users', $this->TablePost->User->getUserList(array('User.id' => $user['id'])));
		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] 新規記事登録';
		$this->help = 'table_posts_form';
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
		if (!$tableContentId || !$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->TablePost->read(null, $id);
		} else {
			if (!empty($this->request->data['TablePost']['posts_date'])) {
				$this->request->data['TablePost']['posts_date'] = str_replace('/', '-', $this->request->data['TablePost']['posts_date']);
			}

			/*			 * * TablePosts.beforeEdit ** */
			$event = $this->dispatchEvent('beforeEdit', array(
				'data' => $this->request->data
			));
			if ($event !== false) {
				$this->request->data = $event->result === true ? $event->data['data'] : $event->result;
			}

			// データを保存
			if ($this->TablePost->saveAll($this->request->data)) {
				clearViewCache();

				/*				 * * TablePosts.afterEdit ** */
				$this->dispatchEvent('afterEdit', array(
					'data' => $this->TablePost->read(null, $id)
				));

				$this->setMessage('記事「' . $this->request->data['TablePost']['name'] . '」を更新しました。', false, true);
				$this->redirect(array('action' => 'edit', $tableContentId, $id));
			} else {
				$this->setMessage('エラーが発生しました。内容を確認してください。', true);
			}
		}

		// 表示設定
		$user = $this->BcAuth->user();
		$editable = false;
		$tableCategoryId = '';
		$currentCatOwner = '';

		if (isset($this->request->data['TablePost']['table_category_id'])) {
			$tableCategoryId = $this->request->data['TablePost']['table_category_id'];
		}
		if (!$tableCategoryId) {
			$currentCatOwner = $this->siteConfigs['root_owner_id'];
		} else {
			if (empty($this->request->data['TableCategory']['owner_id'])) {
				$data = $this->TablePost->TableCategory->find('first', array('conditions' => array('TableCategory.id' => $this->request->data['TablePost']['table_category_id']), 'recursive' => -1));
				$currentCatOwner = $data['TableCategory']['owner_id'];
			}
		}

		$editable = ($currentCatOwner == $user['user_group_id'] ||
			$user['user_group_id'] == Configure::read('BcApp.adminGroupId') || !$currentCatOwner);

		$categories = $this->TablePost->getControlSource('table_category_id', array(
			'tableContentId' => $this->tableContent['TableContent']['id'],
			'rootEditable' => $this->checkRootEditable(),
			'tableCategoryId' => $tableCategoryId,
			'userGroupId' => $user['user_group_id'],
			'postEditable' => $editable,
			'empty' => '指定しない'
		));

		if ($this->request->data['TablePost']['status']) {
			$this->set('publishLink', '/' . $this->tableContent['TableContent']['name'] . '/archives/' . $this->request->data['TablePost']['no']);
		}

		$editorOptions = array('editorDisableDraft' => false);
		if (!empty($this->siteConfigs['editor_styles'])) {
			App::uses('CKEditorStyleParser', 'Vendor');
			$CKEditorStyleParser = new CKEditorStyleParser();
			$editorStyles = array('default' => $CKEditorStyleParser->parse($this->siteConfigs['editor_styles']));
			$editorOptions = array_merge($editorOptions, array(
				'editorStylesSet' => 'default',
				'editorStyles' => $editorStyles
			));
		}

		$this->set('currentCatOwnerId', $currentCatOwner);
		$this->set('editable', $editable);
		$this->set('categories', $categories);
		$this->set('previewId', $this->request->data['TablePost']['id']);
		$this->set('users', $this->TablePost->User->getUserList());
		$this->set('editorOptions', $editorOptions);
		$this->pageTitle = '[' . $this->tableContent['TableContent']['title'] . '] 記事編集： ' . $this->request->data['TablePost']['name'];
		$this->help = 'table_posts_form';
		$this->render('form');
	}

/**
 * [ADMIN] 削除処理　(ajax)
 *
 * @param int $tableContentId
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_delete($tableContentId, $id = null) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}

		// 削除実行
		if ($this->_del($id)) {
			clearViewCache();
			exit(true);
		}

		exit();
	}

/**
 * 一括削除
 *
 * @param array $ids
 * @return boolean
 * @access protected
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
 * データを削除する
 *
 * @param int $id
 * @return boolean
 * @access protected
 */
	protected function _del($id) {
		// メッセージ用にデータを取得
		$post = $this->TablePost->read(null, $id);

		// 削除実行
		if ($this->TablePost->delete($id)) {
			$this->TablePost->saveDbLog($post['TablePost']['name'] . ' を削除しました。');
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
 * @return void
 * @access public
 */
	public function admin_delete($tableContentId, $id = null) {
		if (!$tableContentId || !$id) {
			$this->setMessage('無効な処理です。', true);
			$this->redirect(array('controller' => 'table_contents', 'action' => 'index'));
		}

		// メッセージ用にデータを取得
		$post = $this->TablePost->read(null, $id);

		// 削除実行
		if ($this->TablePost->delete($id)) {
			clearViewCache();
			$this->setMessage($post['TablePost']['name'] . ' を削除しました。', false, true);
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		$this->redirect(array('action' => 'index', $tableContentId));
	}

/**
 * 外部データインポート
 * WordPressのみ対応（2.2.3のみ検証済）
 *
 * @return void
 * @access public
 * @todo 未実装
 */
	public function admin_import() {
		// 入力チェック
		$check = true;
		$message = '';
		if (!isset($this->request->data['Import']['table_content_id']) || !$this->request->data['Import']['table_content_id']) {
			$message .= '取り込み対象のテーブルを選択してください<br />';
			$check = false;
		}
		if (!isset($this->request->data['Import']['user_id']) || !$this->request->data['Import']['user_id']) {
			$message .= '記事の投稿者を選択してください<br />';
			$check = false;
		}
		if (!isset($this->request->data['Import']['file']['tmp_name'])) {
			$message .= 'XMLデータを選択してください<br />';
			$check = false;
		}
		if ($this->request->data['Import']['file']['type'] != 'text/xml') {
			$message .= 'XMLデータを選択してください<br />';
			$check = false;
		} else {

			// XMLデータを読み込む
			$xml = new Xml($this->request->data['Import']['file']['tmp_name']);

			$_posts = Xml::toArray($xml);

			if (!isset($_posts['Rss']['Channel']['Item'])) {
				$message .= 'XMLデータが不正です<br />';
				$check = false;
			} else {
				$_posts = $_posts['Rss']['Channel']['Item'];
			}
		}

		// 送信内容に問題がある場合には元のページにリダイレクト
		if (!$check) {
			$this->setMessage($message, true);
			$this->redirect(array('controller' => 'table_configs', 'action' => 'form'));
		}

		// カテゴリ一覧の取得
		$tableCategoryList = $this->TableCategory->find('list', array('conditions' => array('table_content_id' => $this->request->data['Import']['table_content_id'])));
		$tableCategoryList = array_flip($tableCategoryList);

		// ポストデータに変換し１件ずつ保存
		$count = 0;
		foreach ($_posts as $_post) {
			if (!$_post['Encoded'][0]) {
				continue;
			}
			$post = array();
			$post['table_content_id'] = $this->request->data['Import']['table_content_id'];
			$post['no'] = $this->TablePost->getMax('no', array('TablePost.table_content_id' => $this->request->data['Import']['table_content_id'])) + 1;
			$post['name'] = $_post['title'];
			$_post['Encoded'][0] = str_replace("\n", "<br />", $_post['Encoded'][0]);
			$encoded = explode('<!--more-->', $_post['Encoded'][0]);
			$post['content'] = $encoded[0];
			if (isset($encoded[1])) {
				$post['detail'] = $encoded[1];
			} else {
				$post['detail'] = '';
			}
			if (isset($_post['Category'])) {
				$_post['category'] = $_post['Category'][0];
			} elseif (isset($_post['category'])) {
				$_post['category'] = $_post['category'];
			} else {
				$_post['category'] = '';
			}
			if (isset($tableCategoryList[$_post['category']])) {
				$post['table_category_no'] = $tableCategoryList[$_post['category']];
			} else {
				$no = $this->TableCategory->getMax('no', array('TableCategory.table_content_id' => $this->request->data['Import']['table_content_id'])) + 1;
				$this->TableCategory->create(array('name' => $_post['category'], 'table_content_id' => $this->request->data['Import']['table_content_id'], 'no' => $no));
				$this->TableCategory->save();
				$post['table_category_id'] = $this->TableCategory->getInsertID();
				$tableCategoryList[$_post['category']] = $post['table_category_id'];
			}

			$post['user_id'] = $this->request->data['Import']['user_id'];
			$post['status'] = 1;
			$post['posts_date'] = $_post['post_date'];

			$this->TablePost->create($post);
			if ($this->TablePost->save()) {
				$count++;
			}
		}

		$this->setMessage($count . ' 件の記事を取り込みました');
		$this->redirect(array('controller' => 'table_configs', 'action' => 'form'));
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
	public function admin_ajax_unpublish($tableContentId, $id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, false)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->TablePost->validationErrors);
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
	public function admin_ajax_publish($tableContentId, $id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		if ($this->_changeStatus($id, true)) {
			clearViewCache();
			exit(true);
		} else {
			$this->ajaxError(500, $this->TablePost->validationErrors);
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
		$data = $this->TablePost->find('first', array('conditions' => array('TablePost.id' => $id), 'recursive' => -1));
		$data['TablePost']['status'] = $status;
		$data['TablePost']['publish_begin'] = '';
		$data['TablePost']['publish_end'] = '';
		unset($data['TablePost']['eye_catch']);
		$this->TablePost->set($data);

		if ($this->TablePost->save()) {
			$statusText = $statusTexts[$status];
			$this->TablePost->saveDbLog('テーブル記事「' . $data['TablePost']['name'] . '」 を' . $statusText . 'にしました。');
			return true;
		} else {
			return false;
		}
	}

/**
 * [ADMIN] コピー
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_copy($tableContentId, $id = null) {
		$result = $this->TablePost->copy($id);
		if ($result) {
			// タグ情報を取得するため読み込みなおす
			$this->TablePost->recursive = 1;
			$data = $this->TablePost->read();
			$this->setViewConditions('TablePost', array('action' => 'admin_index'));
			$this->_setAdminIndexViewData();
			$this->set('data', $data);
		} else {
			$this->ajaxError(500, $this->TablePost->validationErrors);
		}
	}

}
