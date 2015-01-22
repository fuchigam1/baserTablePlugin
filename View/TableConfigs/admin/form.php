<?php
/**
 * [ADMIN] テーブル設定 フォーム
 *
 * 1.6.10 では利用していない＆不完全
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<!-- title -->
<h2><?php $this->BcBaser->contentsTitle() ?></h2>

<h3>WordPressデータの取り込み</h3>
<p>WordPressから出力したXMLデータを取込みます。（<a href="http://ja.wordpress.org/" target="_blank">WordPress</a> 2.8.4 のみ動作確認済）</p>

<div class="align-center">
	<?php echo $this->BcForm->create('TablePost', array('action' => 'import', 'enctype' => 'multipart/form-data')) ?>
	<?php echo $this->BcForm->input('Import.table_content_id', array('type' => 'select', 'options' => $tableContentList)) ?>
	<?php echo $this->BcForm->input('Import.user_id', array('type' => 'select', 'options' => $userList)) ?>
	<?php echo $this->BcForm->input('Import.file', array('type' => 'file')) ?>
	<?php echo $this->BcForm->end(array('label' => '取り込む', 'div' => false, 'class' => 'btn-orange button')) ?>
</div>
