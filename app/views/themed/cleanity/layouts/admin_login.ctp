<html>
	<head>
		<?php echo $html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?> ::
			<?php echo $appConfigurations['name']; ?>
		</title>
		<?php
			echo $html->css('admin/reset');
			echo $html->css('admin/message');
			echo $html->css('admin/login');
		?>
	
	    <!--[if IE 6]>
	    <style type="text/css">
	    label.remember {width: 200px; margin-left:40px;}
	    label.txt-field {margin-right: 5px}
	    </style>
	    <![endif]-->

		<?php
			echo $scripts_for_layout;	
		?>
	</head>
	<body>
		<div id="distance"></div>
		<div id="container">
			<div id="top">
				<?php echo $this->element('header/logo'); ?>
			</div>
			<div id="form-container">
				<?php
	                if($session->check('Message.auth')){
	                        echo $session->flash('auth');
	                }
				?>
				<?php echo $form->create('User'); ?>
				<fieldset>
					<legend><?php echo __('Login', true); ?></legend>
						<ol>
							<li><label class="field-title"><?php echo __('Username', true); ?>:</label><label class="txt-field"><?php echo $form->input('username', array('label' => false, 'div' => false)) ?></label></li>
							<li><label class="field-title"><?php echo __('Password', true); ?>:</label><label class="txt-field"><?php echo $form->input('password', array('type' => 'password', 'label' => false, 'div' => false)) ?></label></li>
							<li><label class="remember"><?php echo $form->input('remember_me', array('type' => 'checkbox', 'label' => false, 'div' => false)); ?><?php echo __('Remember Me', true); ?></label>
								<div class="align-right">
									<?php echo $form->submit('/theme/cleanity/img/admin/bt-login.gif'); ?>
		
								</div>
							</li>
						</ol>
					</fieldset>
					<span class="clearFix">&nbsp;</span>
				<?php echo $form->end(); ?>
			</div>
		</div>
		<div style="background: #fff;margin-top: 125px; ">
				<?php echo $this->element('sql_dump'); ?>
		</div>
	</body>
</html>