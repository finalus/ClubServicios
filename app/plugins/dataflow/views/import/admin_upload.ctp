

<div class="importFiles form">
    <?php echo $form->create('ImportUpload', array('type' => 'file','url' => '/admin/import/upload')); ?>
    <fieldset>
        <legend><?php echo __('Add an import upload', true); ?></legend>
        <?php echo $form->input('filename', array('type' => 'file')); ?>
    </fieldset>
    <?php echo $form->end(__('Upload Import', true)); ?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('<< Back to Import Uploads', true), array('action' => 'index')) ?></li>
    </ul>
</div>
