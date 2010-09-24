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
<div class="in author">
	<h2><?php echo "<?php __('{$pluralHumanName}');?>";?></h2>
        <ul class="content_box_buttons">
            <li><?php echo "<?php echo \$this->Html->link(sprintf(__('Add New %s', true), __('{$singularHumanName}', true)), array('action' => 'add'), array('class' => 'button')); ?>";?></li>
<?php
    $done = array();
    foreach ($associations as $type => $data) {
        foreach ($data as $alias => $details) {
            if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
                echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('List %s', true), __('" . Inflector::humanize($details['controller']) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'index')); ?> </li>\n";
                echo "\t\t<li><?php echo \$this->Html->link(sprintf(__('New %s', true), __('" . Inflector::humanize(Inflector::underscore($alias)) . "', true)), array('controller' => '{$details['controller']}', 'action' => 'add')); ?> </li>\n";
                $done[] = $details['controller'];
            }
        }
    }
?>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="in">
    	<table width="850" border="0" cellspacing="0" cellpadding="10" class="table_main" >
            <thead>
    	        <tr>
                    <th><input type="checkbox" class="check-all" /></th>
	<?php  foreach ($fields as $field):?>
            		<th><strong><?php echo "<?php echo \$this->Paginator->sort('{$field}');?>";?></strong></th>
	<?php endforeach;?>
		            <th class="actions"><?php echo "<?php __('Actions');?>";?></th>
	            </tr>
            </thead> 

            <tbody>
	<?php
	echo "<?php
	\$i = 0;
	foreach (\${$pluralVar} as \${$singularVar}):
		\$class = null;
		if (\$i++ % 2 == 0) {
			\$class = ' class=\"gray\"';
		}
	?>\n";
	echo "\t<tr<?php echo \$class;?>>\n";
        echo "\t\t<td><input type=\"checkbox\" name=\"mass\" value=\"<?php echo \${$singularVar}['{$modelClass}']['{$primaryKey}']; ?>\" /></td>\n";
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
	 	echo "\t\t\t<?php echo \$this->Html->link(\$this->Html->image('admin/icons/pencil.png', array('alt' => __('Edit', true))), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false, 'title' => __('Edit', true))); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->tag('span', '|', array('class' => 'v_line')); ?>\n";
	 	echo "\t\t\t<?php echo \$this->Html->link(\$this->Html->image('admin/icons/cross.png', array('alt' => __('Delete', true))), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('escape' => false, 'title' => __('Delete', true)), sprintf(__('Are you sure you want to delete # %s?', true), \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="<?php echo count($fields) + 2; ?>">
                        <div class="pagination">
                            <?php echo "\t<?php echo \$this->Paginator->first('<<'.__('First', true), array()); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>\n" ?>
                            <?php echo "\t<?php echo \$this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>\n"; ?>
                            <?php echo "\t<?php echo \$this->Paginator->last(__('Last', true).'>>', array()); ?>\n"; ?>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </tfoot>
	</table>
</div>
