<?php
/**
 * [ADMIN] テーブル記事 一覧　テーブル
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<!-- pagination -->
<?php $this->BcBaser->element('pagination') ?>

<!-- list -->
<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
<thead>

	<th>詳細</th>
	<th>プレビュー</th>
	<th>公開</th>

	<th>name</th>
	<th>content</th>
	<th>detail</th>
	<th>content_draft</th>
	<th>detail_draft</th>

	<th>eye_catch</th>

	<th>category</th>

	<th>tags</th>

	<th>user</th>

	<th>exclude_search</th>

	<th>posts_date</th>
	<th>publish_begin</th>
	<th>publish_end</th>
	<th>created</th>
	<th>modified</th>

	<th>コピー</th>
	<th>削除</th>

</thead>
<tbody>
	<?php if (!empty($posts)): ?>

		<?php foreach ($posts as $data): ?>

			<?php $this->BcBaser->element('table_posts/index_row', array('data' => $data)) ?>

		<?php endforeach; ?>

	<?php else: ?>

		<tr>
			<td colspan="9"><p class="no-data">データが見つかりませんでした。</p></td>
		</tr>

	<?php endif; ?>
</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
