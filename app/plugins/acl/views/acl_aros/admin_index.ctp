<div class="box acl_aros index">
	<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
	    <h4 class="white"><?php echo __('Aros', true); ?></h4>
	</div>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
    <table cellpadding="0" cellspacing="0" class="table-long">
    <?php
        $tableHeaders = $html->tableHeaders(array(
            $paginator->sort('id'),
            $paginator->sort('parent_id'),
            $paginator->sort('model'),
            $paginator->sort('foreign_key'),
            $paginator->sort('alias'),
            __('Actions', true),
        ));
        echo $html->tag('thead', $tableHeaders);

        $rows = array();
        foreach ($aros AS $aro) {
            $actions  = $html->link($this->Html->image('/acl/img/icons/pencil.png', array('alt' => __('Edit', true))), array('action' => 'edit', $aro['AclAro']['id']), array('escape' => false));
            $actions .= ' ' . $html->link($this->Html->image('/acl/img/icons/cross.png', array('alt' => __('Delete', true))), array(
                'action' => 'delete',
                $aro['AclAro']['id'],
     #           'token' => $this->params['_Token']['key'],
            ), array('escape' => false), __('Are you sure?', true));

            $rows[] = array(
                $aro['AclAro']['id'],
                $aro['AclAro']['parent_id'],
                $aro['AclAro']['model'],
                $html->link($aro['AclAro']['foreign_key'], array('plugin' => 0, 'controller' => Inflector::pluralize(strtolower($aro['AclAro']['model'])), 'action' => 'edit', $aro['AclAro']['foreign_key'])),
                $aro['AclAro']['alias'],
                $actions,
            );
        }

        echo $html->tag('tbody', $html->tableCells($rows));
    ?>	<tfoot>
			<td class="col-chk"><input type="checkbox" name="" /></td>
			<td colspan="14">

			</td>
			<tr>
				<td colspan="14">
                    <div class="pagination">
                        	<?php echo $this->Paginator->first('<'.__('First', true), array()); ?>
                        	<?php echo $this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>
                        	<?php echo $this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>
                        	<?php echo $this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>
                        	<?php echo $this->Paginator->last(__('Last', true).'>', array()); ?>
                    </div>
                    <div class="clear"></div>
                </td>
			</tr>
		</tfoot>
    </table>
    </div>
</div>

