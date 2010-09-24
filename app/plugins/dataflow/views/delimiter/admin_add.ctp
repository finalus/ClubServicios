<div class="importMasterDelimiters form">
<?php echo $form->create('ImportMasterDelimiter');?>
	<fieldset>
 		<legend><?php __('Add ImportMasterDelimiter');?></legend>
	<?php
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
		<li><?php echo $html->link(__('List Delimiters', true), array('action'=>'index'));?></li>
	</ul>
</div>
