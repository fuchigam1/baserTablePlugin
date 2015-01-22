<?php
/**
 * [PUBLISH] テーブルトップ
 *
 * PHP versions 5
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$this->BcBaser->css(array('Table.style', 'admin/colorbox/colorbox'), array('inline' => true));
$this->BcBaser->js('admin/jquery.colorbox-min-1.4.5', false);
$this->BcBaser->setDescription($this->Table->getDescription());
?>

<script type="text/javascript">
$(function(){
	if($("a[rel='colorbox']").colorbox) $("a[rel='colorbox']").colorbox({transition:"fade"});
	});
</script>

<!-- title -->
<h2 class="contents-head">
<?php $this->Table->title() ?>
</h2>

<!-- description -->
	<?php if ($this->Table->descriptionExists()): ?>
	<div class="table-description">
	<?php $this->Table->description() ?>
	</div>
	<?php endif ?>

<!-- list -->
<?php if (!empty($posts)): ?>
	<?php foreach ($posts as $post): ?>
		<div class="post">
			<h4 class="contents-head">
			<?php $this->Table->postTitle($post) ?>
			</h4>
					<?php $this->Table->postContent($post, false, true) ?>
			<div class="meta"><span>
					<?php $this->Table->category($post) ?>
					&nbsp;
					<?php $this->Table->postDate($post) ?>
					&nbsp;
			<?php $this->Table->author($post) ?>
				</span></div>
		<?php $this->BcBaser->element('table_tag', array('post' => $post)) ?>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<p class="no-data">記事がありません。</p>
<?php endif; ?>

<!-- pagination -->
<?php $this->BcBaser->pagination('simple'); ?>