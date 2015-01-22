<?php
/**
 * [MOBILE] タイトル一覧
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
?>

<?php if (!empty($posts)): ?>
	<?php foreach ($posts as $key => $post): ?>
		<span style="color:#8ABE08">■</span>&nbsp;<?php $this->Table->postDate($post, 'y.m.d') ?><br />
		<?php $this->Table->postTitle($post) ?>
		<hr size="1" style="width:100%;height:1px;margin:5px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
	<?php endforeach; ?>
<?php else: ?>
	<p style="text-align:center">ー</p>
<?php endif; ?>