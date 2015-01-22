<?php
/**
 * [ADMIN] テーブルカテゴリー一覧ウィジェット設定
 *
 * baserTablePlugin
 * Copyright 2015 YusukeHirao
 *
 * @copyright		Copyright 2015 YusukeHirao
 * @link			https://github.com/YusukeHirao/baserTablePlugin
 * @license			MIT
 */
$title = 'テーブルカテゴリー一覧';
$description = 'テーブルのカテゴリー一覧を表示します。';
?>

<script type="text/javascript">
$(function(){
	var key = "<?php echo $key ?>";
	$("#"+key+"ByYear").click(function(){
		if($("#"+key+"ByYear").attr('checked') == 'checked') {
			$("#"+key+"Depth").val(1);
			$("#Span"+key+"Depth").slideUp(200);
		} else {
			$("#Span"+key+"Depth").slideDown(200);
		}
	});
	if($("#"+key+"ByYear").attr('checked') == 'checked') {
		$("#"+key+"Depth").val(1);
		$("#Span"+key+"Depth").hide();
	}
});
</script>

<?php echo $this->BcForm->label($key . '.limit', '表示数') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.limit', array('type' => 'text', 'size' => 6)) ?>&nbsp;件<br />
<?php echo $this->BcForm->label($key . '.view_count', '記事数表示') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.view_count', array('type' => 'radio', 'options' => $this->BcText->booleanDoList(''), 'legend' => false, 'default' => 0)) ?><br />
<?php echo $this->BcForm->input($key . '.by_year', array('type' => 'checkbox', 'label' => '年別に表示する')) ?><br />
<p id="Span<?php echo $key ?>Depth"><?php echo $this->BcForm->label($key . '.depth', '深さ') ?>&nbsp;
	<?php echo $this->BcForm->input($key . '.depth', array('type' => 'text', 'size' => 6, 'default' => 1)) ?>&nbsp;階層</p>
<?php echo $this->BcForm->label($key . '.table_content_id', 'テーブル') ?>&nbsp;
<?php echo $this->BcForm->input($key . '.table_content_id', array('type' => 'select', 'options' => $this->BcForm->getControlSource('Table.TableContent.id'))) ?><br />
<small>テーブルページを表示している場合は、上記の設定に関係なく、<br />対象テーブルのテーブルカテゴリー一覧を表示します。</small>