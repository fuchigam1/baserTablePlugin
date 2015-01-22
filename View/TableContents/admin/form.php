<?php
/**
 * [ADMIN] テーブルコンテンツ フォーム
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>


<script type="text/javascript">
	$(window).load(function() {
		$("#TableContentName").focus();
	});
$(function(){
	$("#EditLayoutTemplate").click(function(){
		if(confirm('テーブル設定を保存して、レイアウトテンプレート '+$("#TableContentLayout").val()+' の編集画面に移動します。よろしいですか？')){
				$("#TableContentEditLayoutTemplate").val(1);
				$("#TableContentEditTableTemplate").val('');
				$("#TableContentAdminEditForm").submit();
			}
		});
	$("#EditTableTemplate").click(function(){
		if(confirm('テーブル設定を保存して、コンテンツテンプレート '+$("#TableContentTemplate").val()+' の編集画面に移動します。よろしいですか？')){
				$("#TableContentEditLayoutTemplate").val('');
				$("#TableContentEditTableTemplate").val(1);
				$("#TableContentAdminEditForm").submit();
			}
		});
	});
</script>

<?php if ($this->action == 'admin_edit'): ?>
	<div class="em-box align-left">
		<?php if ($this->BcForm->value('TableContent.status')): ?>
			<strong>このテーブルのURL：<?php $this->BcBaser->link($this->BcBaser->getUri('/' . $tableContent['TableContent']['name'] . '/index'), '/' . $tableContent['TableContent']['name'] . '/index') ?></strong>
		<?php else: ?>
			<strong>このテーブルのURL：<?php echo $this->BcBaser->getUri('/' . $tableContent['TableContent']['name'] . '/index') ?></strong>
		<?php endif ?>
	</div>
<?php endif ?>

<!-- form -->
<h2>基本項目</h2>


<?php echo $this->BcForm->create('TableContent') ?>
<div class="section">
	<table cellpadding="0" cellspacing="0" class="form-table">
		<?php if ($this->action == 'admin_edit'): ?>
			<tr>
				<th class="col-head"><?php echo $this->BcForm->label('TableContent.id', 'NO') ?></th>
				<td class="col-input">
					<?php echo $this->BcForm->value('TableContent.id') ?>
					<?php echo $this->BcForm->input('TableContent.id', array('type' => 'hidden')) ?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.name', 'テーブルアカウント名') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255)) ?>
				<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpCategoryFilter', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
				<?php echo $this->BcForm->error('TableContent.name') ?>
				<div id="helptextCategoryFilter" class="helptext">
					<ul>
						<li>テーブルのURLに利用します。<br />
							(例)テーブルアカウント名が test の場合・・・http://example/test/</li>
						<li>半角英数字で入力してください。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.title', 'テーブルタイトル') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.title', array('type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true)) ?>
				<?php echo $this->BcForm->error('TableContent.title') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.description', 'テーブル説明文') ?></th>
			<td class="col-input">
				<?php
				echo $this->BcForm->ckeditor('TableContent.description', array(
					'editorWidth' => 'auto',
					'editorHeight' => '120px',
					'editorToolType' => 'simple',
					'editorEnterBr' => @$siteConfig['editor_enter_br']
				))
				?>
<?php echo $this->BcForm->error('TableContent.description') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.exclude_search', '公開設定') ?></th>
			<td class="col-input">

				<?php echo $this->BcForm->input('TableContent.status', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('公開'))) ?>
<?php echo $this->BcForm->error('TableContent.status') ?>
<?php echo $this->BcForm->input('TableContent.exclude_search', array('type' => 'checkbox', 'label' => 'このテーブルのトップページをサイト内検索の検索結果より除外する')) ?>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>
<h2 class="btn-slide-form"><a href="javascript:void(0)" id="formOption">オプション</a></h2>
<div class="section">
	<table cellpadding="0" cellspacing="0" class="form-table slide-body" id="formOptionBody">
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.list_count', '一覧表示件数') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.list_count', array('type' => 'text', 'size' => 20, 'maxlength' => 255)) ?>&nbsp;件&nbsp;
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpListCount', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.list_count') ?>
				<div id="helptextListCount" class="helptext">
					<ul>
						<li>公開サイトの一覧に表示する件数を指定します。</li>
						<li>半角数字で入力してください。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.list_direction', '一覧に表示する順番') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.list_direction', array('type' => 'select', 'options' => array('DESC' => '新しい記事順', 'ASC' => '古い記事順'))) ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpListDirection', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.list_direction') ?>
				<div id="helptextListDirection" class="helptext">
					<ul>
						<li>公開サイトの一覧における記事の並び方向を指定します。</li>
						<li>新しい・古いの判断は投稿日が基準となります。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.list_count', 'RSSフィード出力件数') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.feed_count', array('type' => 'text', 'size' => 20, 'maxlength' => 255)) ?>&nbsp;件&nbsp;
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpFeedCount', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.feed_count') ?>
				<div id="helptextFeedCount" class="helptext">
					<ul>
						<li>RSSフィードに出力する件数を指定します。</li>
						<li>半角数字で入力してください。</li>
							<?php if ($this->action == 'admin_edit'): ?>
							<li>RSSフィードのURLは
							<?php $this->BcBaser->link(Router::url('/' . $this->BcForm->value('TableContent.name') . '/index.rss', true), '/' . $this->BcForm->value('TableContent.name') . '/index.rss', array('target' => '_blank')) ?>
								となります。</li>
							<?php endif ?>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.comment_use', 'コメント受付機能') ?></th>
			<td class="col-input">
<?php echo $this->BcForm->input('TableContent.comment_use', array('type' => 'checkbox', 'label' => '利用する')) ?>
<?php echo $this->BcForm->error('TableContent.comment_use') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.comment_approve', 'コメント承認機能') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.comment_approve', array('type' => 'checkbox', 'label' => '利用する')) ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpCommentApprove', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.comment_approve') ?>
				<div id="helptextCommentApprove" class="helptext">承認機能を利用すると、コメントが投稿されてもすぐに公開されず、管理者側で確認する事ができます。</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('MailContent.auth_capthca', 'コメントイメージ認証') ?></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableContent.auth_captcha', array('type' => 'checkbox', 'label' => '利用する')) ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpAuthCaptcha', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.auth_captcha') ?>
				<div id="helptextAuthCaptcha" class="helptext">
					<ul>
						<li>テーブルコメント送信の際、表示された画像の文字入力させる事で認証を行ないます。</li>
						<li>スパムなどいたずら送信が多いが多い場合に設定すると便利です。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.tag_use', 'タグ機能') ?></th>
			<td class="col-input">
<?php echo $this->BcForm->input('TableContent.tag_use', array('type' => 'checkbox', 'label' => '利用する')) ?>
<?php echo $this->BcForm->error('TableContent.tag_use') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.widget_area', 'ウィジェットエリア') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php
				echo $this->BcForm->input('TableContent.widget_area', array(
					'type' => 'select',
					'options' => $this->BcForm->getControlsource('WidgetArea.id'),
					'empty' => 'サイト基本設定に従う'))
				?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpWidgetArea', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.widget_area') ?>
				<div id="helptextWidgetArea" class="helptext">
					テーブルコンテンツで利用するウィジェットエリアを指定します。<br />
					ウィジェットエリアは「<?php $this->BcBaser->link('ウィジェットエリア管理', array('plugin' => null, 'controller' => 'widget_areas', 'action' => 'index')) ?>」より追加できます。
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.layout', 'レイアウトテンプレート名') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php
				echo $this->BcForm->input('TableContent.layout', array(
					'type' => 'select',
					'options' => $this->Table->getLayoutTemplates()))
				?>
<?php echo $this->BcForm->input('TableContent.edit_layout_template', array('type' => 'hidden')) ?>
<?php if ($this->action == 'admin_edit'): ?>
	<?php $this->BcBaser->link('≫ 編集する', 'javascript:void(0)', array('id' => 'EditLayoutTemplate')) ?>
<?php endif ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpLayout', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.layout') ?>
				<div id="helptextLayout" class="helptext">
					<ul>
						<li>テーブルの外枠のテンプレートを指定します。</li>
						<li>「編集する」からテンプレートの内容を編集する事ができます。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.template', 'コンテンツテンプレート名') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php
				echo $this->BcForm->input('TableContent.template', array(
					'type' => 'select',
					'options' => $this->Table->getTableTemplates()))
				?>
<?php echo $this->BcForm->input('TableContent.edit_table_template', array('type' => 'hidden')) ?>
<?php if ($this->action == 'admin_edit'): ?>
	<?php $this->BcBaser->link('≫ 編集する', 'javascript:void(0)', array('id' => 'EditTableTemplate')) ?>
<?php endif ?>
<?php echo $this->Html->image('admin/icn_help.png', array('id' => 'helpTemplate', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
<?php echo $this->BcForm->error('TableContent.template') ?>
				<div id="helptextTemplate" class="helptext">
					<ul>
						<li>テーブルの本体のテンプレートを指定します。</li>
						<li>「編集する」からテンプレートの内容を編集する事ができます。</li>
					</ul>
				</div>
			</td>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.eye_catch_size_width', 'アイキャッチ画像サイズ') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<span>PCサイズ</span>　
				<small>[幅]</small><?php echo $this->BcForm->input('TableContent.eye_catch_size_thumb_width', array('type' => 'text', 'size' => '8')) ?>&nbsp;px　×　
				<small>[高さ]</small><?php echo $this->BcForm->input('TableContent.eye_catch_size_thumb_height', array('type' => 'text', 'size' => '8')) ?><br />
				<span>携帯サイズ</span>　
				<small>[幅]</small><?php echo $this->BcForm->input('TableContent.eye_catch_size_mobile_thumb_width', array('type' => 'text', 'size' => '8')) ?>&nbsp;px　×　
				<small>[高さ]</small><?php echo $this->BcForm->input('TableContent.eye_catch_size_mobile_thumb_height', array('type' => 'text', 'size' => '8')) ?>
<?php echo $this->BcForm->error('TableContent.eye_catch_size') ?>
				<div id="helptextTemplate" class="helptext">
					<ul>
						<li>アイキャッチ画像のサイズを指定します。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableContent.use_content', '記事概要') ?></th>
			<td class="col-input">
	<?php echo $this->BcForm->input('TableContent.use_content', array('type' => 'checkbox', 'label' => '利用する')) ?>
	<?php echo $this->BcForm->error('TableContent.tag_use') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm('option') ?>
	</table>
</div>
<!-- button -->
<div class="submit">
<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button')) ?>
<?php if ($this->action == 'admin_edit'): ?>
	<?php
	$this->BcBaser->link('削除', array('action' => 'delete', $this->BcForm->value('TableContent.id')), array('class' => 'button'), sprintf('%s を本当に削除してもいいですか？', $this->BcForm->value('TableContent.title')), false);
	?>
<?php endif ?>
</div>

<?php echo $this->BcForm->end() ?>