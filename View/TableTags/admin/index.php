<?php
/**
 * [ADMIN] テーブルタグ一覧
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
	$.baserAjaxDataList.config.methods.del.confirm = 'このデータを本当に削除してもいいですか？\nこのタグに関連する記事は削除されません。';
	$.baserAjaxDataList.init();
	$.baserAjaxBatch.init({ url: $("#AjaxBatchUrl").html()});
});
</script>

<div id="AjaxBatchUrl" style="display:none"><?php $this->BcBaser->url(array('controller' => 'table_tags', 'action' => 'ajax_batch')) ?></div>
<div id="AlertMessage" class="message" style="display:none"></div>
<div id="DataList"><?php $this->BcBaser->element('table_tags/index_list') ?></div>