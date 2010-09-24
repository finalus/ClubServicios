<h2><?php echo __('Now, please check your email', true); ?></h2>
<ul>
    <li><?php echo __('You must click on the link in the email we just sent you in order to complete your registration', true); ?></li>
    <li><?php echo sprintf(__('The email was sent to %s', true), $email); ?></li>
</ul>
<hr />
<h2><?php echo __('Never received the email?', true); ?></h2>
<ul>
    <li><?php echo __('Has it been less than 10 minutes? Please wait -- it sometimes just takes a bit', true); ?></li>
    <li><?php echo __('Check your spam folder just in case', true); ?></li>
    <li><?php echo sprintf(__('Try %s your email', true), $html->link(__('resending', true), array('action' => 'resend', $email))); ?></li>
</ul>
