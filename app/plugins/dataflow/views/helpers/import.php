<?php

App::import('Core', array('Folder', 'File'));

class ImportHelper extends AppHelper {
    
    var $filename = null;

    var $count = 0;


    private function getFilename() {
        return $this->filename;
    }

    private function _rawParse($filename, $attributes) {

        App::import('Vendor', 'import.excelreader/excelreader');

        $buffer = array();
        if (file_exists($filename)) {
            if (isset($attributes['delimiter']) && (isset($attributes['excel_reader']) && $attributes['excel_reader'])) {
                $xl = new ExcelReader();
                $xl->read($filename);
                $tmp_array = $xl->toArray();
                for($i=0;$i<10;$i++) {
                    if (isset($tmp_array[$i])){
                    $buffer[] = implode("\t", $tmp_array[$i]);
                    }
                }
            } else {
                $file = new File($filename);
                if ($file->open('r', true)) {
                    for($i=0;$i<10;$i++) {
                        if (isset($attributes['delimiter']) && $attributes['delimiter'] != "") {
                            if (isset($attributes['qualifier']) && $attributes['qualifier'] != "") {
                                $buffer[] = @fgetcsv($file->handle, null, $attributes['delimiter'], $attributes['qualifier']);
                            } else {
                                $buffer[] = @fgetcsv($file->handle, null, $attributes['delimiter']);
                            }
                        }
                    }
                    $file->close();
                } else {
                    // Error
                }
            }
        }
        return $buffer;
    }

    function parseFile($filename, $attributes = array()) {
        $array = array();
        $i =0;
        if ((isset($attributes['delimiter']) && $attributes['delimiter'] != "") || (isset($attributes['excel_reader']) && $attributes['excel_reader'])) {
            if (isset($attributes['excel_reader']) && $attributes['excel_reader']) {
                foreach ($this->_rawParse($filename, $attributes) as $preview_line) {
                    $line_array = explode("\t", $preview_line);
                    if ($i==0) {
                        $this->count = count($line_array);
                    }
                    $array[] = $line_array;
                }
            } else {
                foreach ($this->_rawParse($filename, $attributes) as $preview_line) {
                    if ($i==0) {
                        $this->count = count($preview_line);
                    }
                    $array[] = $preview_line;
                    $i++;
                }
            }
        } else {
            $array[] = implode(" ", $this->_rawParse($filename, $attributes));
        }
        return $array;
    }

    function getColumnCount($filename, $attributes) {
        $prev_array = $this->parseFile($filename, $attributes);
        if (is_array($prev_array) && count($prev_array) > 0) {
            return $prev_array[0];
        }
        return 1;
    }

    function getRowCount($filename, $attributes) {
        App::import('Vendor', 'import.excelreader/excelreader');
        if (is_file($filename)) {
            if (isset($attributes['delimiter']) && (isset($attributes['excel_reader']) && $attributes['excel_reader'])) {
                $xl = new ExcelReader();
                $xl->read($filename);
                $tmp_array = $xl->toArray();
                return count($tmp_array);
            } else {
                $row_count = count(file($filename));
            }
        }
        return false;
    }

    function getPercentDone($data) {
        if (!empty($data['ImportUpload'])) {
            if ($data['ImportUpload']['total'] > 0) {
                return number_format(($data['ImportUpload']['total_imported'] / $data['ImportUpload']['total']) * 100, 2);
            }
            return 0;
        }
    }

    function getProgress($data) {
        App::import('Helper', 'Time');
        $time = new TimeHelper();
        if (is_array($data)) {
            if (!empty($data['ImportStatus'])) {
                switch($data['ImportStatus']['id']) {
                    case 1:
                        return sprintf(__($data['ImportStatus']['message'], true), $time->nice($data['ImportUpload']['created']));
                    break;
                    case 2:
                        return __($data['ImportStatus']['message'], true);
                    break;
                    case 3:
                        return __($data['ImportStatus']['message'], true);
                    break;
                    case 4:
                        return __($data['ImportStatus']['message'], true);
                    break;
                    case 5:
                        return sprintf(__($data['ImportStatus']['message'], true), $data['ImportUpload']['total_imported'], $data['ImportUpload']['total']);
                    break;
                    case 6:
                        return __($data['ImportStatus']['message'], true);
                    break;
                    default:
                        return __('Import has no status', true);

                }
            }
        }
    }
}
