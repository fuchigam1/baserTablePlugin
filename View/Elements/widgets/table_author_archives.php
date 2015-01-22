<?php
/**
 * [PUBLISH] テーブル投稿者一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
if (empty($view_count)) {
	$view_count = '0';
}
if (isset($tableContent)) {
	$id = $tableContent['TableContent']['id'];
} else {
	$id = $table_content_id;
}
$data = $this->requestAction('/table/table/get_authors/' . $id . '/' . $view_count);
$authors = $data['authors'];
$tableContent = $data['tableContent'];
$baseCurrentUrl = $tableContent['TableContent']['name'] . '/archives/';
?>
<div class="widget widget-table-authors widget-table-authors-<?php echo $id ?> table-widget">
	<?php if ($name && $use_title): ?>
		<h2><?php echo $name ?></h2>
	<?php endif ?>
	<?php if ($authors): ?>
		<ul>
			<?php foreach ($authors as $author): ?>
				<?php
				if ($this->request->url == $baseCurrentUrl . $author['User']['name']) {
					$class = ' class="current"';
				} else {
					$class = '';
				}
				if ($view_count) {
					$title = $this->BcBaser->getUserName($author['User']) . ' (' . $author['count'] . ')';
				} else {
					$title = $this->BcBaser->getUserName($author['User']);
				}
				?>
				<li<?php echo $class ?>>
					<?php
					$this->BcBaser->link($title, array(
						'admin' => false, 'plugin' => '',
						'controller' => $tableContent['TableContent']['name'],
						'action' => 'archives',
						'author',
						$author['User']['name']
					))
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
