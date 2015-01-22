<?php if($result): ?>
	<?php echo $this->BcForm->input(
		'TableTag.TableTag',
		array('type' => 'select', 'multiple' => 'checkbox', 'options' => $result, 'hidden' => false, 'value' => true));
	?>
<?php endif ?>