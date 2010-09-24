<script type="text/javascript">
    $(document).ready(function() {
        $('#ImportUploadImportDelimiterId').change(updatePreview);
        $('#ImportUploadTextQualifier').blur(updatePreview);
        $('#ImportUploadUseHeaderRow').change(updatePreview);
        updatePreview();

        $('#ImportMappingMappingModel').change(updatePreview);
        $('#ImportUploadMappingId').change(updatePreview);

        $('#ImportNewMapping').click(toggleNewMapping);
 });
    
    function toggleNewMapping() {
        if ($('#ImportNewMapping').is(':checked')) {
            $('#ImportMappingNew').show();
            $('#ImportUploadMappingId').val('').attr('disabled', 'disabled');
        } else {
            $('#ImportMappingMappingModel').val('');
            $('#ImportMappingNew').hide();
            $('#ImportUploadMappingId').removeAttr('disabled');
            updatePreview();
        }
    }

    function updatePreview() {
        $.post('<?php echo $html->url(array('controller' => 'import', 'action' => 'preview')); ?>', 
            $('#ImportUploadMapForm').serialize(), function(data) {
            $('.importPreview').html(data);
        })
        $('.importPreview').html('<?php echo __('Loading Preview... Please wait', true); ?>');
    }
</script>
<style>
    optgroup option {
        margin-left: 25px;
    }
</style>
<div class="importFiles form">
    <?php echo $form->create('ImportUpload', array('id' => 'ImportUploadMapForm', 'url' => '/admin/import/map')); ?>
    <fieldset>
        <legend><?php echo __('Map an import upload', true); ?></legend>
        <table width="100%">
            <tr>
                <td><strong><?php echo sprintf(__('Preview of "%s"',true), $importUpload['ImportUpload']['filename']); ?></strong></td>
            </tr>
            <tr>
                <td><?php echo $html->div('importPreview', '', array('style' =>'border: 1px solid #666;background:#fff;min-height:200px;height:200px;overflow:auto;')); ?></td>
            </tr>
        </table>
        <?php echo $form->input('id'); ?>
        <?php echo $form->hidden('show_mapping', 1); ?>
        <?php echo $form->hidden('import_delimiter_id'); ?>
        <?php echo $form->hidden('text_qualifier'); ?>
        <?php echo $form->hidden('use_header_row'); ?>
        <?php echo $form->input('mapping_id', array('empty' => __('--Select Mapping--', true))); ?>
        <?php echo $form->input('new_mapping', array('type' => 'checkbox', 'id' => 'ImportNewMapping')); ?>
        <div id="ImportMappingNew" style="display:none;">
            <?php echo $form->input('ImportMapping.mapping_model', array('empty' => __('--Select Model--', true), 'selected' => '', 'options' => $appModels, 'label' => __('Mapping model', true))); ?>
            <?php echo $form->input('ImportMapping.name', array('label' => __('Mapping name', true), 'value' => '')); ?>
            <?php echo $form->input('ImportMapping.description', array('label' => __('Mapping description', true), 'value' => '')); ?>
        </div>
    </fieldset>
    <?php echo $form->end(__('Map Import', true)); ?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $html->link(__('<< Back to Import Uploads', true), '/admin/import') ?></li>
    </ul>
</div>
