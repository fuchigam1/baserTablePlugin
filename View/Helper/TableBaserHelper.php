<?php
/**
 * TableBaserヘルパー
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2014, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Table.View.Helper
 * @since			baserCMS v 0.1.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * TableBaserヘルパー
 *
 * @package Table.View.Helper
 *
 */
class TableBaserHelper extends AppHelper {

/**
 * ヘルパー
 * @var array
 */
	public $helpers = array('Table.Table');

/**
 * テーブル記事一覧出力
 * ページ編集画面等で利用する事ができる。
 * 利用例: <?php $this->BcBaser->tablePosts('news', 3) ?>
 * ビュー: app/webroot/theme/{テーマ名}/table/{コンテンツテンプレート名}/posts.php
 *
 * @param int $contentsName
 * @param int $num
 * @param array $options
 * @param mixid $mobile '' / boolean
 * @return void
 * @access public
 */
	public function tablePosts($contentsName, $num = 5, $options = array()) {
		$options = array_merge(array(
			'category' => null,
			'tag' => null,
			'year' => null,
			'month' => null,
			'day' => null,
			'id' => null,
			'keyword' => null,
			'template' => null,
			'direction' => null,
			'page' => null,
			'sort' => null
		), $options);

		$TableContent = ClassRegistry::init('Table.TableContent');
		$id = $TableContent->field('id', array('TableContent.name' => $contentsName));
		$url = array('admin' => false, 'plugin' => 'table', 'controller' => 'table', 'action' => 'posts');

		$settings = Configure::read('BcAgent');
		foreach ($settings as $key => $setting) {
			if (isset($options[$key])) {
				$agentOn = $options[$key];
				unset($options[$key]);
			} else {
				$agentOn = (Configure::read('BcRequest.agent') == $key);
			}
			if ($agentOn) {
				$url['prefix'] = $setting['prefix'];
				break;
			}
		}

		echo $this->requestAction($url, array('return', 'pass' => array($id, $num), 'named' => $options));
	}

/**
 * カテゴリー別記事一覧ページ判定
 *
 * @return boolean
 */
	public function isTableCategory() {
		return $this->Table->isCategory();
	}

/**
 * タグ別記事一覧ページ判定
 * @return boolean
 */
	public function isTableTag() {
		return $this->Table->isTag();
	}

/**
 * 日別記事一覧ページ判定
 * @return boolean
 */
	public function isTableDate() {
		return $this->Table->isDate();
	}

/**
 * 月別記事一覧ページ判定
 * @return boolean
 */
	public function isTableMonth() {
		return $this->Table->isMonth();
	}

/**
 * 年別記事一覧ページ判定
 * @return boolean
 */
	public function isTableYear() {
		return $this->Table->isYear();
	}

/**
 * 個別ページ判定
 * @return boolean
 */
	public function isTableSingle() {
		return $this->Table->isSingle();
	}

/**
 * インデックスページ判定
 * @return boolean
 */
	public function isTableHome() {
		return $this->Table->isHome();
	}

}
