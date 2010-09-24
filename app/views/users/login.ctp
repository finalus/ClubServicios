<?php
	$javascript->link('http://platform.twitter.com/anywhere.js?id=rEEmmgMVnfiU2gBUdFWN9Q&amp;v=1', false);
?>

<?php
    if ($session->check('Message.auth')) {
        echo $session->flash('auth');
    }
?>
<fieldset>    
	
    <?php echo $html->image('login-fb-connect.png', array('alt' => __('Sign in with Facebook', true), 'url' => array('controller' => 'oauth', 'action' => 'authorize', 'facebook'))); ?><br />
    <?php echo __('or', true); ?><br /> 
	<span id="twitter-login"></span> 
    <?php #echo $html->image('login-twitter.png', array('alt' => __('Sign in with Twitter', true), 'url' => array('controller' => 'oauth', 'action' => 'authorize', 'twitter'))); ?>
</fieldset>
<fieldset>
    <legend><?php echo sprintf(__('Login to %s', true), $appConfigurations['name']); ?></legend>
    <?php 
        echo $form->create('User', array('action' => 'login'));
        echo $form->input('username', array('label' => __('Username', true)));
        echo $form->input('password', array('label' => __('Password', true)));
        echo $form->input('remember_me', array('type' => 'checkbox', 'label' => __('Keep me logged in on this computer', true)));
        echo $form->end(__('Login', true));
    ?> 
</fieldset>

<fieldset>
    <legend><?php echo __('Forgot your username or password?', true); ?></legend>
    <?php
        echo $form->create('User', array('action' => 'recover'));
        echo $form->input('email', array('label' => __('Enter your email address', true)));
        echo $form->end(__('Retrieve Password', true));
    ?> 
</fieldset>
<h2><?php echo __('Don\'t have an account?', true); ?></h2>
<p><?php echo sprintf(__('It\'s easy! Just %s now!', true), $html->link(__('Sign up', true), array('action' => 'register'))); ?> </p>     
<script type="text/javascript">
	twttr.anywhere(function(T) {
		T('#twitter-login').connectButton();
	});
</script> 


