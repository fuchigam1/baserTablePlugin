<?php
/**
 * [PUBLISH] テーブル月別アーカイブ
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
if (!isset($view_count)) {
	$view_count = false;
}
if (!isset($limit)) {
	$limit = 12;
}
if (isset($tableContent)) {
	$id = $tableContent['TableContent']['id'];
} else {
	$id = $table_content_id;
}
$actionUrl = '/table/table/get_posted_months/' . $id . '/' . $limit;
if ($view_count) {
	$actionUrl .= '/1';
}
$data = $this->requestAction($actionUrl);
$postedDates = $data['postedDates'];
$tableContent = $data['tableContent'];
$baseCurrentUrl = $tableContent['TableContent']['name'] . '/archives/date/';
?>


<div class="widget widget-table-monthly-archives widget-table-monthly-archives-<?php echo $id ?> table-widget">
	<?php if ($name && $use_title): ?>
		<h2><?php echo $name ?></h2>
	<?php endif ?>
	<?php if (!empty($postedDates)): ?>
		<ul>
			<?php foreach ($postedDates as $postedDate): ?>
				<?php if (isset($this->params['named']['year']) && isset($this->params['named']['month']) && $this->params['named']['year'] == $postedDate['year'] && $this->params['named']['month'] == $postedDate['month']): ?>
					<?php $class = ' class="selected"' ?>
				<?php elseif ($this->request->url == $baseCurrentUrl . $postedDate['year'] . '/' . $postedDate['month']): ?>
					<?php $class = ' class="current"' ?>
				<?php else: ?>
					<?php $class = '' ?>
				<?php endif ?>
				<?php if ($view_count): ?>
					<?php $title = $postedDate['year'] . '年' . $postedDate['month'] . '月' . '(' . $postedDate['count'] . ')' ?>
				<?php else: ?>
					<?php $title = $postedDate['year'] . '年' . $postedDate['month'] . '月' ?>
				<?php endif ?>
				<li<?php echo $class ?>>
					<?php
					$this->BcBaser->link($title, array(
						'admin' => false,
						'plugin' => '',
						'controller' => $tableContent['TableContent']['name'],
						'action' => 'archives',
						'date', $postedDate['year'], $postedDate['month']
					))
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
