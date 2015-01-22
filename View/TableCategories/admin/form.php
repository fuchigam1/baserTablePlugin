<?php
/**
 * [ADMIN] テーブルカテゴリ フォーム
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$owners = $this->BcForm->getControlSource('TableCategory.owner_id');
?>


<script type="text/javascript">
	$(window).load(function() {
		$("#TableCategoryName").focus();
	});
</script>


<?php if ($this->action == 'admin_edit'): ?>
	<div class="em-box align-left">
		<p><strong>このカテゴリのURL：<?php $this->BcBaser->link($this->BcBaser->getUri('/' . $tableContent['TableContent']['name'] . '/archives/category/' . $this->BcForm->value('TableCategory.name')), '/' . $tableContent['TableContent']['name'] . '/archives/category/' . $this->BcForm->value('TableCategory.name'), array('target' => '_blank')) ?></strong></p>
	</div>
<?php endif ?>


<?php /* TableContent.idを第一引数にしたいが為にURL直書き */ ?>
<?php if ($this->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('TableCategory', array('url' => array('controller' => 'table_categories', 'action' => 'add', $tableContent['TableContent']['id']))) ?>
	<?php elseif ($this->action == 'admin_edit'): ?>
	<?php echo $this->BcForm->create('TableCategory', array('url' => array('controller' => 'table_categories', 'action' => 'edit', $tableContent['TableContent']['id'], $this->BcForm->value('TableCategory.id'), 'id' => false))) ?>
<?php endif; ?>

<?php echo $this->BcForm->input('TableCategory.id', array('type' => 'hidden')) ?>

<!-- form -->
<div class="section">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table">
		<?php if ($this->action == 'admin_edit'): ?>
			<tr>
				<th class="col-head"><?php echo $this->BcForm->label('TableCategory.no', 'NO') ?></th>
				<td class="col-input">
					<?php echo $this->BcForm->value('TableCategory.no') ?>
					<?php echo $this->BcForm->input('TableCategory.no', array('type' => 'hidden')) ?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableCategory.name', 'テーブルカテゴリ名') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableCategory.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255)) ?>
				<?php echo $this->BcHtml->image('admin/icn_help.png', array('id' => 'helpName', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
				<?php echo $this->BcForm->error('TableCategory.name') ?>
				<div id="helptextName" class="helptext">
					<ul>
						<li>URLに利用されます</li>
						<li>半角のみで入力してください</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableCategory.title', 'テーブルカテゴリタイトル') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableCategory.title', array('type' => 'text', 'size' => 40, 'maxlength' => 255)) ?>
				<?php echo $this->BcForm->error('TableCategory.title') ?>
			</td>
		</tr>
		<?php if ($parents): ?>
			<tr>
				<th class="col-head"><?php echo $this->BcForm->label('TableCategory.parent_id', '親カテゴリ') ?></th>
				<td class="col-input">
					<?php
					echo $this->BcForm->input('TableCategory.parent_id', array(
						'type' => 'select',
						'options' => $parents,
						'escape' => false))
					?>
			<?php echo $this->BcForm->error('TableCategory.parent_id') ?>
				</td>
			</tr>
		<?php else: ?>
			<?php echo $this->BcForm->input('TableCategory.parent_id', array('type' => 'hidden')) ?>
		<?php endif ?>
		<?php if ($this->BcBaser->siteConfig['category_permission']): ?>
			<tr>
				<th class="col-head"><?php echo $this->BcForm->label('TableCategory.owner_id', '管理グループ') ?></th>
				<td class="col-input">
					<?php if ($this->BcAdmin->isSystemAdmin()): ?>
						<?php
						echo $this->BcForm->input('TableCategory.owner_id', array(
							'type' => 'select',
							'options' => $owners,
							'empty' => '指定しない'))
						?>
						<?php echo $this->BcHtml->image('admin/icn_help.png', array('id' => 'helpOwnerId', 'class' => 'btn help', 'alt' => 'ヘルプ')) ?>
						<?php echo $this->BcForm->error('TableCategory.owner_id') ?>
					<?php else: ?>
						<?php echo $this->BcText->arrayValue($this->request->data['TableCategory']['owner_id'], $owners) ?>
						<?php echo $this->BcForm->input('TableCategory.owner_id', array('type' => 'hidden')) ?>
					<?php endif ?>
					<div id="helptextOwnerId" class="helptext">
						<ul>
							<li>管理グループを指定した場合、このカテゴリに属した記事は、管理グループのユーザーしか編集する事ができなくなります。</li>
						</ul>
					</div>
				</td>
			</tr>
		<?php endif ?>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>


<!-- button -->
<div class="submit">
	<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button')) ?>
	<?php if ($this->action == 'admin_edit'): ?>
		<?php
		$this->BcBaser->link('削除', array('action' => 'delete', $tableContent['TableContent']['id'], $this->BcForm->value('TableCategory.id')), array('class' => 'button'), sprintf('%s を本当に削除してもいいですか？', $this->BcForm->value('TableCategory.name')), false);
		?>
	<?php endif ?>
</div>

<?php echo $this->BcForm->end() ?>