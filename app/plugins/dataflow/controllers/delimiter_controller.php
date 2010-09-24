<?php


class DelimiterController extends DataflowAppController {


    var $name = 'Delimiter';

    var $uses = array('ImportMasterDelimiter');




    function admin_index() {
        $this->set('delimiters', $this->paginate('ImportMasterDelimiter'));
    }

    function admin_add() {

    }

    function admin_edit() {

    }

}


