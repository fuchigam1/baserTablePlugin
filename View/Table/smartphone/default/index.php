<?php
/**
 * [SMARTPHONE] テーブルトップ
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
$this->BcBaser->setDescription($this->Table->getDescription());
?>

<!-- title -->
<h2 class="contents-head">
	<?php $this->Table->title() ?>
</h2>

<!-- description -->
<?php if ($this->Table->descriptionExists()): ?>
	<section class="table-description">
		<?php $this->Table->description() ?>
	</section>
<?php endif ?>

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