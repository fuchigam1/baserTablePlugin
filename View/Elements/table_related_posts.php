<?php
/**
 * [PUBLISH] 関連投稿一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$relatedPosts = $this->Table->getRelatedPosts($post);
?>
<?php if ($relatedPosts): ?>
	<div id="RelatedPosts">
		<h4 class="contents-head">関連記事</h4>
		<ul>
			<?php foreach ($relatedPosts as $relatedPost): ?>
				<li><?php $this->Table->postTitle($relatedPost) ?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>