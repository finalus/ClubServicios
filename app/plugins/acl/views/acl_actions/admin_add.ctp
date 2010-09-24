<?php
$html->addCrumb(__('System', true), '/admin/system');
$html->addCrumb(__('Permissions', true), '/admin/system/permissions');
$html->addCrumb(sprintf(__('Add %s', true), __('Action', true)));

?>
<div class="acl_actions form contentBox">
    <div class="contentBoxTop">
        <h3><?php echo sprintf(__('Add %s', true), __('Action', true)); ?></h3>
    </div>
    <div style="display: block;" class="innerContent" id="box-1">
    <?php echo $form->create('Aco', array('url' => array('controller' => 'acl_actions', 'action' => 'add'))); ?>
        <?php
            echo $form->input('parent_id', array('label' => __('Parent', true), 'div' => 'smallInput',
                'options' => $acos,
                'empty' => true,
                'rel' => __('Choose none if the Aco is a controller.', true),
            ));
            echo $form->input('alias', array('label' => __('Alias', true), 'div' => 'smallInput'));
        ?>
    <?php echo $form->end(array('name' => __('Submit', true), 'class' => 'button' ));?>
    </div>
</div>
