<?php
/**
 * [PUBLISH] テーブル詳細ページ
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
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->Table->getPostContent($post, false, false, 50));
?>

<script type="text/javascript">
$(function(){
	if($("a[rel='colorbox']").colorbox) $("a[rel='colorbox']").colorbox({transition:"fade"});
	});
</script>

<!-- table title -->
<h2 class="contents-head">
<?php $this->Table->title() ?>
</h2>

<!-- post title -->
<h3 class="contents-head">
<?php $this->BcBaser->contentsTitle() ?>
</h3>

<div class="eye-catch">
<?php $this->Table->eyeCatch($post) ?>
</div>

<!-- post detail -->
<div class="post">
			<?php $this->Table->postContent($post) ?>
	<div class="meta"><span>
			<?php $this->Table->category($post) ?>
			&nbsp;
			<?php $this->Table->postDate($post) ?>
			&nbsp;
	<?php $this->Table->author($post) ?>
		</span></div>
<?php $this->BcBaser->element('table_tag', array('post' => $post)) ?>
</div>

<!-- contents navi -->
<div id="contentsNavi">
	<?php $this->Table->prevLink($post) ?>
	&nbsp;｜&nbsp;
<?php $this->Table->nextLink($post) ?>
</div>

<!-- comments -->
<?php $this->BcBaser->element('table_comments') ?>