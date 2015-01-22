<?php

/**
 * テーブル記事コントローラー
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
App::uses('TableAppController', 'Table.Controller');

/**
 * テーブル記事コントローラー
 *
 * @package			Table.Controller
 */
class TableController extends TableAppController {

/**
 * クラス名
 *
 * @var string
 * @access public
 */
	public $name = 'Table';

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
	public $helpers = array('BcText', 'BcTime', 'BcFreeze', 'BcArray', 'Paginator', 'Table.Table', 'Cache');

/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth', 'Cookie', 'BcAuthConfigure', 'RequestHandler', 'BcEmail', 'Security');

/**
 * ぱんくずナビ
 *
 * @var array
 * @access public
 */
	public $crumbs = array();

/**
 * サブメニューエレメント
 *
 * @var array
 * @access public
 */
	public $subMenuElements = array();

/**
 * テーブルデータ
 *
 * @var array
 * @access public
 */
	public $tableContent = array();

/**
 * beforeFilter
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();

		/* 認証設定 */
		$this->BcAuth->allow(
			'index', 'mobile_index', 'smartphone_index', 'archives', 'mobile_archives', 'smartphone_archives', 'posts', 'mobile_posts', 'smartphone_posts', 'get_calendar', 'get_categories', 'get_posted_months', 'get_posted_years', 'get_recent_entries', 'get_authors'
		);

		$this->TableContent->recursive = -1;
		if ($this->contentId) {
			$this->tableContent = $this->TableContent->read(null, $this->contentId);
		} else {
			$this->tableContent = $this->TableContent->read(null, $this->params['pass'][0]);
		}

		$this->TablePost->setupUpload($this->tableContent['TableContent']['id']);

		$this->subMenuElements = array('default');
		$this->crumbs = array(array('name' => $this->tableContent['TableContent']['title'], 'url' => '/' . $this->tableContent['TableContent']['name'] . '/index'));

		// ページネーションのリンク対策
		// コンテンツ名を変更している際、以下の設定を行わないとプラグイン名がURLに付加されてしまう
		// Viewで $paginator->options = array('url' => $this->passedArgs) を行う事が前提
		if (!isset($this->request->params['admin'])) {
			$this->passedArgs['controller'] = $this->tableContent['TableContent']['name'];
			$this->passedArgs['plugin'] = $this->tableContent['TableContent']['name'];
			$this->passedArgs['action'] = $this->action;
		}

		// コメント送信用のトークンを出力する為にセキュリティコンポーネントを利用しているが、
		// 表示用のコントローラーなのでポストデータのチェックは必要ない
		if (Configure::read('debug') > 0) {
			$this->Security->validatePost = false;
			$this->Security->csrfCheck = false;
		} else {
			$this->Security->enabled = true;
			$this->Security->validatePost = false;
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

		if ($this->tableContent['TableContent']['widget_area']) {
			$this->set('widgetArea', $this->tableContent['TableContent']['widget_area']);
		}
	}

/**
 * [PUBLIC] テーブルを一覧表示する
 *
 * @return void
 * @access public
 */
	public function index() {
		if (!$this->tableContent['TableContent']['status']) {
			$this->notFound();
		}

		if ($this->RequestHandler->isRss()) {
			Configure::write('debug', 0);
			$this->set('channel', array(
				'title' => h($this->tableContent['TableContent']['title'] . '｜' . $this->siteConfigs['name']),
				'description' => h(strip_tags($this->tableContent['TableContent']['description']))
			));
			$this->layout = 'default';
			$template = 'index';
			$listCount = $this->tableContent['TableContent']['feed_count'];
		} else {
			$this->layout = $this->tableContent['TableContent']['layout'];
			$template = $this->tableContent['TableContent']['template'] . DS . 'index';
			$listCount = $this->tableContent['TableContent']['list_count'];
		}

		$datas = $this->_getTablePosts(array('listCount' => $listCount));
		$this->set('editLink', array('admin' => true, 'plugin' => 'table', 'controller' => 'table_contents', 'action' => 'edit', $this->tableContent['TableContent']['id']));
		$this->set('posts', $datas);
		$this->set('single', false);
		$this->pageTitle = $this->tableContent['TableContent']['title'];
		$this->crumbs = array();
		$this->render($template);
	}

/**
 * [MOBILE] テーブル記事を一覧表示する
 *
 * @return void
 * @access public
 */
	public function mobile_index() {
		$this->setAction('index');
	}

/**
 * [SMARTPHONE] テーブル記事を一覧表示する
 *
 * @return void
 * @access public
 */
	public function smartphone_index() {
		$this->setAction('index');
	}

/**
 * [PUBLIC] テーブルアーカイブを表示する
 *
 * @param mixed	table_post_id / type
 * @param mixed	table_post_id / ""
 * @return void
 * @access public
 */
	public function archives() {
		if (!$this->tableContent['TableContent']['status']) {
			$this->notFound();
		}

		// パラメーター処理
		$pass = $this->params['pass'];
		$type = $year = $month = $day = $id = '';
		$crumbs = $posts = array();
		$single = false;
		$posts = array();

		if ($pass[0] == 'category') {
			$type = 'category';
		} elseif ($pass[0] == 'author') {
			$type = 'author';
		} elseif ($pass[0] == 'tag') {
			$type = 'tag';
		} elseif ($pass[0] == 'date') {
			$type = 'date';
		}

		switch ($type) {

			/* カテゴリ一覧 */
			case 'category':

				$category = $pass[count($pass) - 1];
				if (empty($category)) {
					$this->notFound();
				}

				// ナビゲーションを設定
				$categoryId = $this->TableCategory->field('id', array(
					'TableCategory.table_content_id' => $this->contentId,
					'TableCategory.name' => $category
				));

				if (!$categoryId) {
					$this->notFound();
				}

				// 記事を取得
				$posts = $this->_getTablePosts(array('conditions' => array('category' => $category)));

				$tableCategories = $this->TableCategory->getPath($categoryId, array('name', 'title'));
				if (count($tableCategories) > 1) {
					foreach ($tableCategories as $key => $tableCategory) {
						if ($key < count($tableCategories) - 1) {
							$crumbs[] = array('name' => $tableCategory['TableCategory']['title'], 'url' => '/' . $this->tableContent['TableContent']['name'] . '/archives/category/' . $tableCategory['TableCategory']['name']);
						}
					}
				}
				$this->pageTitle = $tableCategories[count($tableCategories) - 1]['TableCategory']['title'];
				$template = $this->tableContent['TableContent']['template'] . DS . 'archives';

				$this->set('tableArchiveType', $type);

				break;

			case 'author':
				$author = h($pass[count($pass) - 1]);
				$posts = $this->_getTablePosts(array('conditions' => array('author' => $author)));
				$data = $this->TablePost->User->find('first', array('fields' => array('real_name_1', 'real_name_2', 'nickname'), 'conditions' => array('User.name' => $author)));
				App::uses('BcBaserHelper', 'View/Helper');
				$BcBaser = new BcBaserHelper(new View());
				$userName = $BcBaser->getUserName($data);
				$this->pageTitle = urldecode($userName);
				$template = $this->tableContent['TableContent']['template'] . DS . 'archives';
				$this->set('tableArchiveType', $type);
				break;

			/* タグ別記事一覧 */
			case 'tag':

				$tag = h($pass[count($pass) - 1]);
				if (empty($this->tableContent['TableContent']['tag_use']) || empty($tag)) {
					$this->notFound();
				}
				$posts = $this->_getTablePosts(array('conditions' => array('tag' => $tag)));
				$this->pageTitle = urldecode($tag);
				$template = $this->tableContent['TableContent']['template'] . DS . 'archives';

				$this->set('tableArchiveType', $type);

				break;

			/* 月別アーカイブ一覧 */
			case 'date':

				$year = h($pass[1]);
				$month = h(@$pass[2]);
				$day = h(@$pass[3]);
				if (!$year && !$month && !$day) {
					$this->notFound();
				}
				$posts = $this->_getTablePosts(array('conditions' => array('year' => $year, 'month' => $month, 'day' => $day)));
				$this->pageTitle = $year . '年';
				if ($month) {
					$this->pageTitle .= $month . '月';
				}
				if ($day) {
					$this->pageTitle .= $day . '日';
				}
				$template = $this->tableContent['TableContent']['template'] . DS . 'archives';

				if ($day) {
					$this->set('tableArchiveType', 'daily');
				} elseif ($month) {
					$this->set('tableArchiveType', 'monthly');
				} else {
					$this->set('tableArchiveType', 'yearly');
				}

				break;

			/* 単ページ */
			default:

				// プレビュー
				if ($this->preview) {

					$this->contentId = $pass[0];
					if (!empty($pass[1])) {
						$id = $pass[1];
					} elseif (empty($this->request->data['TablePost'])) {
						$this->notFound();
					}

					$post['TablePost'] = $this->request->data['TablePost'];

					if ($this->request->data['TablePost']['table_category_id']) {
						$tableCategory = $this->TablePost->TableCategory->find('first', array(
							'conditions' => array('TableCategory.id' => $this->request->data['TablePost']['table_category_id']),
							'recursive' => -1
						));
						$post['TableCategory'] = $tableCategory['TableCategory'];
					}

					if ($this->request->data['TablePost']['user_id']) {
						$author = $this->TablePost->User->find('first', array(
							'conditions' => array('User.id' => $this->request->data['TablePost']['user_id']),
							'recursive' => -1
						));
						$post['User'] = $author['User'];
					}

					if (!empty($this->request->data['TableTag']['TableTag'])) {
						$tags = $this->TablePost->TableTag->find('all', array(
							'conditions' => array('TableTag.id' => $this->request->data['TableTag']['TableTag']),
							'recursive' => -1
						));
						if ($tags) {
							$tags = Hash::extract($tags, '{n}.TableTag');
							$post['TableTag'] = $tags;
						}
					}
				} else {

					if (!empty($pass[0])) {
						$id = $pass[0];
					} else {
						$this->notFound();
					}
					// コメント送信
					if (isset($this->request->data['TableComment'])) {
						$this->add_comment($id);
					}

					$_posts = $this->_getTablePosts(array('conditions' => array('id' => $id)));
					if (!empty($_posts[0])) {
						$post = $_posts[0];
					} else {
						$this->notFound();
					}

					$user = $this->BcAuth->user();
					if (empty($this->params['admin']) && !empty($user) && !Configure::read('BcRequest.agent')) {
						$this->set('editLink', array('admin' => true, 'plugin' => 'table', 'controller' => 'table_posts', 'action' => 'edit', $post['TablePost']['table_content_id'], $post['TablePost']['id']));
					}
				}

				// ナビゲーションを設定
				if (!empty($post['TablePost']['table_category_id'])) {
					$tableCategories = $this->TableCategory->getPath($post['TablePost']['table_category_id'], array('name', 'title'));
					if ($tableCategories) {
						foreach ($tableCategories as $tableCategory) {
							$crumbs[] = array('name' => $tableCategory['TableCategory']['title'], 'url' => '/' . $this->tableContent['TableContent']['name'] . '/archives/category/' . $tableCategory['TableCategory']['name']);
						}
					}
				}
				$this->pageTitle = $post['TablePost']['name'];
				$single = true;
				$template = $this->tableContent['TableContent']['template'] . DS . 'single';
				if ($this->preview) {
					$this->tableContent['TableContent']['comment_use'] = false;
				}
				$this->set('post', $post);
		}

		// 表示設定
		$this->crumbs = array_merge($this->crumbs, $crumbs);
		$this->set('single', $single);
		$this->set('posts', $posts);
		$this->set('year', $year);
		$this->set('month', $month);
		$this->layout = $this->tableContent['TableContent']['layout'];
		$this->render($template);
	}

/**
 * コメントを送信する
 *
 * @param int $id
 * @return void
 * @access public
 */
	public function add_comment($id) {
		// table_post_idを取得
		$conditions = array(
			'TablePost.no' => $id,
			'TablePost.table_content_id' => $this->contentId
		);
		$conditions = am($conditions, $this->TablePost->getConditionAllowPublish());

		// 毎秒抽出条件が違うのでキャッシュしない
		$data = $this->TablePost->find('first', array(
			'conditions' => $conditions,
			'fields' => array('TablePost.id'),
			'cache' => false,
			'recursive' => -1
		));

		if (empty($data['TablePost']['id'])) {
			$this->notFound();
		} else {
			$postId = $data['TablePost']['id'];
		}

		if ($this->TablePost->TableComment->add($this->request->data, $this->contentId, $postId, $this->tableContent['TableContent']['comment_approve'])) {

			$this->_sendCommentAdmin($postId, $this->request->data);
			// コメント承認機能を利用していない場合は、公開されているコメント投稿者にアラートを送信
			if (!$this->tableContent['TableContent']['comment_approve']) {
				$this->_sendCommentContributor($postId, $this->request->data);
			}
			if ($this->tableContent['TableContent']['comment_approve']) {
				$commentMessage = '送信が完了しました。送信された内容は確認後公開させて頂きます。';
			} else {
				$commentMessage = 'コメントの送信が完了しました。';
			}
			$this->request->data = null;
		} else {

			$commentMessage = 'コメントの送信に失敗しました。';
		}

		$this->set('commentMessage', $commentMessage);
	}

/**
 * テーブル記事を取得する
 *
 * @param array $options
 * @return array
 * @access protected
 */
	protected function _getTablePosts($options = array()) {
		// listCountの処理 （num が優先）
		// TODO num に統一する
		if (!empty($options['listCount'])) {
			if (empty($options['num'])) {
				$options['num'] = $options['listCount'];
			}
		}

		// named の 処理
		$named = array();
		if (!empty($this->request->params['named'])) {
			$named = $this->request->params['named'];
		}
		if (!empty($named['direction'])) {
			$options['direction'] = $named['direction'];
			unset($named['direction']);
		}
		if (!empty($named['num'])) {
			$options['num'] = $named['num'];
			unset($named['num']);
		}
		if (!empty($named['page'])) {
			$options['page'] = $named['page'];
			unset($named['page']);
		}
		if (!empty($named['sort'])) {
			$options['sort'] = $named['sort'];
			unset($named['sort']);
		}

		$_conditions = array();
		if (!empty($this->request->params['named'])) {
			if (!empty($options['conditions'])) {
				$_conditions = array_merge($options['conditions'], $this->request->params['named']);
			} else {
				$_conditions = $this->request->params['named'];
			}
		} elseif (!empty($options['conditions'])) {
			$_conditions = $options['conditions'];
		}
		unset($options['conditions']);

		$_conditions = array_merge(array(
			'category' => null,
			'tag' => null,
			'year' => null,
			'month' => null,
			'day' => null,
			'id' => null,
			'keyword' => null,
			'author' => null
			), $_conditions);

		$options = array_merge(array(
			'direction' => $this->tableContent['TableContent']['list_direction'],
			'num' => $this->tableContent['TableContent']['list_count'],
			'page' => 1,
			'sort' => 'posts_date'
			), $options);

		extract($options);

		$expects = array('TableContent', 'TableCategory', 'User', 'TableTag');
		$conditions = array('TablePost.table_content_id' => $this->contentId);

		// カテゴリ条件
		if ($_conditions['category']) {
			$category = $_conditions['category'];
			$categoryId = $this->TableCategory->field('id', array(
				'TableCategory.table_content_id' => $this->contentId,
				'TableCategory.name' => $category
			));

			if ($categoryId === false) {
				$categoryIds = '';
			} else {
				$categoryIds = array(0 => $categoryId);
				// 指定したカテゴリ名にぶら下がる子カテゴリを取得
				$catChildren = $this->TableCategory->children($categoryId);
				if ($catChildren) {
					$catChildren = Hash::extract($catChildren, '{n}.TableCategory.id');
					$categoryIds = am($categoryIds, $catChildren);
				}
			}
			$conditions['TablePost.table_category_id'] = $categoryIds;
		}

		// タグ条件
		if ($_conditions['tag']) {

			$tag = $_conditions['tag'];
			if (!is_array($tag)) {
				$tag = array($tag);
			}

			foreach ($tag as $key => $value) {
				$tag[$key] = urldecode($value);
			}

			$tags = $this->TablePost->TableTag->find('all', array(
				'conditions' => array('TableTag.name' => $tag),
				'recursive' => 1
			));
			if (isset($tags[0]['TablePost'][0]['id'])) {
				$ids = Hash::extract($tags, '{n}.TablePost.{n}.id');
				$conditions['TablePost.id'] = $ids;
			} else {
				return array();
			}
		}

		// キーワード条件
		if ($_conditions['keyword']) {
			$keyword = $_conditions['keyword'];
			if (preg_match('/\s/', $keyword)) {
				$keywords = explode("\s", $keyword);
			} else {
				$keywords = array($keyword);
			}
			foreach ($keywords as $key => $value) {
				$keywords[$key] = urldecode($value);
				$conditions['or'][]['TablePost.name LIKE'] = '%' . $value . '%';
				$conditions['or'][]['TablePost.content LIKE'] = '%' . $value . '%';
				$conditions['or'][]['TablePost.detail LIKE'] = '%' . $value . '%';
			}
		}

		// 年月日条件
		if ($_conditions['year'] || $_conditions['month'] || $_conditions['day']) {
			$year = $_conditions['year'];
			$month = $_conditions['month'];
			$day = $_conditions['day'];

			$db = ConnectionManager::getDataSource($this->TablePost->useDbConfig);
			$datasouce = strtolower(preg_replace('/^Database\/Bc/', '', $db->config['datasource']));

			switch ($datasouce) {
				case 'mysql':
				case 'csv':
					if ($year) {
						$conditions["YEAR(TablePost.posts_date)"] = $year;
					}
					if ($month) {
						$conditions["MONTH(TablePost.posts_date)"] = $month;
					}
					if ($day) {
						$conditions["DAY(TablePost.posts_date)"] = $day;
					}
					break;
				case 'postgres':
					if ($year) {
						$conditions["date_part('year',TablePost.posts_date) = "] = $year;
					}
					if ($month) {
						$conditions["date_part('month',TablePost.posts_date) = "] = $month;
					}
					if ($day) {
						$conditions["date_part('day',TablePost.posts_date) = "] = $day;
					}
					break;
				case 'sqlite':
					if ($year) {
						$conditions["strftime('%Y',TablePost.posts_date)"] = $year;
					}
					if ($month) {
						$conditions["strftime('%m',TablePost.posts_date)"] = sprintf('%02d', $month);
					}
					if ($day) {
						$conditions["strftime('%d',TablePost.posts_date)"] = sprintf('%02d', $day);
					}
					break;
			}
		}

		//author条件
		if ($_conditions['author']) {
			$author = $_conditions['author'];
			App::uses('User', 'Model');
			$user = new User();
			$userId = $user->field('id', array(
				'User.name' => $author
			));
			$conditions['TablePost.user_id'] = $userId;
		}

		if ($_conditions['id']) {
			$conditions["TablePost.no"] = $_conditions['id'];
			$expects[] = 'TableComment';
			$this->TablePost->hasMany['TableComment']['conditions'] = array('TableComment.status' => true);
			$num = 1;
		}

		unset($_conditions['author']);
		unset($_conditions['category']);
		unset($_conditions['tag']);
		unset($_conditions['keyword']);
		unset($_conditions['year']);
		unset($_conditions['month']);
		unset($_conditions['day']);
		unset($_conditions['id']);
		unset($_conditions['page']);
		unset($_conditions['num']);
		unset($_conditions['sort']);
		unset($_conditions['direction']);

		if ($_conditions) {
			// とりあえず TablePost のフィールド固定
			$conditions = array_merge($conditions, $this->postConditions(array('TablePost' => $_conditions)));
		}

		// プレビューの場合は公開ステータスを条件にしない
		if (!$this->preview) {
			$conditions = array_merge($conditions, $this->TablePost->getConditionAllowPublish());
		}

		$this->TablePost->expects($expects, false);

		$order = "TablePost.{$sort} {$direction}";

		// 毎秒抽出条件が違うのでキャッシュしない
		$this->paginate = array(
			'conditions' => $conditions,
			'fields' => array(),
			'order' => $order,
			'limit' => $num,
			'recursive' => 1,
			'cache' => false
		);

		return $this->paginate('TablePost');
	}

/**
 * [MOBILE] テーブルアーカイブを表示する
 *
 * @param mixed	table_post_id / type
 * @param mixed	table_post_id / ""
 * @return void
 * @access public
 */
	public function mobile_archives() {
		$this->setAction('archives');
	}

/**
 * [SMARTPHONE] テーブルアーカイブを表示する
 *
 * @param mixed	table_post_id / type
 * @param mixed	table_post_id / ""
 * @return void
 * @access public
 */
	public function smartphone_archives() {
		$this->setAction('archives');
	}

/**
 * [ADMIN] プレビューを表示する
 *
 * @param int $tableContentsId
 * @param int $id
 * @param string $mode
 * @return void
 * @access public
 */
	public function admin_preview($tableContentsId, $id, $mode) {
		if ($mode == 'create') {
			$this->_createPreview($tableContentsId, $id);
		} elseif ($mode == 'view') {
			$this->_viewPreview($tableContentsId, $id);
		}
	}

/**
 * テーブル記事をプレビュー
 *
 * @param int $tableContentsId / type
 * @param int $id / ""
 * @return void
 * @access protected
 */
	protected function _createPreview($tableContentsId, $id) {
		if (!empty($this->request->data['TablePost']['eye_catch_'])) {
			$this->request->data['TablePost']['eye_catch'] = $this->request->data['TablePost']['eye_catch_'];
		} else {
			$this->request->data['TablePost']['eye_catch'] = '';
		}
		Cache::write('table_posts_preview_' . $id, $this->request->data, '_cake_core_');
		echo true;
		exit();
	}

/**
 * プレビューを表示する
 *
 * @param int $tableContentId
 * @param int $id
 * @return void
 * @access protected
 */
	protected function _viewPreview($tableContentsId, $id) {
		$data = Cache::read('table_posts_preview_' . $id, '_cake_core_');
		Cache::delete('table_posts_preview_' . $id, '_cake_core_');
		// createせず直接プレビューURLを叩いた場合
		if (empty($data)) {
			$data = $this->TablePost->find('first', array(
				'conditions' => array(
					'TablePost.id' => $id,
					'TableContent.id' => $tableContentsId
				)
			));
		}
		$this->request->data = $this->request->params['data'] = $data;
		$this->preview = true;
		$this->layoutPath = '';
		$this->subDir = '';
		$no = ( isset($this->request->data['TablePost']['no']) ) ? $this->request->data['TablePost']['no'] : "";
		unset($this->request->params['pass']);
		unset($this->request->params['prefix']);
		unset($this->request->params['admin']);
		$this->request->params['controller'] = $this->tableContent['TableContent']['name'];
		$this->request->params['action'] = 'archives';
		$this->request->url = $this->params['controller'] . '/' . 'archives' . '/' . $no;
		$this->request->params['pass'][0] = $no;
		$this->theme = $this->siteConfigs['theme'];
		$this->setAction('archives');
	}

/**
 * テーブルカレンダー用のデータを取得する
 *
 * @param int $id
 * @param int $year
 * @param int $month
 * @return array
 * @access public
 */
	public function get_calendar($id, $year = '', $month = '') {
		$year = h($year);
		$month = h($month);
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $id);
		$this->TablePost->recursive = -1;
		$data['entryDates'] = $this->TablePost->getEntryDates($id, $year, $month);

		if (!$year) {
			$year = date('Y');
		}
		if (!$month) {
			$month = date('m');
		}

		if ($month == 12) {
			$data['next'] = $this->TablePost->existsEntry($id, $year + 1, 1);
		} else {
			$data['next'] = $this->TablePost->existsEntry($id, $year, $month + 1);
		}
		if ($month == 1) {
			$data['prev'] = $this->TablePost->existsEntry($id, $year - 1, 12);
		} else {
			$data['prev'] = $this->TablePost->existsEntry($id, $year, $month - 1);
		}

		return $data;
	}

/**
 * カテゴリー一覧用のデータを取得する
 *
 * @param int $id
 * @param mixed $limit Number Or false Or '0'（制限なし）
 * @param mixed $viewCount
 * @param mixed $contentType year Or null
 * @return array
 * @access public
 */
	public function get_categories($id, $limit = false, $viewCount = false, $depth = 1, $contentType = null) {
		if ($limit === '0') {
			$limit = false;
		}
		$data = array();
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $id);
		$data['categories'] = $this->TableCategory->getCategoryList($id, array(
			'type' => $contentType,
			'limit' => $limit,
			'depth' => $depth,
			'viewCount' => $viewCount
		));
		return $data;
	}

/**
 * 投稿者一覧ウィジェット用のデータを取得する
 *
 * @param int $tableContentId
 * @param boolean $limit
 * @param int $viewCount
 */
	public function get_authors($tableContentId, $viewCount = false) {
		$data = array();
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $tableContentId);
		$data['authors'] = $this->TablePost->getAuthors($tableContentId, array(
			'viewCount' => $viewCount
		));
		return $data;
	}

/**
 * 月別アーカイブ一覧用のデータを取得する
 *
 * @param int $id
 * @return mixed $limit Number Or false Or '0'（制限なし）
 * @access public
 */
	public function get_posted_months($id, $limit = 12, $viewCount = false) {
		if ($limit === '0') {
			$limit = false;
		}
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $id);
		$this->TablePost->recursive = -1;
		$data['postedDates'] = $this->TablePost->getPostedDates($id, array(
			'type' => 'month',
			'limit' => $limit,
			'viewCount' => $viewCount
		));
		return $data;
	}

/**
 * 年別アーカイブ一覧用のデータを取得する
 *
 * @param int $id
 * @param boolean $viewCount
 * @return mixed $count
 * @access public
 */
	public function get_posted_years($id, $limit = false, $viewCount = false) {
		if ($limit === '0') {
			$limit = false;
		}
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $id);
		$this->TablePost->recursive = -1;
		$data['postedDates'] = $this->TablePost->getPostedDates($id, array(
			'type' => 'year',
			'limit' => $limit,
			'viewCount' => $viewCount
		));
		return $data;
	}

/**
 * 最近の投稿用のデータを取得する
 *
 * @param int $id
 * @param mixed $count
 * @return array
 * @access public
 */
	public function get_recent_entries($id, $limit = 5) {
		if ($limit === '0') {
			$limit = false;
		}
		$this->TableContent->recursive = -1;
		$data['tableContent'] = $this->TableContent->read(null, $id);
		$this->TablePost->recursive = -1;
		$conditions = array('TablePost.table_content_id' => $id);
		$conditions = am($conditions, $this->TablePost->getConditionAllowPublish());
		// 毎秒抽出条件が違うのでキャッシュしない
		$data['recentEntries'] = $this->TablePost->find('all', array(
			'fields' => array('no', 'name'),
			'conditions' => $conditions,
			'limit' => $limit,
			'order' => 'posts_date DESC',
			'recursive' => -1,
			'cache' => false
		));
		return $data;
	}

/**
 * 記事リストを出力
 * requestAction用
 *
 * @param int $tableContentId
 * @param mixed $num
 * @access public
 */
	public function posts($tableContentId, $limit = 5) {
		if (!empty($this->params['named']['template'])) {
			$template = $this->request->params['named']['template'];
		} else {
			$template = 'posts';
		}
		unset($this->request->params['named']['template']);

		$this->layout = null;
		$this->contentId = $tableContentId;

		if ($this->tableContent['TableContent']['status']) {
			$datas = $this->_getTablePosts(array('listCount' => $limit));
		} else {
			$datas = array();
		}

		$this->set('posts', $datas);

		$this->render($this->tableContent['TableContent']['template'] . DS . $template);
	}

/**
 * [MOBILE] 記事リストを出力
 *
 * requestAction用
 *
 * @param int $tableContentId
 * @param mixed $num
 * @access public
 */
	public function mobile_posts($tableContentId, $limit = 5) {
		$this->setAction('posts', $tableContentId, $limit);
	}

/**
 * [SMARTPHONE] 記事リストを出力
 *
 * requestAction用
 *
 * @param int $tableContentId
 * @param mixed $num
 * @access public
 */
	public function smartphone_posts($tableContentId, $limit = 5) {
		$this->setAction('posts', $tableContentId, $limit);
	}

}
