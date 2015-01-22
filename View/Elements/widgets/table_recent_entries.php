<?php
/**
 * [PUBLISH] テーブル最近の投稿
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
if (!isset($count)) {
	$count = 5;
}
if (isset($tableContent)) {
	$id = $tableContent['TableContent']['id'];
} else {
	$id = $table_content_id;
}
$data = $this->requestAction('/table/table/get_recent_entries/' . $id . '/' . $count);
$recentEntries = $data['recentEntries'];
$tableContent = $data['tableContent'];
$baseCurrentUrl = $tableContent['TableContent']['name'] . '/archives/';
?>
<div class="widget widget-table-recent-entries widget-table-recent-entries-<?php echo $id ?> table-widget">
	<?php if ($name && $use_title): ?>
		<h2><?php echo $name ?></h2>
	<?php endif ?>
	<?php if ($recentEntries): ?>
		<ul>
			<?php foreach ($recentEntries as $recentEntry): ?>
				<?php if ($this->request->url == $baseCurrentUrl . $recentEntry['TablePost']['no']): ?>
					<?php $class = ' class="current"' ?>
				<?php else: ?>
					<?php $class = '' ?>
				<?php endif ?>
				<li<?php echo $class ?>>
					<?php $this->BcBaser->link($recentEntry['TablePost']['name'], array('admin' => false, 'plugin' => '', 'controller' => $tableContent['TableContent']['name'], 'action' => 'archives', $recentEntry['TablePost']['no'])) ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
