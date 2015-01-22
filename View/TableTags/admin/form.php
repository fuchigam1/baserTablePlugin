<?php
/**
 * [ADMIN] テーブルタグ フォーム
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
	$("#TableTagName").focus();
});
</script>


<!-- form -->
<?php echo $this->BcForm->create('TableTag') ?>
<div class="section">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table">
		<?php if ($this->action == 'admin_edit'): ?>
			<tr>
				<th class="col-head"><?php echo $this->BcForm->label('TableTag.id', 'NO') ?></th>
				<td class="col-input">
					<?php echo $this->BcForm->value('TableTag.id') ?>
					<?php echo $this->BcForm->input('TableTag.id', array('type' => 'hidden')) ?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<th class="col-head"><?php echo $this->BcForm->label('TableTag.name', 'テーブルタグ名') ?>&nbsp;<span class="required">*</span></th>
			<td class="col-input">
				<?php echo $this->BcForm->input('TableTag.name', array('type' => 'text', 'size' => 40, 'maxlength' => 255)) ?>
				<?php echo $this->BcForm->error('TableTag.name') ?>
			</td>
		</tr>

		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>
<!-- button -->
<div class="submit">
	<?php echo $this->BcForm->submit('保存', array('div' => false, 'class' => 'button', 'id' => 'BtnSave')) ?>
	<?php if ($this->action == 'admin_edit'): ?>
		<?php
		$this->BcBaser->link('削除', array('action' => 'delete', $this->BcForm->value('TableTag.id')), array('class' => 'button'), sprintf('%s を本当に削除してもいいですか？', $this->BcForm->value('TableTag.name')), false);
		?>
	<?php endif ?>
</div>

<?php echo $this->BcForm->end() ?>