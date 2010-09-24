<script type="text/javascript">
    $(document).ready(function() {
        $('#ImportUploadImportDelimiterId').change(updatePreview);
        $('#ImportUploadTextQualifier').blur(updatePreview);
        $('#ImportUploadUseHeaderRow').change(updatePreview);
        updatePreview();
    });

    function updatePreview() {
        $.post('<?php echo $html->url(array('controller' => 'import', 'action' => 'preview')); ?>', 
            $('#ImportUploadParseForm').serialize(), function(data) {
            $('.importPreview').html(data);
        })
        $('.importPreview').html('<?php echo __('Loading Preview... Please wait', true); ?>');
    }
</script>
<div class="importFiles form">
    <?php echo $form->create('ImportUpload',  array('id' => 'ImportUploadParseForm', 'url' => '/admin/import/parse')); ?>
    <fieldset>
        <legend><?php echo __('Update an import upload', true); ?></legend>
        <table width="100%">
            <tr>
                <td><strong><?php echo sprintf(__('Preview of "%s"',true), $importUpload['ImportUpload']['filename']); ?></strong></td>
            </tr>
            <tr>
                <td><?php echo $html->div('importPreview', '', array('style' =>'border: 1px solid #666;background:#fff;min-height:200px;height:200px;overflow:auto;')); ?></td>
            </tr>
        </table>
        <?php echo $form->input('id'); ?>
        <?php echo $form->hidden('show_mapping', 0); ?>
        <?php echo $form->input('import_delimiter_id', array('label' => __('Delimiter', true))); ?>
        <?php echo $form->input('text_qualifier', array('label' => __('Text Qualifier', true))); ?>
        <?php echo $form->input('use_header_row', array('label' => __('This file has', true), 'type' => 'select', 'options' => array(1 => __('Header Row', true), 0 => __('No Header', true)))); ?>
        <?php echo $form->input('source_id', array('label' => __('Source', true), 'empty' => __('--Select a Source--', true))); ?>
    </fieldset>
    <?php echo $form->end(__('Parse Import', true)); ?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('<< Back to Import Uploads', true), array('action' => 'index')) ?></li>
    </ul>
</div>
