<?php
/**
 * [PUBLISH] テーブルコメント一覧
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$prefix = '';
if (Configure::read('BcRequest.agent')) {
	$prefix = '/' . Configure::read('BcRequest.agentAlias');
}
?>
<script type="text/javascript">
	$(function() {
		loadAuthCaptcha();
		$("#TableCommentAddButton").click(function() {
			sendComment();
			return false;
		});
	});
/**
 * コメントを送信する
 */
	function sendComment() {
		var msg = '';
		if (!$("#TableCommentName").val()) {
			msg += 'お名前を入力してください\n';
		}
		if (!$("#TableCommentMessage").val()) {
			msg += 'コメントを入力してください\n';
		}
<?php if ($tableContent['TableContent']['auth_captcha']): ?>
			if (!$("#TableCommentAuthCaptcha").val()) {
				msg += '画象の文字を入力してください\n';
			}
<?php endif ?>
		if (!msg) {
			$.ajax({
				url: $("#TableCommentAddForm").attr('action'),
				type: 'POST',
				data: $("#TableCommentAddForm").serialize(),
				dataType: 'html',
				beforeSend: function() {
					$("#TableCommentAddButton").attr('disabled', 'disabled');
					$("#ResultMessage").slideUp();
				},
				success: function(result) {
					if (result) {
<?php if ($tableContent['TableContent']['auth_captcha']): ?>
							loadAuthCaptcha();
<?php endif ?>
						$("#TableCommentName").val('');
						$("#TableCommentEmail").val('');
						$("#TableCommentUrl").val('');
						$("#TableCommentMessage").val('');
						$("#TableCommentAuthCaptcha").val('');
						var resultMessage = '';
<?php if ($tableContent['TableContent']['comment_approve']): ?>
							resultMessage = '送信が完了しました。送信された内容は確認後公開させて頂きます。';
	<?php else: ?>
							var comment = $(result);
							comment.hide();
							$("#TableCommentList").append(comment);
							comment.show(500);
							resultMessage = 'コメントの送信が完了しました。';
<?php endif ?>
						$.ajax({
							url: $("#TableCommentGetTokenUrl").html(),
							type: 'GET',
							dataType: 'text',
							success: function(result) {
								$('input[name="data[_Token][key]"]').val(result);
							}
						});
						$("#ResultMessage").html(resultMessage);
						$("#ResultMessage").slideDown();
					} else {
<?php if ($tableContent['TableContent']['auth_captcha']): ?>
							loadAuthCaptcha();
<?php endif ?>
						$("#ResultMessage").html('コメントの送信に失敗しました。入力内容を見なおしてください。');
						$("#ResultMessage").slideDown();
					}
				},
				error: function(result) {
					alert('コメントの送信に失敗しました。入力内容を見なおしてください。');
				},
				complete: function(xhr, textStatus) {
					$("#TableCommentAddButton").removeAttr('disabled');
				}
			});
		} else {
			alert(msg);
		}
	}
/**
 * キャプチャ画像を読み込む
 */
	function loadAuthCaptcha() {

		var src = $("#TableCommentCaptchaUrl").html() + '?' + Math.floor(Math.random() * 100);
		$("#AuthCaptchaImage").hide();
		$("#CaptchaLoader").show();
		$("#AuthCaptchaImage").load(function() {
			$("#CaptchaLoader").hide();
			$("#AuthCaptchaImage").fadeIn(1000);
		});
		$("#AuthCaptchaImage").attr('src', src);

	}
</script>

<div id="TableCommentCaptchaUrl" style="display:none"><?php echo $this->BcBaser->getUrl($prefix . '/table/table_comments/captcha') ?></div>
<div id="TableCommentGetTokenUrl" style="display:none"><?php echo $this->BcBaser->getUrl('/table/table_comments/get_token') ?></div>

<?php if ($tableContent['TableContent']['comment_use']): ?>
	<div id="TableComment">

		<h4 class="contents-head">この記事へのコメント</h4>

		<div id="TableCommentList">
			<?php if (!empty($post['TableComment'])): ?>
				<?php foreach ($post['TableComment'] as $comment): ?>
					<?php $this->BcBaser->element('table_comment', array('dbData' => $comment)) ?>
				<?php endforeach ?>
			<?php endif ?>
		</div>

		<h4 class="contents-head">コメントを送る</h4>

		<?php echo $this->BcForm->create('TableComment', array('url' => $prefix . '/table/table_comments/add/' . $tableContent['TableContent']['id'] . '/' . $post['TablePost']['id'], 'id' => 'TableCommentAddForm')) ?>

		<table cellpadding="0" cellspacing="0" class="row-table-01">
			<tr>
				<th><?php echo $this->BcForm->label('TableComment.name', 'お名前') ?></th>
				<td><?php echo $this->BcForm->input('TableComment.name', array('type' => 'text')) ?></td>
			</tr>
			<tr>
				<th><?php echo $this->BcForm->label('TableComment.email', 'Eメール') ?></th>
				<td>
					<?php echo $this->BcForm->input('TableComment.email', array('type' => 'text', 'size' => 30)) ?>&nbsp;
					<small>※ メールは公開されません</small>
				</td>
			</tr>
			<tr>
				<th><?php echo $this->BcForm->label('TableComment.url', 'URL') ?></th>
				<td><?php echo $this->BcForm->input('TableComment.url', array('type' => 'text', 'size' => 30)) ?></td>
			</tr>
			<tr>
				<th><?php echo $this->BcForm->label('TableComment.message', 'コメント') ?></th>
				<td><?php echo $this->BcForm->input('TableComment.message', array('type' => 'textarea', 'rows' => 10, 'cols' => 52)) ?></td>
			</tr>
		</table>

		<?php if ($tableContent['TableContent']['auth_captcha']): ?>
			<div class="auth-captcha clearfix">
				<img src="" alt="認証画象" class="auth-captcha-image" id="AuthCaptchaImage" style="display:none" />
				<?php $this->BcBaser->img('admin/captcha_loader.gif', array('alt' => 'Loading...', 'class' => 'auth-captcha-image', 'id' => 'CaptchaLoader')) ?>
				<?php echo $this->BcForm->text('TableComment.auth_captcha') ?><br />
				&nbsp;画像の文字を入力してください<br />
			</div>
		<?php endif ?>

		<?php echo $this->BcForm->end(array('label' => '送信する', 'id' => 'TableCommentAddButton', 'class' => 'button')) ?>

		<div id="ResultMessage" class="message" style="display:none;text-align:center">&nbsp;</div>

	</div>
<?php endif ?>