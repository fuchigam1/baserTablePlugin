<?php
/**
 * [ADMIN] テーブル記事管理メニュー
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
	<th>テーブル管理メニュー</th>
	<td>
		<ul class="cleafix">
			<li><?php $this->BcBaser->link('記事一覧', array('controller' => 'table_posts', 'action' => 'index', $tableContent['TableContent']['id'])) ?></li>
			<?php if (isset($newCatAddable) && $newCatAddable): ?>
				<li><?php $this->BcBaser->link('新規記事を登録', array('controller' => 'table_posts', 'action' => 'add', $tableContent['TableContent']['id'])) ?></li>
			<?php endif ?>
			<li><?php $this->BcBaser->link('コメント一覧', array('controller' => 'table_comments', 'action' => 'index', $tableContent['TableContent']['id'])) ?></li>
			<li><?php $this->BcBaser->link('テーブル基本設定', array('controller' => 'table_contents', 'action' => 'edit', $tableContent['TableContent']['id'])) ?></li>
			<li><?php $this->BcBaser->link('公開ページ確認', '/' . $tableContent['TableContent']['name'] . '/index') ?></li>
		</ul>
	</td>
</tr>