<?php
/**
 * [ADMIN] テーブル記事 フォーム
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$this->BcBaser->css('admin/ckeditor/editor', array('inline' => true));
$statuses = array(0 => '非公開', 1 => '公開');
$this->BcBaser->link('&nbsp;', array('controller' => 'table', 'action' => 'preview', $tableContent['TableContent']['id'], $previewId, 'view'), array('style' => 'display:none', 'id' => 'LinkPreview'));
?>

<div id="CreatePreviewUrl" style="display:none"><?php echo $this->BcBaser->url(array('controller' => 'table', 'action' => 'preview', $tableContent['TableContent']['id'], $previewId, 'create')) ?></div>
<div id="AddTagUrl" style="display:none"><?php echo $this->BcBaser->url(array('plugin' => 'table', 'controller' => 'table_tags', 'action' => 'ajax_add')) ?></div>
<div id="AddTableCategoryUrl" style="display:none"><?php echo $this->BcBaser->url(array('plugin' => 'table', 'controller' => 'table_categories', 'action' => 'ajax_add', $tableContent['TableContent']['id'])) ?></div>
<?php echo $this->BcForm->input('UseContent', array('type' => 'hidden', 'value' => $tableContent['TableContent']['use_content'])) ?>


<script type="text/javascript">
	$(window).load(function() {
		$("#TablePostName").focus();
	});
$(function(){

	$("input[type=text]").each(function(){
		$(this).keypress(function(e){
			if(e.which && e.which === 13) {
				return false;
			}
			return true;
		});
	});

/**
 * プレビューボタンクリック時イベント
 */
	var useContent = Number($("#UseContent").val());
	$("#BtnPreview").click(function(){

		var detail = $("#TablePostDetail").val();
		if(typeof editor_detail_tmp != "undefined") {
			$("#TablePostDetail").val(editor_detail_tmp.getData());
		}

		$.ajax({
			type: "POST",
			url: $("#CreatePreviewUrl").html(),
			data: $("#TablePostForm").serialize(),
			success: function(result){
				if(result) {
					$("#LinkPreview").trigger("click");
				} else {
					alert('プレビューの読み込みに失敗しました。');
				}
			}
		});

		$("#TablePostDetail").val(detail);

		return false;

	});

	$("#LinkPreview").colorbox({width:"90%", height:"90%", iframe:true});
/**
 * フォーム送信時イベント
 */
	$("#BtnSave").click(function(){
		if(typeof editor_detail_tmp != "undefined") {
			editor_detail_tmp.execCommand('synchronize');
		}
		$("#TablePostMode").val('save');
		$("#TablePostForm").submit();
		return false;
	});
/**
 * テーブルタグ追加
 */
	$("#TableTagName").keypress(function(ev) {
		if ((ev.which && ev.which === 13) || (ev.keyCode && ev.keyCode === 13)) {
			$("#BtnAddTableTag").click();
			return false;
		} else {
			return true;
		}
	});
	$("#BtnAddTableTag").click(function(){
		if(!$("#TableTagName").val()) {
			return false;
		}
		$.ajax({
			type: "POST",
			url: $("#AddTagUrl").html(),
			data: {'data[TableTag][name]': $("#TableTagName").val()},
			dataType: 'html',
			beforeSend: function() {
				$("#BtnAddTableTag").attr('disabled', 'disabled');
				$("#TagLoader").show();
			},
			success: function(result){
				if(result) {
					$("#TableTags").append(result);
					$("#TableTagName").val('');
				} else {
					alert('テーブルタグの追加に失敗しました。既に登録されていないか確認してください。');
				}
			},
			error: function(){
				alert('テーブルタグの追加に失敗しました。');
			},
			complete: function(xhr, textStatus) {
				$("#BtnAddTableTag").removeAttr('disabled');
				$("#TagLoader").hide();
				$("#TableTags").effect("highlight",{},1500);
			}
		});
		return false;
	});
/**
 * テーブルカテゴリ追加
 */
	$("#BtnAddTableCategory").click(function(){
		var category = prompt("新しいテーブルカテゴリを入力してください。");
		if(!category) {
			return false;
		}
		$.ajax({
			type: "POST",
			url: $("#AddTableCategoryUrl").html(),
			data: {'data[TableCategory][name]': category},
			dataType: 'script',
			beforeSend: function() {
				$("#BtnAddTableCategory").attr('disabled', 'disabled');
				$("#TableCategoryLoader").show();
			},
			success: function(result){
				if(result) {
					$("#TablePostTableCategoryId").append($('<option />').val(result).html(category));
					$("#TablePostTableCategoryId").val(result);
				} else {
					alert('テーブルカテゴリの追加に失敗しました。既に登録されていないか確認してください。');
				}
			},
			error: function(XMLHttpRequest, textStatus){
				if(XMLHttpRequest.responseText) {
					alert('テーブルカテゴリの追加に失敗しました。\n\n' + XMLHttpRequest.responseText);
				} else {
					alert('テーブルカテゴリの追加に失敗しました。\n\n' + XMLHttpRequest.statusText);
				}
			},
			complete: function(xhr, textStatus) {
				$("#BtnAddTableCategory").removeAttr('disabled');
				$("#TableCategoryLoader").hide();
				$("#TablePostTableCategoryId").effect("highlight",{},1500);
			}
		});
		return false;
	});
});
</script>


<?php if ($this->action == 'admin_edit'): ?>
	<div class="em-box align-left">
		<?php if ($this->BcForm->value('TablePost.status') && $tableContent['TableContent']['status']): ?>
			この記事のURL　：<?php
			$this->BcBaser->link(
				$this->BcBaser->getUri('/' . $tableContent['TableContent']['name'] . '/archives/' . $this->BcForm->value('TablePost.no')), '/' . $tableContent['TableContent']['name'] . '/archives/' . $this->BcForm->value('TablePost.no'))
			?>
		<?php else: ?>
			この記事のURL　：<?php echo $this->BcBaser->getUri('/' . $tableContent['TableContent']['name'] . '/archives/' . $this->BcForm->value('TablePost.no')) ?>
		<?php endif ?>
			<br />
			プレビュー用URL：<?php $this->BcBaser->link(
				$this->BcBaser->getUri(array('controller' => 'table', 'action'=>'preview', $tableContent['TableContent']['id'], $this->data['TablePost']['id'], 'view')),
				$this->BcBaser->getUri(array('controller' => 'table', 'action'=>'preview', $tableContent['TableContent']['id'], $this->data['TablePost']['id'], 'view')),
				array('target' => '_blank')
			); ?>
	</div>
<?php endif ?>


<?php /* TableContent.idを第一引数にしたいが為にURL直書き */ ?>
<?php if ($this->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('TablePost', array('type' => 'file', 'url' => array('controller' => 'table_posts', 'action' => 'add', $tableContent['TableContent']['id']), 'id' => 'TablePostForm')) ?>
	<?php elseif ($this->action == 'admin_edit'): ?>
	<?php echo $this->BcForm->create('TablePost', array('type' => 'file', 'url' => array('controller' => 'table_posts', 'action' => 'edit', $tableContent['TableContent']['id'], $this->BcForm->value('TablePost.id'), 'id' => false), 'id' => 'TablePostForm')) ?>
<?php endif; ?>
<?php echo $this->BcForm->input('TablePost.id', array('type' => 'hidden')) ?>
<?php echo $this->BcForm->input('TablePost.table_content_id', array('type' => 'hidden', 'value' => $tableContent['TableContent']['id'])) ?>
<?php echo $this->BcForm->hidden('TablePost.mode') ?>


<?php if (empty($tableContent['TableContent']['use_content'])): ?>
	<?php echo $this->BcForm->hidden('TablePost.content') ?>
<?php endif ?>


<!-- form -->
<div class="section">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table">
	<?php if ($this->action == 'admin_edit'): ?>
		<tr>
			<th class="col-head" style="width:53px"><?php echo $this->BcForm->label('TablePost.no', 'NO') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->value('TablePost.no') ?>
				<?php echo $this->BcForm->input('TablePost.no', array('type' => 'hidden')) ?>
			</td>
		</tr>
	<?php endif; ?>
	<?php if ($categories): ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.table_category_id', 'カテゴリー') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TablePost.table_category_id', array('type' => 'select', 'options' => $categories, 'escape' => false)) ?>&nbsp;
				<?php if($newCatAddable): ?>
					<?php echo $this->BcForm->button('新しいカテゴリを追加', array('id' => 'BtnAddTableCategory')) ?>
				<?php endif ?>
				<?php $this->BcBaser->img('admin/ajax-loader-s.gif', array('style' => 'vertical-align:middle;display:none', 'id' => 'TableCategoryLoader', 'class' => 'loader')) ?>
				<?php echo $this->BcForm->error('TablePost.table_category_id') ?>
			</td>
		</tr>
	<?php endif ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.name', 'タイトル') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TablePost.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
				<?php echo $this->BcForm->error('TablePost.name') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.eye_catch', 'アイキャッチ画像') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->file('TablePost.eye_catch', array('imgsize' => 'thumb')) ?>
				<?php echo $this->BcForm->error('TablePost.eye_catch') ?>
			</td>
		</tr>
	<?php if (!empty($tableContent['TableContent']['use_content'])): ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.content', '概要') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->ckeditor('TablePost.content', array(
					'editorWidth' => 'auto',
					'editorHeight' => '120px',
					'editorToolType' => 'simple',
					'editorEnterBr' => @$siteConfig['editor_enter_br']
				)); ?>
				<?php echo $this->BcForm->error('TablePost.content') ?>
			</td>
		</tr>
	<?php endif ?>
	</table>
</div>

<div class="section" style="text-align: center">
	<?php
	echo $this->BcForm->editor('TablePost.detail', array_merge(array(
		'editor' => @$siteConfig['editor'],
		'editorUseDraft' => true,
		'editorDraftField' => 'detail_draft',
		'editorWidth' => 'auto',
		'editorHeight' => '480px',
		'editorEnterBr' => @$siteConfig['editor_enter_br']
			), $editorOptions))
	?>
		<?php echo $this->BcForm->error('TablePost.detail') ?>
</div>

<div class="section">
	<table cellpadding="0" cellspacing="0" class="form-table">
		<?php if (!empty($tableContent['TableContent']['tag_use'])): ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableTag.TableTag', 'タグ') ?></th>
			<td class="col-input">
				<div class="clearfix" id="TableTags" style="padding:5px">
					<?php echo $this->BcForm->input('TableTag.TableTag', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $this->BcForm->getControlSource('TablePost.table_tag_id'))); ?>
				</div>
				<?php echo $this->BcForm->error('TableTag.TableTag') ?>
				<?php echo $this->BcForm->input('TableTag.name', array('type' => 'text')) ?>
				<?php echo $this->BcForm->button('新しいタグを追加', array('id' => 'BtnAddTableTag')) ?>
				<?php $this->BcBaser->img('admin/ajax-loader-s.gif', array('style' => 'vertical-align:middle;display:none', 'id' => 'TagLoader', 'class' => 'loader')) ?>
			</td>
		</tr>
		<?php endif ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.status', '公開状態') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TablePost.status', array('type' => 'radio', 'options' => $statuses)) ?>
				<?php echo $this->BcForm->error('TablePost.status') ?>
				&nbsp;&nbsp;
				<?php echo $this->BcForm->dateTimePicker('TablePost.publish_begin', array('size' => 12, 'maxlength' => 10), true) ?>
				&nbsp;〜&nbsp;
				<?php echo $this->BcForm->dateTimePicker('TablePost.publish_end', array('size' => 12, 'maxlength' => 10), true) ?><br />
				<?php echo $this->BcForm->input('TablePost.exclude_search', array('type' => 'checkbox', 'label' => 'サイト内検索の検索結果より除外する')) ?>
				<?php echo $this->BcForm->error('TablePost.publish_begin') ?>
				<?php echo $this->BcForm->error('TablePost.publish_end') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.user_id', '作成者') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php if (isset($user) && $user['user_group_id'] == Configure::read('BcApp.adminGroupId')): ?>
					<?php echo $this->BcForm->input('TablePost.user_id', array(
						'type' => 'select',
						'options' => $users
					)); ?>
					<?php echo $this->BcForm->error('TablePost.user_id') ?>
				<?php else: ?>
					<?php if (isset($users[$this->BcForm->value('TablePost.user_id')])): ?>
					<?php echo $users[$this->BcForm->value('TablePost.user_id')] ?>
					<?php endif ?>
					<?php echo $this->BcForm->hidden('TablePost.user_id') ?>
				<?php endif ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TablePost.posts_date', '投稿日') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->dateTimePicker('TablePost.posts_date', array('size' => 12, 'maxlength' => 10), true) ?>
				<?php echo $this->BcForm->error('TablePost.posts_date') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>

<!-- button -->
<div class="submit">
	<?php if ($this->action == 'admin_add'): ?>
		<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button', 'id' => 'BtnSave')) ?>
		<?php echo $this->BcForm->button('保存前確認', array('div' => false, 'class' => 'button', 'id' => 'BtnPreview')) ?>
	<?php elseif ($this->action == 'admin_edit'): ?>
		<?php if ($editable): ?>
		<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button', 'id' => 'BtnSave')) ?>
		<?php endif ?>
		<?php echo $this->BcForm->button('保存前確認', array('div' => false, 'class' => 'button', 'id' => 'BtnPreview')) ?>
		<?php if ($editable): ?>
		<?php $this->BcBaser->link('削除', array('action' => 'delete', $tableContent['TableContent']['id'], $this->BcForm->value('TablePost.id')), array('class' => 'button'), sprintf('%s を本当に削除してもいいですか？', $this->BcForm->value('TablePost.name')), false); ?>
		<?php endif ?>
	<?php endif ?>
</div>

<?php echo $this->BcForm->end() ?>
