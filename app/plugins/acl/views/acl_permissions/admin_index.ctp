<?php
    $html->script('/acl/js/acl_permissions.js', false);
    $html->css('/acl/css/style', null, array('inline' => false)); 

	$html->addCrumb(__('System', true), '/admin/system');
	$html->addCrumb(__('Permissions', true));
?>
<div class="box permissions form">
	<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
	    <h4 class="white"><?php echo __('Permissions', true); ?></h4>
    </div>
    <div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners form">
    	<table cellpadding="0" cellspacing="0" class="table-long">
    <?php
        $groupTitles = array_values($groups);
        $groupIds   = array_keys($groups);

        $tableHeaders = array(
            __('Alias', true),
        );
        $tableHeaders = array_merge($tableHeaders, $groupTitles);
        $tableHeaders =  $html->tableHeaders($tableHeaders);
        echo $html->tag('thead', $tableHeaders);

        $currentController = '';
        foreach ($acos AS $id => $alias) {
            $class = '';
            if(substr($alias, 0, 1) == '_') {
                $level = 1;
                $class .= 'level-'.$level;
                $oddOptions = array('class' => 'hidden controller-'.$currentController);
                $evenOptions = array('class' => 'hidden controller-'.$currentController);
                $alias = Inflector::humanize(substr_replace($alias, '', 0, 1));
            } else {
                $level = 0;
                $class .= ' controller expand';
                $oddOptions = array();
                $evenOptions = array();
                $currentController = Inflector::humanize($alias);
            }
            
            $row = array(
                $html->div($class, $alias),
            );

            foreach ($groups AS $groupId => $groupTitle) {
                if ($level != 0) {
                    #if ($groupId != 1) {
                        if ($permissions[$id][$groupId] == 1) {
                            $row[] = $html->image('/acl/img/icons/tick_circle.png', array('class' => 'permission-toggle grant', 'rel' => $id.'-'.$groupsAros[$groupId]));
                        } else {
                            $row[] = $html->image('/acl/img/icons/cross_circle.png', array('class' => 'permission-toggle deny', 'rel' => $id.'-'.$groupsAros[$groupId]));
                        }
                    #} else {
                    #    $row[] = $html->image('/acl/img/icons/tick_circle_disabled.png', array('class' => 'permission-disabled'));
                    #}
                } else {
                    $row[] = '';
                }
            }
			echo $html->tag('tbody', $html->tableCells(array($row), $oddOptions, $evenOptions));
        }
    ?>
    </table>
    </div>
</div>
