<?php
/**
 * [PUBLISH] テーブルカテゴリー一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
App::uses('TableHelper', 'Table.View/Helper');

if (empty($view_count)) {
	$view_count = '0';
}
if (empty($limit)) {
	$limit = '0';
}
if (!isset($by_year)) {
	$by_year = null;
}
if (isset($tableContent)) {
	$id = $tableContent['TableContent']['id'];
} else {
	$id = $table_content_id;
}
if (empty($depth)) {
	$depth = 1;
}
$actionUrl = '/table/table/get_categories/' . $id . '/' . $limit . '/' . $view_count . '/' . $depth;
if ($by_year) {
	$actionUrl .= '/year';
}
$data = $this->requestAction($actionUrl);
$categories = $data['categories'];
$this->viewVars['tableContent'] = $data['tableContent'];
$this->Table = new TableHelper($this);
?>


<div class="widget widget-table-categories-archives widget-table-categories-archives-<?php echo $id ?> table-widget">
	<?php if ($name && $use_title): ?>
		<h2><?php echo $name ?></h2>
	<?php endif ?>
	<?php if ($by_year): ?>
		<ul>
			<?php foreach ($categories as $key => $category): ?>
				<li class="category-year"><span><?php $this->BcBaser->link($key . '年', array('plugin' => null, 'controller' => $tableContent['TableContent']['name'], 'action' => 'archives', 'date', $key)) ?></span>
						<?php echo $this->Table->getCategoryList($category, $depth, $view_count, array('named' => array('year' => $key))) ?>
				</li>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<?php echo $this->Table->getCategoryList($categories, $depth, $view_count) ?>
	<?php endif ?>
</div>
