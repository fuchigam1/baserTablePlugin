<?php
/**
 * [ADMIN] テーブルコンテンツ 一覧　行
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<?php if (!$data['TableContent']['status']): ?>
	<?php $class = ' class="unpublish disablerow"'; ?>
	<?php else: ?>
	<?php $class = ' class="publish"'; ?>
<?php endif; ?>
<tr <?php echo $class; ?>>
	<td class="row-tools">
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_check.png', array('width' => 24, 'height' => 24, 'alt' => '確認', 'class' => 'btn')), '/' . $data['TableContent']['name'], array('title' => '確認', 'target' => '_blank')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_manage.png', array('width' => 24, 'height' => 24, 'alt' => '管理', 'class' => 'btn')), array('controller' => 'table_posts', 'action' => 'index', $data['TableContent']['id']), array('title' => '管理')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')), array('action' => 'edit', $data['TableContent']['id']), array('title' => '編集')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_copy.png', array('width' => 24, 'height' => 24, 'alt' => 'コピー', 'class' => 'btn')), array('action' => 'ajax_copy', $data['TableContent']['id']), array('title' => 'コピー', 'class' => 'btn-copy')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $data['TableContent']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
	</td>
	<td><?php echo $data['TableContent']['id']; ?></td>
	<td><?php $this->BcBaser->link($data['TableContent']['name'], array('action' => 'edit', $data['TableContent']['id'])) ?></td>
	<td><?php echo $data['TableContent']['title'] ?></td>
	<td><?php echo $this->BcTime->format('Y-m-d', $data['TableContent']['created']); ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['TableContent']['modified']); ?></td>
</tr>