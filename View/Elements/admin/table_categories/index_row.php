<?php
/**
 * [ADMIN] テーブルカテゴリ 一覧　行
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$allowOwners = array();
if (isset($user['user_group_id'])) {
	$allowOwners = array('', $user['user_group_id']);
}
?>


<tr<?php echo $rowGroupClass ?>>
	<td class="row-tools">
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php echo $this->BcForm->checkbox('ListTool.batch_targets.' . $data['TableCategory']['id'], array('type' => 'checkbox', 'class' => 'batch-targets', 'value' => $data['TableCategory']['id'])) ?>
		<?php endif ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_check.png', array('width' => 24, 'height' => 24, 'alt' => '確認', 'class' => 'btn')), $this->Table->getCategoryUrl($data['TableCategory']['id']), array('title' => '確認', 'target' => '_blank')) ?>
		<?php if (in_array($data['TableCategory']['owner_id'], $allowOwners) || (isset($user['user_group_id']) && $user['user_group_id'] == Configure::read('BcApp.adminGroupId'))): ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')), array('action' => 'edit', $tableContent['TableContent']['id'], $data['TableCategory']['id']), array('title' => '編集')) ?>
			<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $tableContent['TableContent']['id'], $data['TableCategory']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
		<?php endif ?>
	</td>
	<td><?php echo $data['TableCategory']['no'] ?></td>
	<td>
		<?php if (in_array($data['TableCategory']['owner_id'], $allowOwners) || $this->BcAdmin->isSystemAdmin()): ?>
			<?php $this->BcBaser->link($data['TableCategory']['name'], array('action' => 'edit', $tableContent['TableContent']['id'], $data['TableCategory']['id'])) ?>
		<?php else: ?>
			<?php echo $data['TableCategory']['name'] ?>
		<?php endif ?>
		<?php if ($this->BcBaser->siteConfig['category_permission']): ?>
			<br />
			<?php echo $this->BcText->arrayValue($data['TableCategory']['owner_id'], $owners) ?>
		<?php endif ?>
	</td>
	<td><?php echo $data['TableCategory']['title'] ?></td>
	<td><?php echo $this->BcTime->format('Y-m-d', $data['TableCategory']['created']); ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['TableCategory']['modified']); ?></td>
</tr>
