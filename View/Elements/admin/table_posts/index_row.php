<?php
/**
 * [ADMIN] テーブル記事 一覧　行
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$classies = array();
if (!$this->Table->allowPublish($data)) {
	$classies = array('unpublish', 'disablerow');
} else {
	$classies = array('publish');
}
$class = ' class="' . implode(' ', $classies) . '"';
?>


<tr<?php echo $class; ?>>
	<td class="row-tools">
		<?php if ($this->BcBaser->isAdminUser()): ?>
			<?php echo $this->BcForm->checkbox('ListTool.batch_targets.' . $data['TablePost']['id'], array('type' => 'checkbox', 'class' => 'batch-targets', 'value' => $data['TablePost']['id'])) ?>
		<?php endif ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '非公開', 'class' => 'btn')), array('action' => 'ajax_unpublish', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '非公開', 'class' => 'btn-unpublish')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '公開', 'class' => 'btn')), array('action' => 'ajax_publish', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '公開', 'class' => 'btn-publish')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_check.png', array('width' => 24, 'height' => 24, 'alt' => '確認', 'class' => 'btn')), '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no'], array('title' => '確認', 'target' => '_blank')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_edit.png', array('width' => 24, 'height' => 24, 'alt' => '編集', 'class' => 'btn')), array('action' => 'edit', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '編集')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_copy.png', array('width' => 24, 'height' => 24, 'alt' => 'コピー', 'class' => 'btn')), array('action' => 'ajax_copy', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => 'コピー', 'class' => 'btn-copy')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
	</td>
	<td><?php echo $data['TablePost']['no']; ?></td>
	<td><?php echo $this->BcTime->format('Y-m-d', $data['TablePost']['posts_date']); ?></td>
	<td>
		<?php if (!empty($data['TableCategory']['title'])): ?>
			<?php echo $data['TableCategory']['title']; ?>
		<?php endif; ?>
		<?php if ($data['TableContent']['tag_use'] && !empty($data['TableTag'])): ?>
			<?php $tags = Hash::extract($data['TableTag'], '{n}.name') ?>
			<span class="tag"><?php echo implode('</span><span class="tag">', $tags) ?></span>
		<?php endif ?>
		<br />
		<?php $this->BcBaser->link($data['TablePost']['name'], array('action' => 'edit', $data['TableContent']['id'], $data['TablePost']['id'])) ?>
	</td>
	<td>
		<?php echo $this->BcBaser->getUserName($data['User']) ?>
	</td>
	<td style="text-align:center"><?php echo $this->BcText->booleanMark($data['TablePost']['status']); ?></td>
	<?php if ($data['TableContent']['comment_use']): ?>
		<td>
			<?php $comment = count($data['TableComment']) ?>
			<?php if ($comment): ?>
				<?php $this->BcBaser->link($comment, array('controller' => 'table_comments', 'action' => 'index', $data['TableContent']['id'], $data['TablePost']['id'])) ?>
			<?php else: ?>
				<?php echo $comment ?>
			<?php endif ?>
		</td>
	<?php endif ?>
	<td style="white-space:nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['TablePost']['created']); ?><br />
		<?php echo $this->BcTime->format('Y-m-d', $data['TablePost']['modified']); ?>
	</td>
</tr>
