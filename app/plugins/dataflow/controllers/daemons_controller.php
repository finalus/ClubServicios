<?php


class DaemonsController extends DataflowAppController {

    var $name = 'Daemons';

    var $uses = array('Import.ImportUpload');

  


    function beforeFilter() {
        parent::beforeFilter();

        if (!empty($this->Auth)) {
            $this->Auth->allow('importer');
        }
    }

    function importer() {
        App::import('Helper', 'Time');
       
        #if (Cache::read('importer.pid')) {
        #    return false;
        #} else {
        #    Cache::write('importer.pid', microtime(),1);
        #}

        ini_set('max_execution_time', 0);

       
        $imports = $this->ImportUpload->find('all', array('conditions' => Array('ImportUpload.status_id' => 4), 'contain' => array('ImportMapping' => array('ImportMappingField')), 'limit' => 10, 'order' => 'ImportUpload.created'));
        if (!empty($imports)) {
            foreach ($imports as $import) {
                echo $time->nice(time()) . __METHOD__ . ":: ".__('Importing', true) ." ". $import['ImportUpload']['filename'] ."\n";
                $this->ImportUpload->saveField('status_id', 5);
                $this->ImportUpload->saveField('is_importing', 1);
            }
            
        }
        die();
    }


}
