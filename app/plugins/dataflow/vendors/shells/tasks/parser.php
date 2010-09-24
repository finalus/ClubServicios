<?php
App::import('Core', array('Folder', 'File'));

class ParserTask extends Shell {

    var $filename = null;

    var $count = 0;

    var $delimiter;

    var $use_excel_reader;

    var $qualifier;

    var $options = array();

    var $tags = array('delimiter', 'excel_reader', 'qualifier');


    
    function execute($filename, $attributes) {
        App::import('Vendor', 'xport.excelreader/excelreader');

        if (!file_exists($filename)) {
            return false;
        }
       
        $this->filename = $filename;

        if (is_array($attributes)) {
            foreach ($attributes as $key => $value) {
                if (in_array($key, $this->tags)) {
                    $this->options[$key] = $value;
                }
            }
        }
        return true;
    }

    protected function _rawParse() {
        if (file_exists($this->filename)) {
            if (isset($this->options['delimiter']) && (isset($this->options['excel_reader']) && $this->options['excel_reader'])) {
                $xl = new ExcelReader();
                $xl->read($this->filename);
                $tmp_array = $xl->toArray();
                for($i=0;$i<10;$i++) {
                    if (isset($tmp_array[$i])){
                    $buffer[] = implode("\t", $tmp_array[$i]);
                    }   
                }   
            } else {
                $file = new File($this->filename);
                if ($file->open('r', true)) {
                    for($i=0;$i<10;$i++) {
                        if (isset($this->options['delimiter']) && $this->options['delimiter'] != "") {
                            if (isset($this->options['qualifier']) && $this->options['qualifier'] != "") {
                                $buffer[] = @fgetcsv($file->handle, null, $this->options['delimiter'], $this->options['qualifier']);
                            } else {
                                $buffer[] = @fgetcsv($file->handle, null, $this->options['delimiter']);
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

    function parseFile() {
        $array = array();
        $i =0; 
        if ((isset($this->options['delimiter']) && $this->options['delimiter'] != "") || (isset($this->options['excel_reader']) && $this->options['excel_reader'])) {
            if (isset($this->options['excel_reader']) && $this->options['excel_reader']) {
                foreach ($this->_rawParse($this->filename, $this->options) as $preview_line) {
                    $line_array = explode("\t", $preview_line);
                    if ($i==0) {
                        $this->count = count($line_array);
                    }
                    $array[] = $line_array;
                }
            } else {
                foreach ($this->_rawParse($this->filename, $this->options) as $preview_line) {
                    if ($i==0) {
                        $this->count = count($preview_line);
                    }
                    $array[] = $preview_line;
                    $i++;
                }
            }
        } else {
            $array[] = implode(" ", $this->_rawParse($this->filename, $this->options));
        }
        return $array;
    }

    function getColumnCount() {
        $prev_array = $this->parseFile($this->filename, $this->options);
        if (is_array($prev_array) && count($prev_array) > 0) {
            return $prev_array[0];
        }
        return 1;
    }

    function getRowCount() {
        App::import('Vendor', 'import.excelreader/excelreader');
        if (is_file($this->filename)) {
            if (isset($this->options['delimiter']) && (isset($this->options['excel_reader']) && $this->options['excel_reader'])) {
                $xl = new ExcelReader();
                $xl->read($this->filename);
                $tmp_array = $xl->toArray();
                return count($tmp_array);
            } else {
                return count(file($this->filename));
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
}
