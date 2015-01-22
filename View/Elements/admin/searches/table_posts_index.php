<?php
/**
 * [ADMIN] テーブル記事 一覧　検索ボックス
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$this->TableCategories = $this->BcForm->getControlSource('TablePost.table_category_id', array('tableContentId' => $tableContent['TableContent']['id']));
$this->TableTags = $this->BcForm->getControlSource('TablePost.table_tag_id');
$users = $this->BcForm->getControlSource("TablePost.user_id");
?>

<?php echo $this->BcForm->create('TablePost', array('url' => array('action' => 'index', $tableContent['TableContent']['id']))) ?>
<p>
	<span><?php echo $this->BcForm->label('TablePost.name', 'タイトル') ?> <?php echo $this->BcForm->input('TablePost.name', array('type' => 'text', 'size' => '30')) ?></span>
	<?php if ($this->TableCategories): ?>
		<span><?php echo $this->BcForm->label('TablePost.table_category_id', 'カテゴリー') ?> <?php echo $this->BcForm->input('TablePost.table_category_id', array('type' => 'select', 'options' => $this->TableCategories, 'escape' => false, 'empty' => '指定なし')) ?></span>　
	<?php endif ?>
	<?php if ($tableContent['TableContent']['tag_use'] && $this->TableTags): ?>
		<span><?php echo $this->BcForm->label('TablePost.table_tag_id', 'タグ') ?> <?php echo $this->BcForm->input('TablePost.table_tag_id', array('type' => 'select', 'options' => $this->TableTags, 'escape' => false, 'empty' => '指定なし')) ?></span>　
	<?php endif ?>
	<span><?php echo $this->BcForm->label('TablePost.status', '公開設定') ?> <?php echo $this->BcForm->input('TablePost.status', array('type' => 'select', 'options' => $this->BcText->booleanMarkList(), 'empty' => '指定なし')) ?></span>　
	<span><?php echo $this->BcForm->label('TablePost.user_id', '作成者') ?> <?php echo $this->BcForm->input('TablePost.user_id', array('type' => 'select', 'options' => $users, 'empty' => '指定なし')) ?></span>　
</p>
<div class="button">
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/btn_search.png', array('alt' => '検索', 'class' => 'btn')), "javascript:void(0)", array('id' => 'BtnSearchSubmit')) ?>
	<?php $this->BcBaser->link($this->BcBaser->getImg('admin/btn_clear.png', array('alt' => 'クリア', 'class' => 'btn')), "javascript:void(0)", array('id' => 'BtnSearchClear')) ?>
</div>