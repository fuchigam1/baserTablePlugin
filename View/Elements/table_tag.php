<?php
/**
 * [PUBLISH] タグ
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>
<?php if (!empty($this->Table->tableContent['tag_use'])): ?>
	<?php if (!empty($post['TableTag'])) : ?>
		<div class="tag">タグ：<?php $this->Table->tag($post) ?></div>
	<?php endif ?>
<?php endif ?>
