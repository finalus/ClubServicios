<?php

$preview = $import->parseFile($importUpload['ImportUpload']['file_path'], 
            array('delimiter' => $importUpload['ImportUpload']['text_delimiter'],
                  'qualifier' => $importUpload['ImportUpload']['text_qualifier'],
                  'excel_reader' => $importUpload['ImportUpload']['use_excel_reader']));

?>
<table width="500px" border="1" cellpadding="0" cellspacing="0" style="margin:0px;padding:0px;">
<?php if ($importUpload['ImportUpload']['file_path']): ?>
    <?php if (($importUpload['ImportUpload']['status_id'] == 2) && $importUpload['ImportUpload']['show_mapping']): ?>
        <tr>
        <?php 
        for ($i=0;$i<$import->count;$i++):
            $selected = ""; 
            if (!empty($importUpload['ImportMappingField'])) {
                foreach ($importUpload['ImportMappingField'] as $key=>$value) {
                    if ($value['column_id'] == $i) {
                        $selected = $importUpload['ImportMappingField'][$key]['column_key'];
                    }
                }
            } 
        ?>
            <td>
                <?php echo $form->hidden("ImportMappingField.$i.column_id", array('value' => $i)); ?>
                <?php echo $form->input("ImportMappingField.$i.column_key", array('label' => '', 'empty' => __('--Skip Column--', true), 'options' => $appModelColumns, 'selected' => $selected)); ?>
            </td>
        <?php endfor; ?>
        </tr>
    <?php endif; ?>
    <?php if (is_array($preview) && count($preview)>0): ?>
    <?php foreach ($preview as $key => $preview_line): ?>
        <?php if (is_array($preview_line) || $preview_line != ""): ?>                                             
        <tr>
        <?php if ($importUpload['ImportUpload']['use_header_row'] && $key == 0): ?>
            <?php if (is_array($preview_line)): ?>
                <?php foreach ($preview_line as $line_item): ?>
                    <th><nobr><?php echo $line_item; ?></nobr></th>
                <?php endforeach; ?>
            <?php else: ?>
                    <th><?php echo $preview_line; ?></th>
            <?php endif; ?>
        <?php else: ?>
            <?php if (is_array($preview_line)): ?>
                <?php foreach ($preview_line as $line_item): ?>
                    <td><nobr><?php echo $line_item; ?></nobr></td>
                <?php endforeach; ?>
            <?php else: ?>
                <td><nobr><?php echo $preview_line; ?></nobr></td>
            <?php endif; ?>
        <?php endif; ?>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php else: ?>
        <?php echo sprintf(__('The import "%s" was empty.', true), $importUpload['ImportUpload']['filename']); ?>
    <?php endif; ?>
<?php endif; ?>
</table>
