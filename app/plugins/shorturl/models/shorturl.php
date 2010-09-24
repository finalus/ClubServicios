<?php


class Shorturl extends ShorturlAppModel {

	var $name = 'Shorturls';  
	
	var $options = array(
		'fields' => array('url'),
		'length' => 5,
		'url' => ''
	); 
	
	function __construct($id = false, $table = null, $ds = null) { 
		parent::__construct($id, $table, $ds);
		
		$config = $this->options; 
		if (empty($config['url'])) {
			$config['url'] = FULL_BASE_URL;
		}
		
		if(substr($config['url'], -3) != '/r/'){
            $config['url'] = $config['url'] . DS."r".DS;
        }   

        foreach($config['fields'] as $key => $field){
            // Check if given field exists 
            if(!$this->hasField($field)){ 
                unset($config['fields'][$key]);   
                unset($this->data[$this->name][$field]);
                continue;
            }     
        }
 		$this->options = $config;   
   	}   
	
	function shortUrl($data) {
		if (!empty($data)) {  
			$success = false;
			foreach ($this->options['fields'] as $field) {
				$this->create();    
				$data['Shorturl']['url'] = $data['Shorturl'][$field];       
				$data['Shorturl']['key'] = $this->shorten($data['Shorturl'][$field], $this->options);
			   	$data['Shorturl']['short'] = $this->options['url'].base64_encode($data['Shorturl']['key']);
				$data['Shorturl']['model'] = $this->name;  
				
				//need to loop thise things
				if ($data = $this->save($data)) {
					return array('rsp' => array('url' => $data['Shorturl']['short']));
				}
			}
		}    
		return false;
	}
	
	function shorten($long_url, $options) {  
		$short_url = "";  
	  	$k = 0;
		while($k<10){
			$k++;
			$short_url = substr(md5($long_url),0,$options['length']); 
			//Check if Dup exists
			break;  
		} 

		if(!empty($short_url)) {
        	return $short_url;
		}
        return $long_url;
    }
	
}