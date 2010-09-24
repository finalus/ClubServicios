<?php
	$html->addCrumb(__('System', true), '/admin/system');
	$html->addCrumb(__('Permissions', true), '/admin/system/acl_permissions');
	$html->addCrumb(__('Acos', true));
?>
<div class="contentBox acl_acos index">
		<div class="contentBoxTop">
        <h3><?php echo __('Acos', true); ?></h3>
        <ul class="switcherTabs">
            <li><?php echo $html->link(__('New Aco', true), array('action' => 'add'), array('class' => 'button')); ?></li>
        </ul>
    </div>
    <div class="innerContent">
    <table cellpadding="0" cellspacing="0" class="sTable">
    <?php
        $tableHeaders = $html->tableHeaders(array(
            $paginator->sort('id'),
            $paginator->sort('parent_id'),
            $paginator->sort('model'),
            $paginator->sort('foreign_key'),
            $paginator->sort('alias'),
            __('Actions', true),
        ));
        echo $tableHeaders;

        $rows = array();
        foreach ($acos AS $aco) {
            $actions  = $html->link(__('Edit', true), array('action' => 'edit', $aco['AclAco']['id']));
            $actions .= ' ' . $html->link(__('Delete', true), array(
                'action' => 'delete',
                $aco['AclAco']['id'],
          #      'token' => $this->params['_Token']['key'],
            ), null, __('Are you sure?', true));

            $rows[] = array(
                $aco['AclAco']['id'],
                $aco['AclAco']['parent_id'],
                $aco['AclAco']['model'],
                $aco['AclAco']['foreign_key'],
                $aco['AclAco']['alias'],
                $actions,
            );
        }

        echo $html->tableCells($rows);
    ?>
    </table>
    </div>
</div>

<div class="paging"><?php echo $paginator->numbers(); ?></div>
<div class="counter"><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true))); ?></div>
