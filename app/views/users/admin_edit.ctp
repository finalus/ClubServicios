<div class="box users form">
	<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
	    <h4 class="white"><?php printf(__('Admin Edit %s', true), __('User', true)); ?></h4>
    </div>
    <div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners form">
<?php 
	echo $form->create('User', array('class' => 'middle-forms', 'inputDefaults' => array('div' => array('tag' => 'li')))); 
?>
	<fieldset>
        <legend><?php printf(__('User %s', true), __('Information', true)); ?></legend>
		<ol>
	<?php
		echo $this->Form->input('id');
		echo $form->input('first_name', array('label' => array('text' => __('First Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('last_name', array('label' => array('text' => __('Last Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('username', array('label' => array('text' => __('User Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('email', array('label' => array('text' => __('Email', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('password_before', array('type' => 'password', 'label' => array('text' => __('Password', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('password_confirmation', array('type' => 'password', 'label' => array('text' => __('Password Confirmation', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		echo $form->input('reset_key', array('type' => 'checkbox', 'label' => array('text' => __('Reset API Key', true), 'class' => 'field-title'), 'after' => sprintf('<strong>%s</strong>', $this->data['User']['key'])));
		echo $form->input('active', array('type' => 'checkbox', 'label' => array('text' => __('Active', true), 'class' => 'field-title')));
		echo $this->Form->input('group_id', array('label' => array('text' => __('Group', true), 'class' => 'field-title')));
	?>
	</ol>
	<?php echo $this->Form->end(array('name' => __('Submit', true), 'class' => 'button'));?>
	</fieldset>

	</div>
</div>
