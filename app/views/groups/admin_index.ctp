<?php
	$html->addCrumb(__('System', true), '/admin/system');
	$html->addCrumb(__('Permissions', true), '/admin/acl/acl_permissions');
	$html->addCrumb(__('Groups', true));
	
	$sidebar->addTitle(__('Group Menu', true));
	$sidebar->addMenu('user', array('title' => 'Manage Group', 'sort_order' => 20, 'url' => array('plugin' => false, 'controller' => 'group', '')));
	$sidebar->addMenu('add_group', array('title' => 'Add New Group', 'sort_order' => 30, 'url' => array('plugin' => false, 'controller' => 'user', 'action' => 'add')), 'group');

?>

<div class="groups index box">
	<h4 style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;" class="white rounded_by_jQuery_corners"><?php __('Groups');?></h4>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
    	<table cellpadding="0" cellspacing="0" class="table-long">
            <thead>
    	        <tr>
                   	<th>&nbsp;</th>
            		<th><?php echo $this->Paginator->sort('id');?></th>
					<th><?php echo $this->Paginator->sort('name');?></th>
			      	<th class="actions"><?php __('Actions');?></th>
	            </tr>
            </thead> 
            <tbody>
	<?php
	$i = 0;
	$count = 0;
	if (!empty($groups)):
		foreach ($groups as $group):
			$count = count($group['Group']);
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td class="col-chk" ><input type="checkbox" name="mass" value="<?php echo $group['Group']['id']; ?>" /></td>
			<td><?php echo $group['Group']['id']; ?>&nbsp;</td>
			<td><?php echo $group['Group']['name']; ?>&nbsp;</td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $group['Group']['id']), array('title' => __('Edit', true), 'class' => 'table-edit-link')); ?>
				<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $group['Group']['id']), array('title' => __('Delete', true), 'class' => 'table-delete-link'), sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['id'])); ?>
			</td>
		</tr>
		<?php 
 endforeach;
else: ?>
	<tr>
		<td colspan="6">asdasdf</td>
	</tr>
<?php
endif;
?>
            </tbody>

            <tfoot>
				<tr>
					<td class="col-ckk"><input type="checkbox" class="check-all" /></td>
					<td colspan="<?php echo $count+1?>">
						<select></select>
					</td>
				</tr>
                <tr>
                    <td colspan="<?php echo $count+2;?>">
                        <div class="pagination">
                            	<?php echo $this->Paginator->first('<<'.__('First', true), array()); ?>
                            	<?php echo $this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>
                            	<?php echo $this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->last(__('Last', true).'>>', array()); ?>
                        </div>
                    </td>
                </tr>
            </tfoot>
	</table>
	</div>
</div>
