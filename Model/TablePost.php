<?php

/**
 * 記事モデル
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
 * 記事モデル
 *
 * @package Table.Model
 */
class TablePost extends TableAppModel {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'TablePost';

/**
 * 検索テーブルへの保存可否
 *
 * @var boolean
 * @access public
 */
	public $contentSaving = true;

/**
 * ビヘイビア
 *
 * @var array
 * @access public
 */
	public $actsAs = array(
		'BcContentsManager',
		'BcCache',
		'BcUpload' => array(
			'subdirDateFormat' => 'Y/m/',
			'fields' => array(
				'eye_catch' => array(
					'type' => 'image',
					'namefield' => 'no',
					'nameformat' => '%08d'
				)
			)
		)
	);

/**
 * belongsTo
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'TableCategory' => array(
			'className' => 'Table.TableCategory',
			'foreignKey' => 'table_category_id'),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'),
		'TableContent' => array(
			'className' => 'Table.TableContent',
			'foreignKey' => 'table_content_id')
	);

/**
 * hasMany
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'TableComment' => array(
			'className' => 'Table.TableComment',
			'order' => 'created',
			'foreignKey' => 'table_post_id',
			'dependent' => true,
			'exclusive' => false,
			'finderQuery' => '')
	);

/**
 * HABTM
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'TableTag' => array(
			'className' => 'Table.TableTag',
			'joinTable' => 'table_posts_table_tags',
			'foreignKey' => 'table_post_id',
			'associationForeignKey' => 'table_tag_id',
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
			array('rule' => array('notEmpty'),
				'message' => 'タイトルを入力してください。',
				'required' => true),
			array('rule' => array('maxLength', 255),
				'message' => 'タイトルは255文字以内で入力してください。')
		),
		'posts_date' => array(
			array('rule' => array('notEmpty'),
				'message' => '投稿日を入力してください。',
				'required' => true),
			array('rule' => array('checkDate'),
				'message' => '投稿日の形式が不正です。')
		),
		'user_id' => array(
			array('rule' => array('notEmpty'),
				'message' => '投稿者を選択してください。')
		)
	);

/**
 * アップロードビヘイビアの設定
 *
 * @param	int		$id
 * @param	string	$table
 * @param	string	$ds
 */
	public function setupUpload($id) {
		$sizes = array('thumb', 'mobile_thumb');
		$data = $this->TableContent->find('first', array('conditions' => array('TableContent.id' => $id)));
		$data = $this->TableContent->constructEyeCatchSize($data);
		$data = $data['TableContent'];

		$imagecopy = array();

		foreach ($sizes as $size) {
			if (!isset($data['eye_catch_size_' . $size . '_width']) || !isset($data['eye_catch_size_' . $size . '_height'])) {
				continue;
			}
			$imagecopy[$size] = array('suffix' => '__' . $size);
			$imagecopy[$size]['width'] = $data['eye_catch_size_' . $size . '_width'];
			$imagecopy[$size]['height'] = $data['eye_catch_size_' . $size . '_height'];
		}

		$settings = $this->Behaviors->BcUpload->settings['TablePost'];
		if (empty($settings['saveDir']) || !preg_match('/^' . preg_quote("table" . DS . $data['name'], '/') . '\//', $settings['saveDir'])) {
			$settings['saveDir'] = "table" . DS . $data['name'] . DS . "table_posts";
		}

		$settings['fields']['eye_catch']['imagecopy'] = $imagecopy;
		$this->Behaviors->attach('BcUpload', $settings);
	}

/**
 * 初期値を取得する
 *
 * @return array $authUser 初期値データ
 * @access public
 */
	public function getDefaultValue($authUser) {
		$data[$this->name]['user_id'] = $authUser['id'];
		$data[$this->name]['posts_date'] = date('Y/m/d H:i:s');
		$data[$this->name]['status'] = 0;
		return $data;
	}

/**
 * テーブルの月別一覧を取得する
 *
 * @param array $tableContentId
 * @param array $options
 * @return array 月別リストデータ
 * @access public
 */
	public function getPostedDates($tableContentId, $options) {
		$options = array_merge(array(
			'category' => false,
			'limit' => false,
			'viewCount' => false,
			'type' => 'month' // month Or year
			), $options);

		extract($options);
		$conditions = array('TablePost.table_content_id' => $tableContentId);
		$conditions = am($conditions, $this->getConditionAllowPublish());
		// TODO CSVDBではGROUP BYが実装されていない為、取り急ぎPHPで処理
		/* $dates = $this->find('all',array('fields'=>array('YEAR(posts_date) as year','MONTH(posts_date) as month','COUNT(id)' as count),
		  $conditions,
		  'group'=>array('YEAR(posts_date)','MONTH(posts_date)')))); */

		if ($category) {
			$recursive = 1;
			$this->unbindModel(array(
				'belongsTo' => array('User', 'TableContent'),
				'hasAndBelongsToMany' => array('TableTag')
			));
		} else {
			$recursive = -1;
		}

		// 毎秒抽出条件が違うのでキャッシュしない
		$posts = $this->find('all', array(
			'conditions' => $conditions,
			'order' => 'TablePost.posts_date DESC',
			'recursive' => $recursive,
			'cache' => false
		));

		$dates = array();
		$counter = 0;

		foreach ($posts as $post) {

			$exists = false;
			$_date = array();
			$year = date('Y', strtotime($post['TablePost']['posts_date']));
			$month = date('m', strtotime($post['TablePost']['posts_date']));
			$categoryId = $post['TablePost']['table_category_id'];

			foreach ($dates as $key => $date) {

				if (!$category || $date['TableCategory']['id'] == $categoryId) {
					if ($type == 'year' && $date['year'] == $year) {
						$exists = true;
					}
					if ($type == 'month' && $date['year'] == $year && $date['month'] == $month) {
						$exists = true;
					}
				}

				if ($exists) {
					if ($viewCount) {
						$dates[$key]['count'] ++;
					}
					break;
				}
			}

			if (!$exists) {
				if ($type == 'year') {
					$_date['year'] = $year;
				} elseif ($type == 'month') {
					$_date['year'] = $year;
					$_date['month'] = $month;
				}
				if ($category) {
					$_date['TableCategory']['id'] = $categoryId;
					$_date['TableCategory']['name'] = $post['TableCategory']['name'];
					$_date['TableCategory']['title'] = $post['TableCategory']['title'];
				}
				if ($viewCount) {
					$_date['count'] = 1;
				}
				$dates[] = $_date;
				$counter++;
			}

			if ($limit !== false && $limit <= $counter) {
				break;
			}
		}

		return $dates;
	}

/**
 * カレンダー用に指定した月で記事の投稿がある日付のリストを取得する
 *
 * @param int $contentId
 * @param int $year
 * @param int $month
 * @return array
 * @access public
 */
	public function getEntryDates($contentId, $year, $month) {
		$entryDates = $this->find('all', array(
			'fields' => array('TablePost.posts_date'),
			'conditions' => $this->_getEntryDatesConditions($contentId, $year, $month),
			'recursive' => -1,
			'cache' => false
		));
		$entryDates = Hash::extract($entryDates, '{n}.TablePost.posts_date');
		foreach ($entryDates as $key => $entryDate) {
			$entryDates[$key] = date('Y-m-d', strtotime($entryDate));
		}
		return $entryDates;
	}

/**
 * 投稿者の一覧を取得する
 *
 * @param int $tableContentId
 * @param array $options
 * @return array
 */
	public function getAuthors($tableContentId, $options) {
		$options = array_merge(array(
			'viewCount' => false
			), $options);
		extract($options);

		$users = $this->User->find('all', array('recursive' => -1, array('order' => 'User.id'), 'fields' => array(
				'User.id', 'User.name', 'User.real_name_1', 'User.real_name_2', 'User.nickname'
		)));
		$availableUsers = array();
		foreach ($users as $key => $user) {
			$count = $this->find('count', array('conditions' => array_merge(array(
					'TablePost.user_id' => $user['User']['id'],
					'TablePost.table_content_id' => $tableContentId
					), $this->getConditionAllowPublish())));
			if ($count) {
				if ($viewCount) {
					$user['count'] = $count;
				}
				$availableUsers[] = $user;
			}
		}
		return $availableUsers;
	}

/**
 * 指定した月の記事が存在するかチェックする
 *
 * @param	int $contentId
 * @param	int $year
 * @param	int $month
 * @return	boolean
 */
	public function existsEntry($contentId, $year, $month) {
		if ($this->find('first', array(
				'fields' => array('TablePost.id'),
				'conditions' => $this->_getEntryDatesConditions($contentId, $year, $month),
				'recursive' => -1,
				'cache' => false
			))) {
			return true;
		} else {
			return false;
		}
	}

/**
 * 年月を指定した検索条件を生成
 * データベースごとに構文が違う
 *
 * @param int $contentId
 * @param int $year
 * @param int $month
 * @return string
 * @access private
 */
	protected function _getEntryDatesConditions($contentId, $year, $month) {
		$dbConfig = new DATABASE_CONFIG();
		$datasource = $dbConfig->plugin['datasource'];

		switch ($datasource) {
			case 'Database/BcMysql':
			case 'Database/BcCsv':
				if (!empty($year)) {
					$conditions["YEAR(`TablePost`.`posts_date`)"] = $year;
				} else {
					$conditions["YEAR(`TablePost`.`posts_date`)"] = date('Y');
				}
				if (!empty($month)) {
					$conditions["MONTH(`TablePost`.`posts_date`)"] = $month;
				} else {
					$conditions["MONTH(`TablePost`.`posts_date`)"] = date('m');
				}
				break;

			case 'Database/BcPostgres':
				if (!empty($year)) {
					$conditions["date_part('year', \"TablePost\".\"posts_date\") ="] = $year;
				} else {
					$conditions["date_part('year', \"TablePost\".\"posts_date\") ="] = date('Y');
				}
				if (!empty($month)) {
					$conditions["date_part('month', \"TablePost\".\"posts_date\") ="] = $month;
				} else {
					$conditions["date_part('month', \"TablePost\".\"posts_date\") ="] = date('m');
				}
				break;

			case 'Database/BcSqlite':
				if (!empty($year)) {
					$conditions["strftime('%Y',TablePost.posts_date)"] = $year;
				} else {
					$conditions["strftime('%Y',TablePost.posts_date)"] = date('Y');
				}
				if (!empty($month)) {
					$conditions["strftime('%m',TablePost.posts_date)"] = sprintf('%02d', $month);
				} else {
					$conditions["strftime('%m',TablePost.posts_date)"] = date('m');
				}
				break;
		}

		$conditions = am($conditions, array('TablePost.table_content_id' => $contentId), $this->getConditionAllowPublish());
		return $conditions;
	}

/**
 * コントロールソースを取得する
 *
 * @param string $field フィールド名
 * @param	array	$options
 * @return	array	コントロールソース
 * @access	public
 */
	public function getControlSource($field, $options = array()) {
		switch ($field) {
			case 'table_category_id':

				extract($options);
				$catOption = array('tableContentId' => $tableContentId);
				$isSuperAdmin = false;

				if (!empty($userGroupId)) {

					if (!isset($tableCategoryId)) {
						$tableCategoryId = '';
					}

					if ($userGroupId == 1) {
						$isSuperAdmin = true;
					}

					// 現在のページが編集不可の場合、現在表示しているカテゴリも取得する
					if (!$postEditable && $tableCategoryId) {
						$catOption['conditions'] = array('OR' => array('TableCategory.id' => $tableCategoryId));
					}

					// super admin でない場合は、管理許可のあるカテゴリのみ取得
					if (!$isSuperAdmin) {
						$catOption['ownerId'] = $userGroupId;
					}

					if ($postEditable && !$rootEditable && !$isSuperAdmin) {
						unset($empty);
					}
				}

				$categories = $this->TableCategory->getControlSource('parent_id', $catOption);

				// 「指定しない」追加
				if (isset($empty)) {
					if ($categories) {
						$categories = array('' => $empty) + $categories;
					} else {
						$categories = array('' => $empty);
					}
				}

				$controlSources['table_category_id'] = $categories;

				break;
			case 'user_id':
				$controlSources['user_id'] = $this->User->getUserList($options);
				break;
			case 'table_tag_id':
				$controlSources['table_tag_id'] = $this->TableTag->find('list');
				break;
		}
		if (isset($controlSources[$field])) {
			return $controlSources[$field];
		} else {
			return false;
		}
	}

/**
 * 公開状態を取得する
 *
 * @param array データリスト
 * @return boolean 公開状態
 * @access public
 */
	public function allowPublish($data) {
		if (isset($data['TablePost'])) {
			$data = $data['TablePost'];
		}

		$allowPublish = (int)$data['status'];

		if ($data['publish_begin'] == '0000-00-00 00:00:00') {
			$data['publish_begin'] = null;
		}
		if ($data['publish_end'] == '0000-00-00 00:00:00') {
			$data['publish_end'] = null;
		}

		// 期限を設定している場合に条件に該当しない場合は強制的に非公開とする
		if (($data['publish_begin'] && $data['publish_begin'] >= date('Y-m-d H:i:s')) ||
			($data['publish_end'] && $data['publish_end'] <= date('Y-m-d H:i:s'))) {
			$allowPublish = false;
		}

		return $allowPublish;
	}

/**
 * 公開済の conditions を取得
 *
 * @return array
 * @access public
 */
	public function getConditionAllowPublish() {
		$conditions[$this->alias . '.status'] = true;
		$conditions[] = array('or' => array(array($this->alias . '.publish_begin <=' => date('Y-m-d H:i:s')),
				array($this->alias . '.publish_begin' => null),
				array($this->alias . '.publish_begin' => '0000-00-00 00:00:00')));
		$conditions[] = array('or' => array(array($this->alias . '.publish_end >=' => date('Y-m-d H:i:s')),
				array($this->alias . '.publish_end' => null),
				array($this->alias . '.publish_end' => '0000-00-00 00:00:00')));
		return $conditions;
	}

/**
 * 公開状態の記事を取得する
 *
 * @param array $options
 * @return array
 * @access public
 */
	public function getPublishes($options) {
		if (!empty($options['conditions'])) {
			$options['conditions'] = array_merge($this->getConditionAllowPublish(), $options['conditions']);
		} else {
			$options['conditions'] = $this->getConditionAllowPublish();
		}
		// 毎秒抽出条件が違うのでキャッシュしない
		$options['cache'] = false;
		$datas = $this->find('all', $options);
		return $datas;
	}

/**
 * afterSave
 *
 * @param boolean $created
 * @return boolean
 * @access public
 */
	public function afterSave($created, $options = array()) {
		// 検索用テーブルへの登録・削除
		if ($this->contentSaving && !$this->data['TablePost']['exclude_search']) {
			$this->saveContent($this->createContent($this->data));
		} else {

			if (!empty($this->data['TablePost']['id'])) {
				$this->deleteContent($this->data['TablePost']['id']);
			} elseif (!empty($this->id)) {
				$this->deleteContent($this->id);
			} else {
				$this->cakeError('Not found pk-value in TablePost.');
			}
		}
	}

/**
 * 検索用データを生成する
 *
 * @param array $data
 * @return array
 * @access public
 */
	public function createContent($data) {
		if (isset($data['TablePost'])) {
			$data = $data['TablePost'];
		}

		$_data = array();
		$_data['Content']['type'] = 'テーブル';
		$_data['Content']['model_id'] = $this->id;
		$_data['Content']['category'] = '';
		if (!empty($data['table_category_id'])) {
			$TableCategory = ClassRegistry::init('Table.TableCategory');
			$categoryPath = $TableCategory->getPath($data['table_category_id'], array('title'));
			if ($categoryPath) {
				$_data['Content']['category'] = $categoryPath[0]['TableCategory']['title'];
			}
		}
		$_data['Content']['title'] = $data['name'];
		$_data['Content']['detail'] = $data['content'] . ' ' . $data['detail'];
		$PluginContent = ClassRegistry::init('PluginContent');
		$_data['Content']['url'] = '/' . $PluginContent->field('name', array('PluginContent.content_id' => $data['table_content_id'], 'plugin' => 'table')) . '/archives/' . $data['no'];
		$_data['Content']['status'] = $this->allowPublish($data);

		return $_data;
	}

/**
 * beforeDelete
 *
 * @return boolean
 * @access public
 */
	public function beforeDelete($cascade = true) {
		return $this->deleteContent($this->id);
	}

/**
 * コピーする
 *
 * @param int $id
 * @param array $data
 * @return mixed page Or false
 */
	public function copy($id = null, $data = array()) {
		$data = array();
		if ($id) {
			$data = $this->find('first', array('conditions' => array('TablePost.id' => $id), 'recursive' => 1));
		}
		if (!empty($_SESSION['Auth']['User'])) {
			$data['TablePost']['user_id'] = $_SESSION['Auth']['User']['id'];
		}

		$data['TablePost']['name'] .= '_copy';
		$data['TablePost']['no'] = $this->getMax('no', array('TablePost.table_content_id' => $data['TablePost']['table_content_id'])) + 1;
		$data['TablePost']['status'] = '0'; // TODO intger の為 false では正常に保存できない（postgreSQLで確認）

		unset($data['TablePost']['id']);
		unset($data['TablePost']['created']);
		unset($data['TablePost']['modified']);

		// 一旦退避(afterSaveでリネームされてしまうのを避ける為）
		$eyeCatch = $data['TablePost']['eye_catch'];
		unset($data['TablePost']['eye_catch']);

		if (!empty($data['TableTag'])) {
			foreach ($data['TableTag'] as $key => $tag) {
				$data['TableTag'][$key] = $tag['id'];
			}
		}

		$this->create($data);
		$result = $this->save();

		if ($result) {
			if ($eyeCatch) {
				$data['TablePost']['id'] = $this->getLastInsertID();
				$data['TablePost']['eye_catch'] = $eyeCatch;
				$this->set($data);
				$this->renameToFieldBasename(true); // 内部でリネームされたデータが再セットされる
				$result = $this->save();
			}
			return $result;
		} else {
			if (isset($this->validationErrors['name'])) {
				return $this->copy(null, $data);
			} else {
				return false;
			}
		}
	}

}
