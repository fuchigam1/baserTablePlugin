<?php
/**
 * [MOBILE] テーブル
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
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->BcBaser->getContentsTitle() . 'のアーカイブ一覧です。');
?>

<!-- title -->
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<div style="text-align:center;background-color:#8ABE08;"> <span style="color:white;"><?php echo $this->BcBaser->getContentsTitle(); ?></span> </div>
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />

<!-- pagination -->
<?php echo $this->BcBaser->pagination() ?>

<!-- list -->
<?php if (!empty($posts)): ?>
	<?php foreach ($posts as $post): ?>
		<span style="color:#8ABE08">◆</span>
		<?php $this->Table->postTitle($post) ?>
		<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#8ABE08;background:#8ABE08;border:1px solid #8ABE08;" />
		<br />
		<?php $this->Table->postContent($post, false, true) ?>
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
	<?php endforeach; ?>
<?php else: ?>
	<p class="no-data">記事がありません。</p>
<?php endif; ?>

<!-- pagination -->
<?php echo $this->BcBaser->pagination() ?>