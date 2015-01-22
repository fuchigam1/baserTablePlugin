<?php
/**
 * [ADMIN] テーブルタグ一覧　行
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<tr>
	<td class="row-tools">
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php echo $this->BcForm->checkbox('ListTool.batch_targets.' . $data['TableTag']['id'], array('type' => 'checkbox', 'class' => 'batch-targets', 'value' => $data['TableTag']['id'])) ?>
		<?php endif ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')), array('action' => 'edit', $data['TableTag']['id']), array('title' => '編集')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $data['TableTag']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
	</td>
	<td><?php echo $data['TableTag']['id'] ?></td>
	<td><?php $this->BcBaser->link($data['TableTag']['name'], array('action' => 'edit', $data['TableTag']['id'])) ?></td>
	<td><?php echo $this->BcTime->format('Y-m-d', $data['TableTag']['created']); ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['TableTag']['modified']); ?></td>
</tr>
