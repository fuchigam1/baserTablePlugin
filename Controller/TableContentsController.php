<?php

/**
 * テーブルコンテンツコントローラー
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
 * テーブルコンテンツコントローラー
 *
 * @package Table.Controller
 */
class TableContentsController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableContents';

/**
 * モデル
 *
 * @var array
 * @access public
 */
	public $uses = array('SiteConfig', 'Table.TableCategory', 'Table.TableContent');

/**
 * ヘルパー
 *
 * @var array
 * @access public
 */
	public $helpers = array('BcHtml', 'BcTime', 'BcForm', 'Table.Table');

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
 * before_filter
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
			$this->subMenuElements = array('table_common');
		}
	}

/**
 * [ADMIN] テーブルコンテンツ一覧
 *
 * @return void
 * @access public
 */
	public function admin_index() {
		$datas = $this->TableContent->find('all', array('order' => array('TableContent.id')));
		$this->set('datas', $datas);

		if ($this->RequestHandler->isAjax() || !empty($this->query['ajax'])) {
			$this->render('ajax_index');
			return;
		}

		$this->pageTitle = 'テーブル一覧';
		$this->help = 'table_contents_index';
	}

/**
 * [ADMIN] テーブルコンテンツ追加
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		$this->pageTitle = '新規テーブル登録';

		if (!$this->request->data) {

			$this->request->data = $this->TableContent->getDefaultValue();
		} else {

			$this->request->data = $this->TableContent->deconstructEyeCatchSize($this->request->data);
			$this->TableContent->create($this->request->data);

			if ($this->TableContent->save()) {

				$id = $this->TableContent->getLastInsertId();
				$this->setMessage('新規テーブル「' . $this->request->data['TableContent']['title'] . '」を追加しました。', false, true);
				$this->redirect(array('action' => 'edit', $id));
			} else {
				$this->setMessage('入力エラーです。内容を修正してください。', true);
			}

			$this->request->data = $this->TableContent->constructEyeCatchSize($this->request->data);
		}

		// テーマの一覧を取得
		$this->set('themes', $this->SiteConfig->getThemes());
		$this->render('form');
	}

/**
 * [ADMIN] 編集処理
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_edit($id) {
		/* 除外処理 */
		if (!$id && empty($this->request->data)) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {

			$this->request->data = $this->TableContent->read(null, $id);
			$this->request->data = $this->TableContent->constructEyeCatchSize($this->request->data);
		} else {

			$this->request->data = $this->TableContent->deconstructEyeCatchSize($this->request->data);
			$this->TableContent->set($this->request->data);

			if ($this->TableContent->save()) {

				$this->setMessage('テーブル「' . $this->request->data['TableContent']['title'] . '」を更新しました。', false, true);

				if ($this->request->data['TableContent']['edit_layout_template']) {
					$this->redirectEditLayout($this->request->data['TableContent']['layout']);
				} elseif ($this->request->data['TableContent']['edit_table_template']) {
					$this->redirectEditTable($this->request->data['TableContent']['template']);
				} else {
					$this->redirect(array('action' => 'edit', $id));
				}
			} else {
				$this->setMessage('入力エラーです。内容を修正してください。', true);
			}

			$this->request->data = $this->TableContent->constructEyeCatchSize($this->request->data);
		}

		$this->set('publishLink', '/' . $this->request->data['TableContent']['name'] . '/index');

		/* 表示設定 */
		$this->set('tableContent', $this->request->data);
		$this->subMenuElements = array('table_posts', 'table_categories', 'table_common');
		$this->set('themes', $this->SiteConfig->getThemes());
		$this->pageTitle = 'テーブル設定編集：' . $this->request->data['TableContent']['title'];
		$this->help = 'table_contents_form';
		$this->render('form');
	}

/**
 * レイアウト編集画面にリダイレクトする
 *
 * @param string $template
 * @return void
 * @access public
 */
	public function redirectEditLayout($template) {
		$target = WWW_ROOT . 'theme' . DS . $this->siteConfigs['theme'] . DS . 'Layouts' . DS . $template . $this->ext;
		$sorces = array(BASER_PLUGINS . 'table' . DS . 'View' . DS . 'Layouts' . DS . $template . $this->ext,
			BASER_VIEWS . 'Layouts' . DS . $template . $this->ext);
		if ($this->siteConfigs['theme']) {
			if (!file_exists($target)) {
				foreach ($sorces as $source) {
					if (file_exists($source)) {
						copy($source, $target);
						chmod($target, 0666);
						break;
					}
				}
			}
			$this->redirect(array('plugin' => null, 'controller' => 'theme_files', 'action' => 'edit', $this->siteConfigs['theme'], 'Layouts', $template . $this->ext));
		} else {
			$this->setMessage('現在、「テーマなし」の場合、管理画面でのテンプレート編集はサポートされていません。', true);
			$this->redirect(array('action' => 'index'));
		}
	}

/**
 * テーブルテンプレート編集画面にリダイレクトする
 *
 * @param string $template
 * @return void
 * @access public
 */
	public function redirectEditTable($template) {
		$path = 'table' . DS . $template;
		$target = WWW_ROOT . 'theme' . DS . $this->siteConfigs['theme'] . DS . $path;
		$sorces = array(BASER_PLUGINS . 'table' . DS . 'View' . DS . $path);
		if ($this->siteConfigs['theme']) {
			if (!file_exists($target . DS . 'index' . $this->ext)) {
				foreach ($sorces as $source) {
					if (is_dir($source)) {
						$folder = new Folder();
						$folder->create(dirname($target), 0777);
						$folder->copy(array('from' => $source, 'to' => $target, 'chmod' => 0777, 'skip' => array('_notes')));
						break;
					}
				}
			}
			$path = str_replace(DS, '/', $path);
			$this->redirect(array_merge(array('plugin' => null, 'controller' => 'theme_files', 'action' => 'edit', $this->siteConfigs['theme'], 'etc'), explode('/', $path . '/index' . $this->ext)));
		} else {
			$this->setMessage('現在、「テーマなし」の場合、管理画面でのテンプレート編集はサポートされていません。', true);
			$this->redirect(array('action' => 'index'));
		}
	}

/**
 * [ADMIN] 削除処理
 *
 * @param int $id
 * @return void
 * @access public
 * @deprecated
 */
	public function admin_delete($id = null) {
		/* 除外処理 */
		if (!$id) {
			$this->setMessage('無効なIDです。', true);
			$this->redirect(array('action' => 'index'));
		}

		// メッセージ用にデータを取得
		$post = $this->TableContent->read(null, $id);

		/* 削除処理 */
		if ($this->TableContent->delete($id)) {
			$this->setMessage('テーブル「' . $post['TableContent']['title'] . '」 を削除しました。', false, true);
		} else {
			$this->setMessage('データベース処理中にエラーが発生しました。', true);
		}

		$this->redirect(array('action' => 'index'));
	}

/**
 * [ADMIN] Ajax 削除処理
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_delete($id = null) {
		/* 除外処理 */
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}

		// メッセージ用にデータを取得
		$post = $this->TableContent->read(null, $id);

		/* 削除処理 */
		if ($this->TableContent->delete($id)) {
			$this->TableContent->saveDbLog('テーブル「' . $post['TableContent']['title'] . '」 を削除しました。');
			echo true;
		}

		exit();
	}

/**
 * [ADMIN] データコピー（AJAX）
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function admin_ajax_copy($id) {
		if (!$id) {
			$this->ajaxError(500, '無効な処理です。');
		}
		$result = $this->TableContent->copy($id);
		if ($result) {
			$this->set('data', $result);
		} else {
			$this->ajaxError(500, $this->TableContent->validationErrors);
		}
	}

}
