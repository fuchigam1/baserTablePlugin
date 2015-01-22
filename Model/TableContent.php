<?php

/**
 * テーブルコンテンツモデル
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
 * テーブルコンテンツモデル
 *
 * @package Table.Model
 */
class TableContent extends TableAppModel {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableContent';

/**
 * behaviors
 *
 * @var array
 * @access public
 */
	public $actsAs = array('BcContentsManager', 'BcPluginContent', 'BcCache');

/**
 * hasMany
 *
 * @var array
 * @access public
 */
	public $hasMany = array('TablePost' =>
		array('className' => 'Table.TablePost',
			'order' => 'id DESC',
			'limit' => 10,
			'foreignKey' => 'table_content_id',
			'dependent' => true,
			'exclusive' => false,
			'finderQuery' => ''),
		'TableCategory' =>
		array('className' => 'Table.TableCategory',
			'order' => 'id',
			'limit' => 10,
			'foreignKey' => 'table_content_id',
			'dependent' => true,
			'exclusive' => false,
			'finderQuery' => ''));

/**
 * validate
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array(
			array('rule' => array('halfText'),
				'message' => 'テーブルアカウント名は半角のみ入力してください。',
				'allowEmpty' => false),
			array('rule' => array('notInList', array('table')),
				'message' => 'テーブルアカウント名に「table」は利用できません。'),
			array('rule' => array('isUnique'),
				'message' => '入力されたテーブルアカウント名は既に使用されています。'),
			array('rule' => array('maxLength', 100),
				'message' => 'テーブルアカウント名は100文字以内で入力してください。')
		),
		'title' => array(
			array('rule' => array('notEmpty'),
				'message' => 'テーブルタイトルを入力してください。'),
			array('rule' => array('maxLength', 255),
				'message' => 'テーブルタイトルは255文字以内で入力してください。')
		),
		'layout' => array(
			array('rule' => 'halfText',
				'message' => 'レイアウトテンプレート名は半角で入力してください。',
				'allowEmpty' => false),
			array('rule' => array('maxLength', 20),
				'message' => 'レイアウトテンプレート名は20文字以内で入力してください。')
		),
		'template' => array(
			array('rule' => 'halfText',
				'message' => 'コンテンツテンプレート名は半角で入力してください。',
				'allowEmpty' => false),
			array('rule' => array('maxLength', 20),
				'message' => 'レイアウトテンプレート名は20文字以内で入力してください。')
		),
		'list_count' => array(array('rule' => 'halfText',
				'message' => "一覧表示件数は半角で入力してください。",
				'allowEmpty' => false)
		),
		'list_direction' => array(array('rule' => array('notEmpty'),
				'message' => "一覧に表示する順番を指定してください。")
		),
		'eye_catch_size' => array(array(
				'rule' => array('checkEyeCatchSize'),
				'message' => 'アイキャッチ画像のサイズが不正です。'
			))
	);

/**
 * アイキャッチ画像サイズバリデーション
 *
 * @return boolean
 */
	public function checkEyeCatchSize() {
		$data = $this->constructEyeCatchSize($this->data);
		if (empty($data['TableContent']['eye_catch_size_thumb_width']) ||
			empty($data['TableContent']['eye_catch_size_thumb_height']) ||
			empty($data['TableContent']['eye_catch_size_mobile_thumb_width']) ||
			empty($data['TableContent']['eye_catch_size_mobile_thumb_height'])) {
			return false;
		}

		return true;
	}

/**
 * 英数チェック
 *
 * @param string $check チェック対象文字列
 * @return boolean
 * @access public
 */
	public function alphaNumeric($check) {
		if (preg_match("/^[a-z0-9]+$/", $check[key($check)])) {
			return true;
		} else {
			return false;
		}
	}

/**
 * コントロールソースを取得する
 *
 * @param string フィールド名
 * @return array コントロールソース
 * @access public
 */
	public function getControlSource($field = null, $options = array()) {
		$controlSources['id'] = $this->find('list');

		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}

/**
 * afterSave
 *
 * @return boolean
 * @access public
 */
	public function afterSave($created, $options = array()) {
		if (empty($this->data['TableContent']['id'])) {
			$this->data['TableContent']['id'] = $this->getInsertID();
		}

		// 検索用テーブルへの登録・削除
		if (!$this->data['TableContent']['exclude_search'] && $this->data['TableContent']['status']) {
			$this->saveContent($this->createContent($this->data));
		} else {

			$this->deleteContent($this->data['TableContent']['id']);
		}
	}

/**
 * beforeDelete
 *
 * @return	boolean
 * @access	public
 */
	public function beforeDelete($cascade = true) {
		return $this->deleteContent($this->id);
	}

/**
 * 検索用データを生成する
 *
 * @param array $data
 * @return array
 * @access public
 */
	public function createContent($data) {
		if (isset($data['TableContent'])) {
			$data = $data['TableContent'];
		}

		$_data = array();
		$_data['Content']['type'] = 'テーブル';
		$_data['Content']['model_id'] = $this->id;
		$_data['Content']['category'] = '';
		$_data['Content']['title'] = $data['title'];
		$_data['Content']['detail'] = $data['description'];
		$_data['Content']['url'] = '/' . $data['name'] . '/index';
		$_data['Content']['status'] = true;

		return $_data;
	}

/**
 * ユーザーグループデータをコピーする
 *
 * @param int $id
 * @param array $data
 * @return mixed TableContent Or false
 */
	public function copy($id, $data = null) {
		if ($id) {
			$data = $this->find('first', array('conditions' => array('TableContent.id' => $id), 'recursive' => -1));
		}
		$data['TableContent']['name'] .= '_copy';
		$data['TableContent']['title'] .= '_copy';
		$data['TableContent']['status'] = false;
		unset($data['TableContent']['id']);
		$this->create($data);
		$result = $this->save();
		if ($result) {
			$result['TableContent']['id'] = $this->getInsertID();
			return $result;
		} else {
			if (isset($this->validationErrors['name'])) {
				return $this->copy(null, $data);
			} else {
				return false;
			}
		}
	}

/**
 * フォームの初期値を取得する
 *
 * @return void
 * @access protected
 */
	public function getDefaultValue() {
		$data['TableContent']['comment_use'] = true;
		$data['TableContent']['comment_approve'] = false;
		$data['TableContent']['layout'] = 'default';
		$data['TableContent']['template'] = 'default';
		$data['TableContent']['list_count'] = 10;
		$data['TableContent']['feed_count'] = 10;
		$data['TableContent']['auth_captcha'] = 1;
		$data['TableContent']['tag_use'] = false;
		$data['TableContent']['status'] = false;
		$data['TableContent']['eye_catch_size_thumb_width'] = 600;
		$data['TableContent']['eye_catch_size_thumb_height'] = 600;
		$data['TableContent']['eye_catch_size_mobile_thumb_width'] = 150;
		$data['TableContent']['eye_catch_size_mobile_thumb_height'] = 150;
		$data['TableContent']['use_content'] = true;

		return $data;
	}

/**
 * アイキャッチサイズフィールドの値をDB用に変換する
 *
 * @param array $data
 * @return array
 */
	public function deconstructEyeCatchSize($data) {
		$data['TableContent']['eye_catch_size'] = BcUtil::serialize(array(
			'thumb_width' => $data['TableContent']['eye_catch_size_thumb_width'],
			'thumb_height' => $data['TableContent']['eye_catch_size_thumb_height'],
			'mobile_thumb_width' => $data['TableContent']['eye_catch_size_mobile_thumb_width'],
			'mobile_thumb_height' => $data['TableContent']['eye_catch_size_mobile_thumb_height'],
		));
		unset($data['TableContent']['eye_catch_size_thumb_width']);
		unset($data['TableContent']['eye_catch_size_thumb_height']);
		unset($data['TableContent']['eye_catch_size_mobile_thumb_width']);
		unset($data['TableContent']['eye_catch_size_mobile_thumb_height']);

		return $data;
	}

/**
 * アイキャッチサイズフィールドの値をフォーム用に変換する
 *
 * @param array $data
 * @return array
 */
	public function constructEyeCatchSize($data) {
		$eyeCatchSize = BcUtil::unserialize($data['TableContent']['eye_catch_size']);
		$data['TableContent']['eye_catch_size_thumb_width'] = $eyeCatchSize['thumb_width'];
		$data['TableContent']['eye_catch_size_thumb_height'] = $eyeCatchSize['thumb_height'];
		$data['TableContent']['eye_catch_size_mobile_thumb_width'] = $eyeCatchSize['mobile_thumb_width'];
		$data['TableContent']['eye_catch_size_mobile_thumb_height'] = $eyeCatchSize['mobile_thumb_height'];
		return $data;
	}

}
