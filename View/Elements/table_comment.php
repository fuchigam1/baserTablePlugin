<?php
/**
 * [PUBLISH] テーブルコメント単記事
 *
 * Ajax でも利用される
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>

<?php if (!empty($dbData)): ?>
	<?php if ($dbData['status']): ?>
		<div class="comment" id="Comment<?php echo $dbData['no'] ?>">
			<span class="comment-name">≫
				<?php if ($dbData['url']): ?>
					<?php echo $this->BcBaser->link($dbData['name'], $dbData['url'], array('target' => '_blank')) ?>
				<?php else: ?>
					<?php echo $dbData['name'] ?>
				<?php endif ?>
			</span><br />
			<span class="comment-message"><?php echo nl2br($this->BcText->autoLinkUrls($dbData['message'])) ?></span>
		</div>
	<?php endif ?>
<?php endif ?>