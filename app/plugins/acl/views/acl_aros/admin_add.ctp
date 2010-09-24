<div class="acl_aros form content-box">
    <div class="content-box-header">
        <h3><?php echo sprintf(__('Add %s', true), __('Aro', true)); ?></h3>
    </div>
    <div class="content-box-content">
    <?php echo $form->create('AclAro', array('url' => array('action' => 'add')));?>
        <fieldset>
        <?php
            echo $form->input('parent_id');
            echo $form->input('model');
            echo $form->input('foreign_key');
            echo $form->input('alias');
        ?>
        </fieldset>
    <?php echo $form->end(array('name' => __('Submit', true), 'class' => 'button' ));?>
    </div>
</div>
