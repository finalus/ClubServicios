<?php
    if ($success == 1) {
        if ($permitted == 1) {
            echo $html->image('/acl/img/icons/tick_circle.png', array('class' => 'permission-toggle grant', 'rel' => $acoId.'-'.$aroId));
        } else {
            echo $html->image('/acl/img/icons/cross_circle.png', array('class' => 'permission-toggle deny', 'rel' => $acoId.'-'.$aroId));
        }
    } else {
        __('Error', true);
    }

    Configure::write('debug', 0);
?>
