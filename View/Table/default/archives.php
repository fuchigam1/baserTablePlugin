<?php
/**
 * [PUBLISH] テーブルアーカイブ一覧
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
//$this->BcBaser->setTitle($this->pageTitle.'｜'.$this->Table->getTitle());
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->BcBaser->getContentsTitle() . 'のアーカイブ一覧です。');
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

<!-- archives title -->
<h3 class="contents-head">
<?php $this->BcBaser->contentsTitle() ?>
</h3>

<!-- list -->
<?php if (!empty($posts)): ?>
	<?php foreach ($posts as $post): ?>
		<div class="post">
			<h4 class="contents-head">
			<?php $this->Table->postTitle($post) ?>
			</h4>
					<?php $this->Table->postContent($post, true, true) ?>
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