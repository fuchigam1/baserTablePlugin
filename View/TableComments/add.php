<?php
/**
 * [PUBLISH] テーブルコメント登録完了
 *
 * Ajaxで呼び出される
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */


if ($dbData) {
	$this->BcBaser->element('table_comment', array('dbData' => $dbData));
}
