<?php
/**
 * [ADMIN] テーブルカテゴリー一覧ウィジェット設定
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$title = 'テーブル投稿者一覧';
$description = 'テーブルの投稿者一覧を表示します。';
?>


<?php echo $this->BcForm->label($key . '.view_count', '記事数表示') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.view_count', array('type' => 'radio', 'options' => $this->BcText->booleanDoList(''), 'legend' => false, 'default' => 0)) ?><br />
<?php echo $this->BcForm->label($key . '.table_content_id', 'テーブル') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.table_content_id', array('type' => 'select', 'options' => $this->BcForm->getControlSource('Table.TableContent.id'))) ?><br />
<small>テーブルページを表示している場合は、上記の設定に関係なく、<br />対象テーブルのテーブル投稿者一覧を表示します。</small>
