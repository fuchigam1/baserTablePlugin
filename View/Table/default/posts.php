<?php
/**
 * [PUBLISH] 記事一覧
 *
 * BaserHelper::tablePosts( コンテンツ名, 件数 ) で呼び出す
 * （例）<?php $this->BcBaser->tablePosts('news', 3) ?>
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>
<?php if ($posts): ?>
	<ul class="post-list">
		<?php foreach ($posts as $key => $post): ?>
			<?php $class = array('clearfix', 'post-' . ($key + 1)) ?>
			<?php if ($this->BcArray->first($posts, $key)): ?>
				<?php $class[] = 'first' ?>
			<?php elseif ($this->BcArray->last($posts, $key)): ?>
				<?php $class[] = 'last' ?>
			<?php endif ?>
			<li class="<?php echo implode(' ', $class) ?>">
				<span class="date"><?php $this->Table->postDate($post, 'Y.m.d') ?></span><br />
				<span class="title"><?php $this->Table->postTitle($post) ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<p class="no-data">記事がありません</p>
<?php endif ?>
