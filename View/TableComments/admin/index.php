<?php
/**
 * [ADMIN] テーブル記事コメント 一覧
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
		$.baserAjaxDataList.init();
	$.baserAjaxBatch.init({ url: $("#AjaxBatchUrl").html()});
	});
</script>
<?php if (!empty($this->params['pass'][1])): ?>
	<div id="AjaxBatchUrl" style="display:none"><?php $this->BcBaser->url(array('controller' => 'table_comments', 'action' => 'ajax_batch', $tableContent['TableContent']['id'], $this->params['pass'][1])) ?></div>
	<?php else: ?>
	<div id="AjaxBatchUrl" style="display:none"><?php $this->BcBaser->url(array('controller' => 'table_comments', 'action' => 'ajax_batch', $tableContent['TableContent']['id'], 0)) ?></div>
<?php endif ?>
<div id="AlertMessage" class="message" style="display:none"></div>
<div id="DataList"><?php $this->BcBaser->element('table_comments/index_list') ?></div>