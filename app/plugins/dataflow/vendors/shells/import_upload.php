<?php

class ImportUploadShell extends Shell {

    var $uses = array('Xport.ImportUpload');

    var $tasks = array('Parser');

    protected $_availableImportFiles = array();

    protected $_availableImportIds = array();
    
    protected $_availableImports = array();

    function main() {
        
        parent::loadTasks();

        $this->out('Import Upload Shell');
        $this->hr();

        $this->_setAvailableImportFiles();


        if (empty($this->args)) {
            $imports = $this->_interactive();
        } else {
            $imports = $this->_determineImportIds(implode(' ', $this->args));
        }
        
        if (!empty($imports)) {
            foreach ($imports as $import) {
                $importUpload = $this->ImportUpload->create($import);
                if ($filename = $this->ImportUpload->filePath()) {
                    $importUpload['ImportUpload']['file_path'] = $filename;
                    if (!empty($import['ImportDelimiter'])) {
                        $options = array('delimiter' => $import['ImportDelimiter']['delimiter'],
                                         'excel_reader' => $import['ImportDelimiter']['use_excel_reader'],
                                         'qualifier' => $import['ImportUpload']['text_qualifier']);
                    }
                    if ($this->Parser->execute($filename, $options)) {
                        $this->ImportUpload->saveField('total', $this->Parser->getRowCount());
                        $this->ImportUpload->saveField('is_importing', 1);
                    }
                    die('here');
                } else {
                
                }
 
                  


            }
        }

    }

    protected function _setAvailableImportFiles() {
        $importUploads = $this->ImportUpload->find('all', array('conditions' => array('ImportUpload.status_id' => 4, 'ImportUpload.is_importing' => 0, 'ImportUpload.is_imported' => 0), 'contain' => array('ImportDelimiter', 'ImportMapping' => array('ImportMappingField')), 'order' => 'ImportUpload.created ASC'));

        if (!empty($importUploads)) {
            foreach ($importUploads as $importUpload) {
                $this->_availableImportFiles[] = $importUpload['ImportUpload']['filename'];
                $this->_availableImportIds[] = $importUpload['ImportUpload']['id'];
                $this->_availableImports[] = $importUpload;
            }
        }
    }


    protected function _interactive() {

        $this->out(__('Possible Import Ids based on your current data set:', true));
        
        $i = 1;
        foreach ($this->_availableImportFiles as $importFileName) {
            $this->out($i++ .". ". $importFileName);
        }

        $enteredFileName = '';
        while ($enteredFileName == '') {
            $enteredFileName = $this->in(__("Enter one or more numbers from the list above seperated by a space, 'a' for all or 'q' for exit", true), null, 'q');

            if (strtolower($enteredFileName) === 'q') {
                $this->out(__('Byebye', true));
                $this->_stop();
            }
        }

        return $this->_determineImportNumber($enteredFileName);
    }

    protected function _checkIfAll($enteredValue) {
        if (strtolower($enteredValue) === 'a' || strtolower($enteredValue) === 'all') {
            return true;
        } 
        return false;
    }

    protected function _checkIfMultiple($enteredName) {
        if (preg_match('/[^a-z0-9.]/i', $enteredName)) {
            return preg_split('/[^a-z0-9.]/i', $enteredName);
        }
        return false;
    }

    protected function _determineImportIds($enteredIds) {
        if ($this->_checkIfAll($enteredIds)) {
            return $this->_availableImports;
        }

        $enteredImportIds = $this->_checkIfMultiple($enteredIds);
        if (!$enteredImportIds) {
            $enteredImportIds = array($enteredIds);
        }

        $selectedImports = array();
        foreach ($enteredImportIds as $enteredImportId) {
            if (is_numeric($enteredImportId)) {
                if (in_array($enteredImportId, $this->_availableImportIds)) {
                    foreach ($this->_availableImportIds as $key => $availableIds) {
                        if ($availableIds == $enteredImportId) {
                            if (isset($this->_availableImports[$key])) {
                                $selectedImports[] = $this->_availableImports[$key];
                            }
                            unset($this->_availableImports[$key]);
                        }
                    }
                }
            } elseif (in_array($enteredImportId, $this->_availableImportFiles)) {
                foreach($this->_availableImportFiles as $key => $availableFiles) {
                    if ($availableFiles == $enteredImportId) {
                        if (isset($this->_availableImports[$key])) {
                            $selectedImports[] = $this->_availableImports[$key];
                        }
                        unset($this->_availableImports[$key]);
                    }
                }
            }
        }
        return $selectedImports;
    }

    protected function _determineImportNumber($enteredFileName) {
        
        if ($this->_checkIfAll($enteredFileName)) {
            return $this->_availableImports;
        }

        $enteredFileNames = $this->_checkIfMultiple($enteredFileName);
        if (!$enteredFileNames) {
            $enteredFileNames = array($enteredFileName);
        }

        $selectedImports = array();
        foreach ($enteredFileNames as $enteredFileName) {
            if (is_numeric($enteredFileName) > 0) {
                if ($enteredFileName <= count($this->_availableImportIds)) {
                    if (isset($this->_availableImportIds[intval($enteredFileName) - 1])) {
                        $selectedImports[] = $this->_availableImports[intval($enteredFileName) - 1];
                    }
                    unset($this->_availableImports[intval($enteredFileName) - 1]);
                }
            } elseif (in_array($enteredFileName, $this->_availableImportFiles)) {
                foreach ($this->_availableImportFiles as $key => $availableFiles) {
                    if ($availableFiles == $enteredFileName) {
                        if (isset($this->_availableImports[$key])) {
                            $selectedImports[] = $this->_availableImports[$key];
                        }
                        unset($this->_availableImports[$key]);
                    }
                }
            }
        }
        return $selectedImports;
    }



   
 
  /**
   * Displays help contents
   */
  public function help() {
    $this->out('Import Upload Shell:');
    $this->hr();
    $this->out('Import uploaded files into mapped models');
    $this->hr();
    $this->out("Usage: cake import_upload [all|<arg1> [<arg2>]...]");
    $this->hr();
    $this->out('Arguments:');
    $this->out("\n<no arguments>\n\tInteractive mode e.g. cake import_upload");
    $this->out("\nall\n\tImports all import uploads  e.g. cake import_upload all");
    $this->out("\n<id>\n\tOne or more Import Upload Ids e.g. cake import_upload 1 2.");
    $this->out("");

  }

}
?>
