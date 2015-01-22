<?php
/**
 * [ADMIN] テーブル最近の投稿ウィジェット設定
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$title = '最近の投稿';
$description = 'テーブルの最近の投稿を表示します。';
?>
<?php echo $this->BcForm->label($key . '.count', '表示数') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.count', array('type' => 'text', 'size' => 6, 'default' => 5)) ?>&nbsp;件<br />
<?php echo $this->BcForm->label($key . '.table_content_id', 'テーブル') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.table_content_id', array('type' => 'select', 'options' => $this->BcForm->getControlSource('Table.TableContent.id'))) ?><br />
<small>テーブルページを表示している場合は、上記の設定に関係なく、対象テーブルの最近の投稿を表示します。</small>