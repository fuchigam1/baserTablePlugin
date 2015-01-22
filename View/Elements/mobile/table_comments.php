<?php
/**
 * [MOBILE] テーブルコメント一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
?>
<?php if ($tableContent['TableContent']['comment_use']): ?>

	<div id="TableComment">
		<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
		<div style="text-align:center;background-color:#8ABE08;"> <span style="color:white;">この記事へのコメント</span> </div>
		<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
		<?php if (!empty($commentMessage)): ?>
			<span style="color:red;"><?php echo $commentMessage ?></span><br />
			<br />
		<?php endif ?>
		<?php if (!empty($post['TableComment'])): ?>
			<div id="TableCommentList">
				<?php foreach ($post['TableComment'] as $comment): ?>
					<?php $this->BcBaser->element('table_comment', array('dbData' => $comment)) ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
		<br />
		<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
		<div style="text-align:center;background-color:#8ABE08;"> <span style="color:white;">コメントを送る</span> </div>
		<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
		<?php echo $this->BcForm->create('TableComment', array('url' => '/' . Configure::read('BcAgent.mobile.alias') . '/' . $tableContent['TableContent']['name'] . '/archives/' . $post['TablePost']['no'] . '#TableComment')) ?>
		<?php echo $this->BcForm->label('TableComment.name', 'お名前') ?><br />
		<?php echo $this->BcForm->text('TableComment.name') ?><br />
		<span style="color:red;"><?php echo $this->BcForm->error('TableComment.name') ?></span>
		<?php echo $this->BcForm->label('TableComment.email', 'Eメール') ?>&nbsp;<small>※ 非公開</small><br />
		<?php echo $this->BcForm->text('TableComment.email', array('size' => 30)) ?><br />
		<span style="color:red;"><?php echo $this->BcForm->error('TableComment.email') ?></span>
		<?php echo $this->BcForm->label('TableComment.url', 'URL') ?><br />
		<?php echo $this->BcForm->text('TableComment.url', array('size' => 30)) ?><br />
		<span style="color:red;"><?php echo $this->BcForm->error('TableComment.url') ?></span>
		<?php echo $this->BcForm->label('TableComment.message', 'コメント') ?><br />
		<?php echo $this->BcForm->textarea('TableComment.message', array('rows' => 6, 'cols' => 26)) ?>
		<span style="color:red;"><?php echo $this->BcForm->error('TableComment.message') ?></span>
		<?php echo $this->BcForm->end(array('label' => '　　送信する　　', 'id' => 'TableCommentAddButton')) ?>
		<div id="ResultMessage" class="message" style="display:none;text-align:center">&nbsp;</div>
	</div>
<?php endif ?>
