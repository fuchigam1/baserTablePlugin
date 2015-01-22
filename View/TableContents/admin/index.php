<?php
/**
 * [ADMIN] テーブルコンテンツ 一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$this->BcBaser->js(array(
	'admin/jquery.baser_ajax_data_list',
	'admin/jquery.baser_ajax_batch',
	'admin/baser_ajax_data_list_config',
	'admin/baser_ajax_batch_config'
));
?>

<script type="text/javascript">
	$(function(){
		$.baserAjaxDataList.config.methods.del.confirm = '削除を行うと関連する記事やカテゴリは全て削除されてしまい元に戻す事はできません。\n本当に削除してもいいですか？';
		$.baserAjaxDataList.init();
		$.baserAjaxBatch.init({ url: $("#AjaxBatchUrl").html()});
	});
</script>

<div id="AlertMessage" class="message" style="display:none"></div>
<div id="AjaxBatchUrl" style="display:none"><?php $this->BcBaser->url(array('controller' => 'table_contents', 'action' => 'ajax_batch')) ?></div>
<div id="DataList"><?php $this->BcBaser->element('table_contents/index_list') ?></div>