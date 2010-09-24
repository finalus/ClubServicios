<?php

App::import('Core', array('Folder', 'File'));

class ImportUploadBehavior extends ModelBehavior {

    var $options = array(
        'required'  => false,
        'directory' => 'imports',
        'allowed_extension' => array(),
        'allowed_mime_type' => array('.csv'),
        'allowed_size'  => 1048576,
        'random_filename' => true,
    );

    var $errors = array();
    
    var $__fields;

    function setup(&$model, $config = array()) {
        $config_tmp = array();
        
        foreach ($config as $field => $options) {
            if (!$model->hasField($field)) {
                unset($config[$field]);
                unset($model->data[$model->name][$field]);
                continue;
            }

            $options = array_merge($this->options, $options);
            
            if (substr($options['directory'], -1) != '/') {
                $options['directory'] = $options['directory'].DS;
            }

            $config_tmp[$field] = $options;
        }
        $this->__fields = $config_tmp;
    }

    function beforeSave(&$model) {
        if (count($this->__fields)>0) {
            foreach ($this->__fields as $field=>$options) {
                if (!$model->data[$model->name][$field]) {
                    continue;
                }
                
                if (isset($model->data) && !is_array($model->data[$model->name][$field])) {
                    unset($model->data[$model->name][$field]);
                    continue;
                }
                
                if ($model->data[$model->name][$field]['error'] > 0) {
                    continue;
                }

                $folder = new Folder(TMP . $options['directory'], true, 0777);
                if (!isset($options['random_filename']) || !$options['random_filename']) {
                    $saveAs = $folder->path . $model->data[$model->name][$field]['name'];
                } else {
                    $uniqueFileName = sha1(uniqid(rand(), true));
                    $extension = explode('.', $model->data[$model->name][$field]['name']);
                    $saveAs    = $folder->path . $uniqueFileName . '.' . $extension[count($extension)-1];
                }

                if (!move_uploaded_file($model->data[$model->name][$field]['tmp_name'], $saveAs)) {
                    unset($model->data[$model->name][$field]);
                    continue;
                }

                $model->data[$model->name]['status_id'] = 1;
                $model->data[$model->name][$field] = basename($saveAs);
            }
        }
        return true;
    }

    function beforeValidate(&$model) {
        foreach ($this->__fields as $field => $options) {
            if (!empty($model->data[$model->name][$field]['type']) && !empty($options['allowed_mime'])) {
                if (count($options['allowd_extension']) > 0) {
                    $matches = 0;
                    foreach ($options['allowed_extension'] as $extension) {
                        if (strtolower($substr($model->data[$model->name][$field]['name'], -strlen($extension))) == $extension) {
                            $matches++;
                        }
                    }

                    if ($matches == 0) {
                        $allowed_ext = implode(', ', $options['allowed_extension']);
                        $model->invalidate($field, sprintf(__('Invalid file type.  Only %s allowed.', true), $allowed_ext));
                        continue;
                    }

                    if (count($options['allowed_mime']) > 0 && !in_array($model->data[$model->name][$field]['type'], $options['allowed_mime'])) {
                        $model->invalidate($field, __('Invalid file type.', true));
                        continue;
                    }

                    if ($model->data[$model->name][$field]['size'] > $options['allowed_size']) {
                        $model->invalidate($field, sprintf(__('The file you uploaded exceeds the maximum file size of %d bytes', true), $options['allowed_size']));
                        continue;
                    }
                } else {
                    if (is_array($options['required'])) {
                        foreach ($options['required'] as $action => $required) {
                            $empty = false;

                            switch ($action) {
                                case 'add':
                                case 'upload':
                                    if ($required == true && empty($model->data[$model->name]['id'])) {
                                        $empty = true;
                                        continue;
                                    }
                                    break; 
                                case 'edit':
                                    if ($required == true && !empty($model->data[$model->name]['id'])) {
                                        $empty = true;
                                        continue;
                                    }
                                    break;
                            }

                            if ($empty) {
                                $model->invalidate($field, sprintf(__('%s is required.', true), Inflector::humanize($field)));
                                continue;
                            }
                        }
                    } elseif ($options['required'] == true) {
                        $model->invalidate($field, sprintf(__('%s is required.', true), Inflector::humanize($field)));
                        continue;
                    }
                }
            }
        }
    }

    function beforeDelete(&$model) {
        if (count($this->__fields) > 0) {
            $model->read(null, $model->id);
            if (isset($model->data)) {
                foreach ($this->__fields as $field => $options) {
                    if (!empty($model->data[$model->name][$field])) {
                        $this->_removeImport($model->data[$model->name][$field], $options);
                    }
                }
            }
        }
        return true;
    }

    private function _removeImport($file, $options) {
        $folder = new Folder(TMP . $options['directory']);
        if (file_exists($folder->path . $file)) {
            unlink($folder->path . $file);
        }
    }

    function filePath(&$model) {
        if (count($this->__fields) >0) {
           foreach ($this->__fields as $field => $options) {
                if (!$model->data[$model->name][$field]) {
                    continue;
                }
                $folder = new Folder(TMP . $options['directory']);
                if (file_exists($folder->path . $model->data[$model->name][$field])) {
                    return $folder->path . $model->data[$model->name][$field];
                }
           }
        } 
        return false;
    }
            
}
                                
