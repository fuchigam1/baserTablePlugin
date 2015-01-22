<?php
/**
 * [ADMIN] テーブル記事コメント 一覧　行
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */

if (!$data['TableComment']['status']) {
	$class = ' class="disablerow unpublish"';
} else {
	$class = ' class="publish"';
}
?>


<tr<?php echo $class; ?>>
	<td class="row-tools">
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php echo $this->BcForm->checkbox('ListTool.batch_targets.' . $data['TableComment']['id'], array('type' => 'checkbox', 'class' => 'batch-targets', 'value' => $data['TableComment']['id'])) ?>
		<?php endif ?>
		<?php if (!empty($this->params['pass'][1])): ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '非公開', 'class' => 'btn')), array('action' => 'ajax_unpublish', $tableContent['TableContent']['id'], $data['TableComment']['table_post_id'], $data['TableComment']['id']), array('title' => '非公開', 'class' => 'btn-unpublish')) ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '公開', 'class' => 'btn')), array('action' => 'ajax_publish', $tableContent['TableContent']['id'], $data['TableComment']['table_post_id'], $data['TableComment']['id']), array('title' => '公開', 'class' => 'btn-publish')) ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $tableContent['TableContent']['id'], $data['TableComment']['table_post_id'], $data['TableComment']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
		<?php else: ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '非公開', 'class' => 'btn')), array('action' => 'ajax_unpublish', $tableContent['TableContent']['id'], 0, $data['TableComment']['id']), array('title' => '非公開', 'class' => 'btn-unpublish')) ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '公開', 'class' => 'btn')), array('action' => 'ajax_publish', $tableContent['TableContent']['id'], 0, $data['TableComment']['id']), array('title' => '公開', 'class' => 'btn-publish')) ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $tableContent['TableContent']['id'], 0, $data['TableComment']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
		<?php endif ?>
	</td>
	<td><?php echo $data['TableComment']['no'] ?></td>
	<td>
		<?php if (!empty($data['TableComment']['url'])): ?>
			<?php $this->BcBaser->link($data['TableComment']['name'], $data['TableComment']['url'], array('target' => '_blank')) ?>
		<?php else: ?>
			<?php echo $data['TableComment']['name'] ?>
		<?php endif ?>
	</td>
	<td>
		<?php if (!empty($data['TableComment']['email'])): ?>
			<?php $this->BcBaser->link($data['TableComment']['email'], 'mailto:' . $data['TableComment']['email']) ?>
		<?php endif; ?>
		<br />
		<?php echo $this->BcText->autoLinkUrls($data['TableComment']['url']) ?>
	</td>
	<td>
		<strong>
			<?php $this->BcBaser->link($data['TablePost']['name'], array('controller' => 'table_posts', 'action' => 'edit', $tableContent['TableContent']['id'], $data['TablePost']['id'])) ?>
		</strong><br />
		<?php echo nl2br($this->BcText->autoLinkUrls($data['TableComment']['message'])) ?>
	</td>
	<td style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['TableComment']['created']); ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['TableComment']['modified']); ?>
	</td>
</tr>
