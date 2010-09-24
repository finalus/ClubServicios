<?php
class ImportController extends DataflowAppController {

	var $name = 'Import';
    
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Import');
    var $components = array('RequestHandler');
    var $uses = array('Import.ImportUpload');

    public function admin_index() {
        $this->set('importUploads', $this->paginate('ImportUpload'));
    }

    public function admin_upload() {
        if (!empty($this->data)) {
            $this->ImportUpload->create();
            if ($this->ImportUpload->save($this->data)) {
                $this->data['ImportUpload']['id'] = $this->ImportUpload->getInsertID();
                $this->redirect(array('controller' => 'import', 'action' => 'parse', $this->data['ImportUpload']['id']));    
            }
        }
    }

    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for the Import', true));
            $this->redirect(array('action' => 'index'));
        }
        $import = $this->ImportUpload->read(null, $id);
        if (empty($import)) {
            $this->Session->setFlash(__('The import ID was invalid.', true));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->ImportUpload->del($id)) {
            $this->Session->setFlash(__('The import was successfully deleted.', true));
        } else {
            $this->Session->setFlash(__('There was a problem deleting the import.', true));
        }
        $this->redirect(array('plugin' => 'import', 'controller' => 'import', 'action' => 'index'));
    }

    public function admin_parse($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid id for Import', true));
            $this->redirect(array('action' => 'index'));
        }
        
        if (!empty($this->data)) {
            $this->data['ImportUpload']['status_id'] = 2; //Import Parsed 
            $id = $this->data['ImportUpload']['id'];
            $this->ImportUpload->read(null, $id);
            if ($this->ImportUpload->save($this->data)) {
                $this->Session->setFlash(__('Import has been parsed. Please create a map.', true));
                $this->redirect(array('action' => 'map', $id));
            } else {
                $this->Session->setFlash(__('There was a problem parsing the import. Please review the errors and try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->ImportUpload->read(null, $id);
            if (empty($this->data)) {
                $this->Session->setFlash(__('Invalid Import', true));
            }
        }
        $this->set('importUpload', $this->data);
        $this->set('sources', $this->ImportUpload->ImportSource->find('list'));
        $this->set('importDelimiters', $this->ImportUpload->ImportDelimiter->find('list'));
    }

    public function admin_map($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid id for Import', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->data)) {
            if ($this->data['ImportUpload']['new_mapping']) {
                if ($this->ImportUpload->ImportMapping->save($this->data)) {
                    $this->data['ImportMapping']['id'] = $this->ImportUpload->ImportMapping->id;
                    $this->data['ImportUpload']['mapping_id'] = $this->data['ImportMapping']['id'];
                }
            } else {
                if (!empty($this->data['ImportUpload']['mapping_id'])) {
                    $importMapping = $this->ImportUpload->ImportMapping->read(null, $this->data['ImportUpload']['mapping_id']);
                    $this->data['ImportMapping'] = $importMapping['ImportMapping'];
                    $this->ImportUpload->ImportMapping->ImportMappingField->deleteAll(array('mapping_id' => $this->data['ImportMapping']['id']));
                }
            }
            
            if (!empty($this->data['ImportMappingField'])) {
                foreach($this->data['ImportMappingField'] as $key=>$value) {
                    $this->data['ImportMappingField'][$key]['mapping_id'] = $this->data['ImportMapping']['id'];
                    if ($value['column_key'] == '') {
                        unset($this->data['ImportMappingField'][$key]);
                    }
                }
            }
 
            $this->ImportUpload->ImportMapping->saveAll($this->data);

            $this->data['ImportUpload']['status_id'] = 3;  //Import Mapped 
            $id = $this->data['ImportUpload']['id'];
            $this->ImportUpload->recursive = 1;
 
            $importUpload = $this->ImportUpload->read(null, $id);

            if ($this->ImportUpload->save($this->data)) {
                $this->Session->setFlash(__('Import has been mapped.', true));
                $this->redirect('/admin/import/');
            } else {
                $this->Session->setFlash(__('There was a problem mapping the import.  Please review the errors and try again.', true));
            }
        }

        if (empty($this->data)) {
            $this->data = $this->ImportUpload->read(null, $id);
            if (empty($this->data)) {
                $this->Session->setFlash(__('Invalid import', true));
            }
        }

        $appModels = array();
        foreach(Configure::listObjects('model') as $value) {
            $appModels[$value] = $value;
        }

        $this->data['ImportUpload']['show_mapping'] = true;

        $this->set('appModels', $appModels);
        $this->set('importUpload', $this->data);
        
        $this->set('mappings', $this->ImportUpload->ImportMapping->find('list'));
        
    }

    public function admin_start($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for import', true));
            $this->redirect('/admin/import');
        }
        $import = $this->ImportUpload->read(null, $id);
        if (empty($import)) {
            $this->Session->setFlash(__('The import id was invalid', true));
            $this->redirect('/admin/import');
        }
        
        if ($import['ImportUpload']['status_id'] >= 3) {
            $import['ImportUpload']['status_id'] = 4;
            if ($this->ImportUpload->save($import)) {
                $this->Session->setFlash(__('Import is ready.  Waiting to be processed', true));
            }
        }
        $this->redirect('/admin/import');
    }

    public function admin_preview() {
        if ($this->RequestHandler->isAjax()) {
            $this->ImportUpload->recursive = true;

            if (!empty($this->data)) {
                $importUpload = $this->ImportUpload->read(null, $this->data['ImportUpload']['id']);

                if (!empty($this->data['ImportUpload']['import_delimiter_id'])) {
                    $importMasterDelimiter = $this->ImportUpload->ImportDelimiter->read(null, $this->data['ImportUpload']['import_delimiter_id']);
                } else {
                    $importMasterDelimiter['ImportDelimiter'] = $importUpload['ImportDelimiter'];
                }

                if (!$filename = $this->ImportUpload->filePath()) {
                    $this->Session->setFlash(__('The file %s does not exist on this server.', $importUpload['ImportUpload']['filename']));
                }

                if (empty($importUpload['ImportUpload']['show_mapping'])) {
                    $importUpload['ImportUpload']['show_mapping'] = $this->data['ImportUpload']['show_mapping'];
                }

                if (!empty($this->data['ImportUpload']['import_delimiter_id'])) {
                    $importUpload['ImportUpload']['import_delimiter_id'] = $this->data['ImportUpload']['import_delimiter_id'];
                }
               
                if (!empty($this->data['ImportUpload']['text_qualifier'])) {
                    $importUpload['ImportUpload']['text_qualifier'] = $this->data['ImportUpload']['text_qualifier'];
                }
                if (isset($this->data['ImportUpload']['use_header_row'])) {
                    $importUpload['ImportUpload']['use_header_row'] = $this->data['ImportUpload']['use_header_row'];
                }
                $importUpload['ImportUpload']['use_excel_reader'] = $importMasterDelimiter['ImportDelimiter']['use_excel_reader'];
                $importUpload['ImportUpload']['text_delimiter'] = $importMasterDelimiter['ImportDelimiter']['delimiter'];
                $importUpload['ImportUpload']['file_path'] = $filename;

                if (!empty($this->data['ImportMapping']) || !empty($this->data['ImportUpload'])) {
                    $model = null;
                    if (!empty($this->data['ImportUpload']['mapping_id'])) {
                        $importMapping = $this->ImportUpload->ImportMapping->read(null, $this->data['ImportUpload']['mapping_id']);
                        if (empty($this->data['ImportMapping']['mapping_model'])) {
                            $model = $importMapping['ImportMapping']['mapping_model'];
                        } else {
                            $model = $this->data['ImportMapping']['mapping_model'];
                        }
                        $importUpload['ImportMappingField'] = $importMapping['ImportMappingField'];
                    }

                    if (!empty($this->data['ImportMapping']['mapping_model'])) {
                        $model = $this->data['ImportMapping']['mapping_model'];
                    }

                    $appModelColumns = null;
                    if ($model) {
                        $appModelColumns = array();
                        $this->loadModel($model);
                        $schema = $this->{$model}->schema();
                        $associated = $this->{$model}->getAssociated();

                        if (is_array($schema)) {
                            foreach ($schema as $key => $value) {
                                if (isset($value['key']) && $value['key'] == 'primary') {
                                    continue;
                                }
                                $appModelColumns[$this->{$model}->name][$this->{$model}->name.".".$key] = Inflector::humanize($key);
                            }
                            if (is_array($associated)) {
                                foreach ($associated as $model=>$value) {
                                    $this->loadModel($model);
                                    $schema = $this->{$model}->schema();
                                    foreach ($schema as $key => $value) {
                                        if (isset($value['key']) && $value['key'] == 'primary') {
                                            continue;
                                        }
                                        $appModelColumns[$this->{$model}->name][$this->{$model}->name.".".$key] = Inflector::humanize($key);
                                    }
                                }
                            }
                        }
                    }

                    $this->set('appModelColumns', $appModelColumns);
                }
                $this->set('importUpload', $importUpload);
            }
            Configure::write('debug', 0); 
        }
    }

    function admin_test($id = null) {
        $this->ImportUpload->ImportMapping->recursive = 1;
        $importUpload = $this->ImportUpload->Import->read(null, $id);
        echo "<pre>";
        print_r($importUpload);
        die();

        Configure::write('debug', 0);
        exit();
    }


}

?>
