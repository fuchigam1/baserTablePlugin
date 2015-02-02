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

<script>
$(function() {
	// TODO: ちゃんとスタイルを調整する
	$('[data-table-editor="editable"]').prop('contenteditable', true);

	// TODO: input要素じゃなくてcontenteditableのままできるとベター
	$('[data-table-editor="datetime"] input').datepicker();
});
</script>

<?php # チェックボックス ?>
<?php # if ($this->BcBaser->isAdminUser()): ?>
<?php # echo $this->BcForm->checkbox('ListTool.batch_targets.' . $data['TablePost']['id'], array('type' => 'checkbox', 'class' => 'batch-targets', 'value' => $data['TablePost']['id'])) ?>
<?php # endif ?>


<tr<?php echo $class; ?> data-id="<?php echo $data['TablePost']['id'] ?>">
	<td>
		<!-- 詳細 -->
		<?php $this->BcBaser->link('詳細', array('action' => 'edit', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '詳細')) ?>
	</td>
	<td>
		<!-- プレビュー -->
		<?php $this->BcBaser->link('プレビュー', '/' . $data['TableContent']['name'] . '/archives/' . $data['TablePost']['no'], array('title' => 'プレビュー', 'target' => '_blank')) ?>
	</td>
	<td class="row-tools">
		<!-- 公開/非公開 -->
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_unpublish.png', array('width' => 24, 'height' => 24, 'alt' => '非公開', 'class' => 'btn')), array('action' => 'ajax_unpublish', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '非公開', 'class' => 'btn-unpublish')) ?>
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_publish.png', array('width' => 24, 'height' => 24, 'alt' => '公開', 'class' => 'btn')), array('action' => 'ajax_publish', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '公開', 'class' => 'btn-publish')) ?>
	</td>

	<td data-table-editor="editable" data-post-field="name"><?php echo $data['TablePost']['name'] ?></td>
	<td data-table-editor="editable" data-post-field="content"><?php echo $data['TablePost']['content'] ?></td>
	<td data-table-editor="editable" data-post-field="detail"><?php echo $data['TablePost']['detail'] ?></td>
	<td data-table-editor="editable" data-post-field="content_draft"><?php echo $data['TablePost']['content_draft'] ?></td>
	<td data-table-editor="editable" data-post-field="detail_draft"><?php echo $data['TablePost']['detail_draft'] ?></td>

	<td data-table-editor="file" data-post-field="eye_catch">
		<input type="file">
		<?php if ($data['TablePost']['eye_catch']): ?>
			<!-- TODO: newsのところはtable名 -->
			<img src="/files/table/news/table_posts/<?php echo $data['TablePost']['eye_catch'] ?>">
		<?php endif; ?>
	</td>

	<td data-table-editor="selectbox" data-post-field="table_category" data-post-value="<?php echo $data['TableCategory']['name'] ?>">
		<select>
			<!-- TODO: ちゃんとリストアップする -->
			<option value="<?php echo $data['TableCategory']['id'] ?>"><?php echo $data['TableCategory']['title'] ?></option>
		</select>
	</td>

	<td data-table-editor="tagging" data-post-field="tags" data-post-value="***">
		<!-- TODO: data-post-value はPOST送信の形式が望ましい気がする -->
		<!-- TODO: Evernoteのタグっぽい編集機能にする -->
		<?php foreach($data['TableTag'] as $tag): ?>
			<span data-tag-id="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></span>
		<?php endforeach; ?>
	</td>

	<td data-table-editor="selectbox" data-post-field="user" data-post-value="<?php echo $data['user']['name'] ?>">
		<select>
			<!-- TODO: ちゃんとリストアップする -->
			<option value="<?php echo $data['user']['id'] ?>"><?php echo $data['TableCategory']['name'] ?></option>
		</select>
	</td>

	<td data-table-editor="boolean" data-post-field="exclude_search"><?php echo $data['TablePost']['exclude_search'] ?></td>

	<td data-table-editor="datetime" data-post-field="posts_date" data-post-value="<?php echo $data['TablePost']['posts_date'] ?>"><input type="datetime" value="<?php echo $data['TablePost']['posts_date'] ?>"></td>
	<td data-table-editor="datetime" data-post-field="publish_begin" data-post-value="<?php echo $data['TablePost']['publish_begin'] ?>"><input type="datetime" value="<?php echo $data['TablePost']['publish_begin'] ?>"></td>
	<td data-table-editor="datetime" data-post-field="publish_end" data-post-value="<?php echo $data['TablePost']['publish_end'] ?>"><input type="datetime" value="<?php echo $data['TablePost']['publish_end'] ?>"></td>
	<td data-table-editor="datetime" data-post-field="created" data-post-value="<?php echo $data['TablePost']['created'] ?>"><input type="datetime" value="<?php echo $data['TablePost']['created'] ?>"></td>
	<td data-table-editor="datetime" data-post-field="modified" data-post-value="<?php echo $data['TablePost']['modified'] ?>"><input type="datetime" value="<?php echo $data['TablePost']['modified'] ?>"></td>
	<td>
		<!-- コピー -->
		<?php $this->BcBaser->link('コピー', array('action' => 'ajax_copy', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => 'コピー', 'class' => 'btn-copy')) ?>
	</td>
	<td>
		<!-- 削除ボタン -->
		<?php $this->BcBaser->link($this->BcBaser->getImg('admin/icn_tool_delete.png', array('width' => 24, 'height' => 24, 'alt' => '削除', 'class' => 'btn')), array('action' => 'ajax_delete', $data['TableContent']['id'], $data['TablePost']['id']), array('title' => '削除', 'class' => 'btn-delete')) ?>
	</td>
</tr>
