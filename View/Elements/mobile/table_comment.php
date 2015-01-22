<?php
/**
 * [MOBILE] テーブルコメント単記事
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

		<div class="comment" id="Comment<?php echo $dbData['no'] ?>"> <span class="comment-name"> <span style="color:#8ABE08">◆ </span>
				<?php if ($dbData['url']): ?>
					<?php echo $this->BcBaser->link($dbData['name'], $dbData['url'], array('target' => '_blank')) ?>
				<?php else: ?>
					<?php echo $dbData['name'] ?>
				<?php endif ?>
			</span> <br />
			<span class="comment-message"> <?php echo nl2br($dbData['message']) ?> </span>
			<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#cccccc;background:#cccccc;border:1px solid #cccccc;" />
		</div>
	<?php endif ?>
<?php endif ?>
