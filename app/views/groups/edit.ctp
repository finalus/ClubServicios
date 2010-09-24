<div class="groups form content-box">
    <div class="content-box-header">
        <h3><?php printf(__('Edit %s', true), __('Group', true)); ?></h3>
    </div>
    <div class="content-box-content">

<?php echo $this->Form->create('Group');?>
	<fieldset>
        <legend><?php printf(__('Group %s', true), __('Information', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(array('name' => __('Submit', true), 'class' => 'button'));?>
</div>
