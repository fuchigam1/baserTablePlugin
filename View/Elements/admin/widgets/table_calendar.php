<?php
/**
 * [ADMIN] テーブルカレンダーウィジェット設定
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$title = 'テーブルカレンダー';
$description = 'テーブルのカレンダーを表示します。';
?>
<?php echo $this->BcForm->label($key . '.table_content_id', 'テーブル') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.table_content_id', array('type' => 'select', 'options' => $this->BcForm->getControlSource('Table.TableContent.id'))) ?><br />
<small>テーブルページを表示している場合は、上記の設定に関係なく、対象テーブルのテーブルカレンダーを表示します。</small>