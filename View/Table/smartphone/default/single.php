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
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->Table->getPostContent($post, false, false, 50));
$this->Table->editPost($post['TablePost']['table_content_id'], $post['TablePost']['id']);
?>

<!-- table title -->
<h2 class="contents-head">
	<?php $this->Table->title() ?>
</h2>

<!-- post detail -->
<div class="post">

	<!-- post title -->
	<h3 class="contents-head">
		<?php $this->BcBaser->contentsTitle() ?><br />
		<small><?php $this->Table->postDate($post) ?></small>
	</h3>

	<?php $this->Table->postContent($post) ?>

	<div class="meta"><span><?php $this->Table->category($post) ?>&nbsp;<?php $this->Table->author($post) ?></span></div>
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