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
$this->BcBaser->setDescription($this->Table->getTitle() . '｜' . $this->BcBaser->getContentsTitle() . 'のアーカイブ一覧です。');
?>

<!-- title -->
<h2 class="contents-head">
	<?php $this->Table->title() ?>
</h2>

<!-- archives title -->
<h3 class="contents-head">
	<?php $this->BcBaser->contentsTitle() ?>
</h3>

<section class="box news">
	<!-- list -->
	<?php if (!empty($posts)): ?>
		<ul>
			<?php foreach ($posts as $post): ?>
				<li><?php $this->Table->postLink($post, '<span class="date">' . $this->Table->getPostDate($post) . '</span><br />' . $this->Table->getPostTitle($post)) ?></li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p class="no-data">記事がありません。</p>
	<?php endif; ?>
</section>

<!-- pagination -->
<?php $this->BcBaser->pagination('simple'); ?>