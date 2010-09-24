	<div class="author in">
		<h2><?php printf(__('Add %s', true), __('User', true)); ?></h2>
		<p></p>
	</div>
	
	<div class="line"></div>
	
	<div class="users forms in">

<?php echo $this->Form->create('User');?>
	<fieldset>
        <legend><?php printf(__('User %s', true), __('Information', true)); ?></legend>
	<?php
		echo $this->Form->input('group_id');
		echo $this->Form->input('username');
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('active');
		echo $this->Form->input('key');
		echo $this->Form->input('ip');
	?>
	</fieldset>
<?php echo $this->Form->end(array('name' => __('Submit', true), 'class' => 'com_btn'));?>
</div>
