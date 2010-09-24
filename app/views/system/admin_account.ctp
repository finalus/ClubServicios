<?php
	$html->addCrumb(__('System', true), '/admin/system');
	$html->addCrumb(__('My Account', true));

?>
<div class="box">
	<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
    	<h4 class="white"><?php echo __('My Account', true); ?></h4>
	</div>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners form">
		<?php 
			echo $form->create('User', array('url' => array('controller' => 'system', 'action' => 'account'), 'class' => 'middle-forms', 'inputDefaults' => array('div' => array('tag' => 'li')))); 
		?>
		<fieldset>
		<ol>
		<?php 
			echo $form->input('User.username', array('label' => array('text' => __('User Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
			echo $form->input('User.first_name', array('label' => array('text' => __('First Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
			echo $form->input('User.last_name', array('label' => array('text' => __('Last Name', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
			echo $form->input('User.email', array('label' => array('text' => __('Email', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
			echo $form->input('User.password_before', array('type' => 'password', 'label' => array('text' => __('Password', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
			echo $form->input('User.password_confirmation', array('type' => 'password', 'label' => array('text' => __('Password Confirmation', true), 'class' => 'field-title'), 'class' => 'txtbox-long'));
		?>
		</ol>
		<?php
			echo $form->end('Submit');
		?>
		</fieldset>
	</div>
</div>

