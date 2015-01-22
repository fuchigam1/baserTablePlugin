<?php
/**
 * [PUBLISH] テーブル年別アーカイブ
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
	$limit = false;
}
if (isset($tableContent)) {
	$id = $tableContent['TableContent']['id'];
} else {
	$id = $table_content_id;
}
$actionUrl = '/table/table/get_posted_years/' . $id;
if ($limit) {
	$actionUrl .= '/' . $limit;
} else {
	$actionUrl .= '/0';
}
if ($view_count) {
	$actionUrl .= '/1';
}
$data = $this->requestAction($actionUrl);
$postedDates = $data['postedDates'];
$tableContent = $data['tableContent'];
$baseCurrentUrl = $tableContent['TableContent']['name'] . '/archives/date/';
?>


<div class="widget widget-table-yearly-archives widget-table-yearly-archives-<?php echo $id ?> table-widget">
	<?php if ($name && $use_title): ?>
		<h2><?php echo $name ?></h2>
	<?php endif ?>
	<?php if (!empty($postedDates)): ?>
		<ul>
			<?php foreach ($postedDates as $postedDate): ?>
				<?php if (isset($this->params['named']['year']) && $this->params['named']['year'] == $postedDate['year']): ?>
					<?php $class = ' class="selected"' ?>
				<?php elseif ($this->request->url == $baseCurrentUrl . $postedDate['year']): ?>
					<?php $class = ' class="current"' ?>
				<?php else: ?>
					<?php $class = '' ?>
				<?php endif ?>
				<?php if ($view_count): ?>
					<?php $title = $postedDate['year'] . '年' . '(' . $postedDate['count'] . ')' ?>
				<?php else: ?>
					<?php $title = $postedDate['year'] . '年' ?>
				<?php endif ?>
				<li<?php echo $class ?>>
					<?php
					$this->BcBaser->link($title, array(
						'admin' => false,
						'plugin' => '',
						'controller' => $tableContent['TableContent']['name'],
						'action' => 'archives',
						'date', $postedDate['year']
					))
					?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>