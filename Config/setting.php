<?php

/**
 * テーブル設定
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
/**
 * システムナビ
 */
$config['BcApp.adminNavi.table'] = array(
	'name' => 'テーブルプラグイン',
	'contents' => array(
		array('name' => 'テーブル一覧', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_contents', 'action' => 'index')),
		array('name' => 'テーブル登録', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_contents', 'action' => 'add')),
		array('name' => 'タグ一覧', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_tags', 'action' => 'index')),
		array('name' => 'タグ登録', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_tags', 'action' => 'add')),
	)
);
$TableContent = ClassRegistry::init('Table.TableContent');
$tableContents = $TableContent->find('all', array('recursive' => -1));
foreach ($tableContents as $tableContent) {
	$tableContent = $tableContent['TableContent'];
	$config['BcApp.adminNavi.table']['contents'] = array_merge($config['BcApp.adminNavi.table']['contents'], array(
		array('name' => '[' . $tableContent['title'] . '] 公開ページ', 'url' => '/' . $tableContent['name'] . '/index'),
		array('name' => '[' . $tableContent['title'] . '] 記事一覧', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_posts', 'action' => 'index', $tableContent['id'])),
		array('name' => '[' . $tableContent['title'] . '] 記事登録', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_posts', 'action' => 'add', $tableContent['id'])),
		array('name' => '[' . $tableContent['title'] . '] カテゴリ一覧', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_categories', 'action' => 'index', $tableContent['id'])),
		array('name' => '[' . $tableContent['title'] . '] カテゴリ登録', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_categories', 'action' => 'add', $tableContent['id'])),
		array('name' => '[' . $tableContent['title'] . '] コメント一覧', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_comments', 'action' => 'index', $tableContent['id'])),
		array('name' => '[' . $tableContent['title'] . '] 設定', 'url' => array('admin' => true, 'plugin' => 'table', 'controller' => 'table_contents', 'action' => 'edit', $tableContent['id'])),
	));
}
