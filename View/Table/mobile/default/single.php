<?php
/**
 * [MOBILE] テーブル詳細
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
$this->BcBaser->setTitle($this->pageTitle . '｜' . $this->Table->getTitle());
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->Table->getPostContent($post, false, false, 50));
?>

<!-- title -->
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<div style="text-align:center;background-color:#8ABE08;"> <span style="color:white;"><?php echo $this->BcBaser->getContentsTitle(); ?></span> </div>
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<br />

<!-- detail -->
<?php if (!empty($post)): ?>
	<?php $this->Table->eyeCatch($post, array('mobile' => true)) ?>
	<?php $this->Table->postContent($post) ?>
	<br />
	<p align="right">
		<?php $this->Table->category($post) ?>
		<br />
		<?php $this->Table->postDate($post) ?>
		<br />
		<?php $this->Table->author($post) ?>
	</p>
	<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
	<br />
<?php else: ?>
	<p class="no-data">記事がありません。</p>
<?php endif; ?>

<!-- comments -->
<?php $this->BcBaser->element('table_comments') ?>