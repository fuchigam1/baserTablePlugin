<?php
/**
 * [ADMIN] テーブル共通メニュー
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
	<th>テーブルプラグイン共通メニュー</th>
	<td>
		<ul class="cleafix">
			<li><?php $this->BcBaser->link('テーブル一覧', array('controller' => 'table_contents', 'action' => 'index')) ?></li>
			<li><?php $this->BcBaser->link('新規テーブルを登録', array('controller' => 'table_contents', 'action' => 'add')) ?></li>
			<li><?php $this->BcBaser->link('タグ一覧', array('controller' => 'table_tags', 'action' => 'index')) ?></li>
			<li><?php $this->BcBaser->link('新規タグを登録', array('controller' => 'table_tags', 'action' => 'add')) ?></li>
		</ul>
	</td>
</tr>
