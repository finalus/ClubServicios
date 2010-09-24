<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
$sort_order = 10;
echo "<?php\n 
	echo \$html->addCrumb(__('{$singularHumanName}', true));\n
	echo \$sidebar->addTitle(sprintf(__('%s Menu', true), __('{$singularHumanName}', true)));
	echo \$sidebar->addMenu('". Inflector::underscore($pluralHumanName)."', array('title' => sprintf(__('Manage %s',true), __('{$pluralHumanName}', true)), 'sort_order' => {$sort_order}));
	echo \$sidebar->addMenu('new_". Inflector::underscore($singularHumanName)."', array('title' => sprintf(__('New %s',true), __('{$pluralHumanName}', true)), 'sort_order' => {$sort_order}, 'url' => array('action' => 'add')), '".Inflector::underscore($pluralHumanName)."');
	";
	$done = array();
	foreach ($associations as $type => $data) {
		foreach ($data as $alias => $details) {
			$sort_order = $sort_order+10;
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
				echo "echo \$sidebar->addMenu('".Inflector::underscore(Inflector::humanize($details['controller']))."', array('title' => sprintf(__('Manage %s', true), __('". Inflector::humanize($details['controller']) ."', true)), 'sort_order' => {$sort_order}));\n";
				echo "echo \$sidebar->addMenu('". Inflector::underscore(Inflector::humanize("list_".$details['controller']))."', array('title' => sprintf(__('List %s', true), __('" . Inflector::humanize($details['controller']) . "', true)), 'sort_order' => {$sort_order}, 'url' => array('controller' => '{$details['controller']}', 'action' => 'index')), '".Inflector::underscore(Inflector::humanize($details['controller']))."');\n";
				echo "echo \$sidebar->addMenu('". Inflector::underscore(Inflector::humanize("new_".$details['controller']))."', array('title' => sprintf(__('New %s', true), __('" . Inflector::humanize($alias) . "', true)), 'sort_order' => {$sort_order}, array('controller' => '{$details['controller']}', 'action' => 'add')), '".Inflector::underscore(Inflector::humanize($details['controller']))."');\n";
				

				
				
			}
		}
	}
echo "?>\n\n";
?>



<div class="box">
	<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
    	<h4 class="white"><?php echo "<?php __('{$pluralHumanName}');?>";?></h4>
	</div>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
		<table cellpadding="0" cellspacing="0" class="table-long">
			<thead>
				<tr>
					<th>&nbsp;</th>
<?php  foreach ($fields as $field):?>
            		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}');?>";?></th>
<?php endforeach;?>
		            <th class="actions"><?php echo "<?php __('Actions', true);?>";?></th>
	            </tr>
			</thead>
		    <tbody>
			<?php
			echo "<?php
			\$i = 0;
			\$count = 0;
			if (!empty(\${$pluralVar})):
			foreach (\${$pluralVar} as \${$singularVar}):
				\$class = null;
				\$count = count(\${$singularVar}['{$modelClass}']);
				if (\$i++ % 2 == 0) {
					\$class = ' class=\"altrow\"';
				}
			?>\n";
			echo "\t<tr<?php echo \$class;?>>\n";
		        echo "\t\t<td class=\"col-chk\"><input type=\"checkbox\" name=\"mass[]\" value=\"<?php echo \${$singularVar}['{$modelClass}']['{$primaryKey}']; ?>\" /></td>\n";
				foreach ($fields as $field) {
					$isKey = false;
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								$isKey = true;
								echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
								break;
							}
						}
					}
					if ($isKey !== true) {
						echo "\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
					}
				}

				echo "\t\t<td class=\"actions\">\n";
			 	echo "\t\t\t<?php echo \$this->Html->link(__('Edit', true), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('title' => __('Edit', true), 'class' => 'table-edit-link')); ?>\n";
			 	echo "\t\t\t<?php echo \$this->Html->link(__('Delete', true), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('title' => __('Delete', true), 'class' => 'table-delete-link'), sprintf(__('Are you sure you want to delete # %s?', true), \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
				echo "\t\t</td>\n";
			echo "\t</tr>\n";

			echo "<?php endforeach; ?>\n";
			echo "<?php else: ?>\n";
			echo "\t<tr>\n";
			echo "\t\t<td colspan=''><?php echo sprintf(__('No %s Found', true), __('{$singularHumanName}', true)); ?></td>\n";
			echo "\t</tr>\n";
			echo "<?php endif; ?>\n";
			?>
			</tbody>
			<tfoot>
				<td class="col-chk"><input type="checkbox" name="" /></td>
				<td colspan="<?php echo count($fields)+2;?>">
	
				</td>
				<tr>
					<td colspan="<?php echo count($fields) + 2; ?>">
                        <div class="pagination">
                            <?php echo "\t<?php echo \$this->Paginator->first('<'.__('First', true), array()); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>\n" ?>
                            <?php echo "\t<?php echo \$this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->last(__('Last', true).'>', array()); ?>\n"; ?>
                        </div>
                        <div class="clear"></div>
                    </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

