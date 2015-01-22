<?php
/**
 * [ADMIN] テーブルカテゴリ管理メニュー
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<tr>
	<th>カテゴリ管理メニュー</th>
	<td>
		<ul class="cleafix">
			<li><?php $this->BcBaser->link('テーブルカテゴリ一覧', array('controller' => 'table_categories', 'action' => 'index', $tableContent['TableContent']['id'])) ?></li>
			<?php if (isset($newCatAddable) && $newCatAddable): ?>
				<li><?php $this->BcBaser->link('新規テーブルカテゴリを登録', array('controller' => 'table_categories', 'action' => 'add', $tableContent['TableContent']['id'])) ?></li>
			<?php endif ?>
		</ul>
	</td>
</tr>