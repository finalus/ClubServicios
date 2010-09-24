<?php
$html->addCrumb(__('Imports', true), '/amin/import');
echo $this->element('admin/crumb');
?>

<div class="imports index">

    <h2><?php __('Import Uploads', true) ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('Add new Import', true), array('action' => 'upload')) ?></li>
        </ul>
    </div>
</div>

<?php if ($paginator->counter() > 0): ?>
    <table>
        <tr>
            <th><?php echo $paginator->sort('id'); ?></th>
            <th><?php echo $paginator->sort(__('Filename', true),'filename'); ?></th>
            <th><?php echo $paginator->sort(__('Delimiter', true), 'ImportDelimiter.id'); ?></th>
            <th><?php echo $paginator->sort(__('Uploaded', true), 'is_uploaded'); ?></th>
            <th><?php echo __('Progress', true); ?></th>
            <th class="actions"><?php echo __('Options', true); ?></th>
        </tr>
        <?php 
        $i = 0;
        foreach ($importUploads as $imports): 
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        ?>
        <tr<?php echo $class; ?>>
            <td>
                <?php echo $imports['ImportUpload']['id']; ?>
            </td>        
            <td>
                <?php echo $imports['ImportUpload']['filename']; ?>
            </td>
            <td>
                <?php echo $imports['ImportDelimiter']['delimiter']; ?>
            </td>
            <td>
                <?php echo ($imports['ImportUpload']['is_uploaded'])?__('Yes', true):__('No', true); ?>
            </td>
            <td>
                <?php echo ($import->getProgress($imports)); ?>
            </td>
            <td class="actions">
                <?php echo $html->link(__('Edit', true), array('action'=>'parse', $imports['ImportUpload']['id'])); ?>
                <?php if ($imports['ImportUpload']['status_id'] == 3): ?>
                <?php echo $html->link(__('Start Import', true), array('action'=>'start', $imports['ImportUpload']['id'])); ?>
                <?php endif; ?>
                <?php echo $html->link(__('Delete', true), array('action' => 'delete', $imports['ImportUpload']['id'])); ?>
            </td>
        </tr>
<?php endforeach; ?>
    </table>
<?php echo $this->element('admin/pagination'); ?>
<?php else: ?>
    <p><?php echo __('There are no imports at the moment', true); ?></p>
<?php endif; ?>
