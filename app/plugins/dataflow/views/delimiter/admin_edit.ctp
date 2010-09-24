<div class="importMasterDelimiters form">
<?php echo $form->create('ImportMasterDelimiter');?>
	<fieldset>
 		<legend><?php __('Edit Delimiter');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('description');
		echo $form->input('delimiter');
		echo $form->input('use_excel_reader');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('ImportMasterDelimiter.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $form->value('ImportMasterDelimiter.name'))); ?></li>
		<li><?php echo $html->link(__('List Delimiters', true), array('action'=>'index'));?></li>
	</ul>
</div>
