<fieldset>
    <legend><?php echo __('Forgot your username or password?', true); ?></legend>
    <?php
        echo $form->create('User', array('action' => 'recover'));
        echo $form->input('email', array('label' => __('Enter your email address', true)));
        echo $form->end(__('Retrieve Password', true));
    ?> 
</fieldset>

