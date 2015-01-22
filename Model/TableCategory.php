<?php

/**
 * テーブルカテゴリモデル
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
 * テーブルカテゴリモデル
 *
 * @package Table.Model
 */
class TableCategory extends TableAppModel {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TableCategory';

/**
 * バリデーション設定
 *
 * @var array
 * @access public
 */
	public $validationParams = array();

/**
 * actsAs
 *
 * @var array
 * @access public
 */
	public $actsAs = array('Tree', 'BcCache');

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
			'foreignKey' => 'table_category_id',
			'dependent' => false,
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
			array('rule' => array('notEmpty'),
				'message' => "テーブルカテゴリ名を入力してください。",
				'required' => true),
			array('rule' => 'halfText',
				'message' => 'テーブルカテゴリ名は半角のみで入力してください。'),
			array('rule' => array('duplicateTableCategory'),
				'message' => '入力されたテーブルカテゴリは既に登録されています。'),
			array('rule' => array('maxLength', 255),
				'message' => 'テーブルカテゴリ名は255文字以内で入力してください。')
		),
		'title' => array(
			array('rule' => array('notEmpty'),
				'message' => "テーブルカテゴリタイトルを入力してください。",
				'required' => true),
			array('rule' => array('maxLength', 255),
				'message' => 'テーブルカテゴリ名は255文字以内で入力してください。')
		)
	);

/**
 * コントロールソースを取得する
 *
 * @param string フィールド名
 * @return array コントロールソース
 * @access public
 */
	public function getControlSource($field, $options = array()) {
		switch ($field) {
			case 'parent_id':
				if (!isset($options['tableContentId'])) {
					return false;
				}
				$conditions = array();
				if (isset($options['conditions'])) {
					$conditions = $options['conditions'];
				}
				$conditions['TableCategory.table_content_id'] = $options['tableContentId'];
				if (!empty($options['excludeParentId'])) {
					$children = $this->children($options['excludeParentId']);
					$excludeIds = array($options['excludeParentId']);
					foreach ($children as $child) {
						$excludeIds[] = $child['TableCategory']['id'];
					}
					$conditions['NOT']['TableCategory.id'] = $excludeIds;
				}

				if (isset($options['ownerId'])) {
					$ownerIdConditions = array(
						array('TableCategory.owner_id' => null),
						array('TableCategory.owner_id' => $options['ownerId']),
					);
					if (isset($conditions['OR'])) {
						$conditions['OR'] = am($conditions['OR'], $ownerIdConditions);
					} else {
						$conditions['OR'] = $ownerIdConditions;
					}
				}

				$parents = $this->generateTreeList($conditions);
				$controlSources['parent_id'] = array();
				foreach ($parents as $key => $parent) {
					if (preg_match("/^([_]+)/i", $parent, $matches)) {
						$parent = preg_replace("/^[_]+/i", '', $parent);
						$prefix = str_replace('_', '&nbsp&nbsp&nbsp', $matches[1]);
						$parent = $prefix . '└' . $parent;
					}
					$controlSources['parent_id'][$key] = $parent;
				}
				break;
			case 'owner_id':
				$UserGroup = ClassRegistry::init('UserGroup');
				$controlSources['owner_id'] = $UserGroup->find('list', array('fields' => array('id', 'title'), 'recursive' => -1));
				break;
		}

		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}

/**
 * 同じニックネームのカテゴリがないかチェックする
 * 同じテーブルコンテンツが条件
 *
 * @param array $check
 * @return boolean
 * @access public
 */
	public function duplicateTableCategory($check) {
		$conditions = array('TableCategory.' . key($check) => $check[key($check)],
			'TableCategory.table_content_id' => $this->validationParams['tableContentId']);
		if ($this->exists()) {
			$conditions['NOT'] = array('TableCategory.id' => $this->id);
		}
		$ret = $this->find('first', array('conditions' => $conditions));
		if ($ret) {
			return false;
		} else {
			return true;
		}
	}

/**
 * 関連する記事データをカテゴリ無所属に変更し保存する
 *
 * @param boolean $cascade
 * @return boolean
 * @access public
 */
	public function beforeDelete($cascade = true) {
		parent::beforeDelete($cascade);
		$ret = true;
		if (!empty($this->data['TableCategory']['id'])) {
			$id = $this->data['TableCategory']['id'];
			$this->TablePost->unBindModel(array('belongsTo' => array('TableCategory')));
			$datas = $this->TablePost->find('all', array('conditions' => array('TablePost.table_category_id' => $id)));
			if ($datas) {
				foreach ($datas as $data) {
					$data['TablePost']['table_category_id'] = '';
					$this->TablePost->set($data);
					if (!$this->TablePost->save()) {
						$ret = false;
					}
				}
			}
		}
		return $ret;
	}

/**
 * カテゴリリストを取得する
 *
 * @param int $id
 * @param boolean $count
 * @return array
 * @access public
 */
	public function getCategoryList($tableContentId, $options) {
		$options = array_merge(array(
			'depth' => 1,
			'type' => null,
			'order' => 'TableCategory.id',
			'limit' => false,
			'viewCount' => false,
			'fields' => array('id', 'name', 'title')
			), $options);
		$datas = array();

		extract($options);
		if (!$type) {
			$datas = $this->_getCategoryList($tableContentId, null, $viewCount, $depth);
		} elseif ($type == 'year') {
			$options = array(
				'category' => true,
				'limit' => $limit,
				'viewCount' => $viewCount,
				'type' => 'year'
			);
			$_datas = $this->TablePost->getPostedDates($tableContentId, $options);
			$datas = array();
			foreach ($_datas as $data) {
				if ($viewCount) {
					$data['TableCategory']['count'] = $data['count'];
				}
				$datas[$data['year']][] = array('TableCategory' => $data['TableCategory']);
			}
		}

		return $datas;
	}

/**
 * カテゴリリストを取得する（再帰処理）
 *
 * @param int $tableContentId
 * @param int $id
 * @param int $viewCount
 * @param int $depth
 * @param int $current
 * @param array $fields
 * @return array
 */
	protected function _getCategoryList($tableContentId, $id = null, $viewCount = false, $depth = 1, $current = 1, $fields = array()) {
		$datas = $this->find('all', array(
			'conditions' => array('TableCategory.table_content_id' => $tableContentId, 'TableCategory.parent_id' => $id),
			'fields' => $fields,
			'recursive' => -1));
		if ($datas) {
			foreach ($datas as $key => $data) {
				if ($viewCount) {
					$datas[$key]['TableCategory']['count'] = $this->TablePost->find('count', array(
						'conditions' =>
						am(
							array('TablePost.table_category_id' => $data['TableCategory']['id']), $this->TablePost->getConditionAllowPublish()
						),
						'cache' => false
					));
				}
				if ($current < $depth) {
					$children = $this->_getCategoryList($tableContentId, $data['TableCategory']['id'], $viewCount, $depth, $current + 1);
					if ($children) {
						$datas[$key]['TableCategory']['children'] = $children;
					}
				}
			}
		}
		return $datas;
	}

/**
 * 新しいカテゴリが追加できる状態かチェックする
 *
 * @param int $userGroupId ユーザーグループID
 * @param bool $rootEditable ドキュメントルートの書き込み権限の有無
 * @return bool
 */
	public function checkNewCategoryAddable($userGroupId, $rootEditable) {
		$ownerCats = $this->find('count', array(
			'conditions' => array(
				'OR' => array(
					array('TableCategory.owner_id' => null),
					array('TableCategory.owner_id' => $userGroupId)
				)
		)));

		if (ClassRegistry::isKeySet('Permission')) {
			$Permission = ClassRegistry::getObject('Permission');
		} else {
			$Permission = ClassRegistry::init('Permission');
		}

		$ajaxAddUrl = preg_replace('|^/index.php|', '', Router::url(array('plugin' => 'table', 'controller' => 'table_categories', 'action' => 'ajax_add')));
		$hasUrlPermission = $Permission->check($ajaxAddUrl, $userGroupId);

		if (($ownerCats || $rootEditable) && $hasUrlPermission) {
			return true;
		}

		return false;
	}

}
