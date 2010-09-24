<div class="box groups form">
    <div class="box-top">
        <h4 style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;" class="white rounded_by_jQuery_corners"><?php printf(__('Admin Add %s', true), __('Group', true)); ?></h4>
    </div>
    <div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners form">
<?php 
	echo $form->create('Group', array('class' => 'middle-forms', 'inputDefaults' => array('div' => array('tag' => 'li')))); 
?>
	<fieldset>
        <legend><?php printf(__('Group %s', true), __('Information', true)); ?></legend>
		<ol>
	<?php
		echo $form->input('name', array('label' => array('text' => __('Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
	?>
	</ol>
	<?php echo $this->Form->end(array('name' => __('Submit', true), 'class' => 'button'));?>
	</fieldset>

	</div>
</div>
