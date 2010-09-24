<h2><?php echo __('Please Enter a new Password', true); ?></h2>
<fieldset>
    <legend><?php echo sprintf(__('Your username is "%s"', true), $user['User']['username'])?></legend>
    <?php
        echo $form->create('User', array('action' => 'reset'));
        echo $form->input('before_password', array('type' => 'password', 'label' => __('Enter a New Password', true)));
        echo $form->input('retype_password', array('type' => 'password', 'label' => __('Verify New Password', true)));
        echo $form->end(__('Reset Password', true));
    ?> 
</fieldset>

