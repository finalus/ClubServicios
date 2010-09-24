<?php

class ShorturlBehavior extends ModelBehavior {
    
	var $options = array(
		'url' => '',
		'length' => 5,
		'fields' => array()
	);

    public function setup(&$model,$config = array()) {  
		
		$config = array_merge($this->options, $config);  
		
		if (empty($config['url'])) {
			if (defined(FULL_BASE_URL)) {
				$config['url'] = FULL_BASE_URL;
			}
		}
		
		if(substr($config['url'], -3) != '/r/'){
            $config['url'] = $config['url'] . DS."r".DS;
        }

        foreach($config['fields'] as $key => $field){
            // Check if given field exists  
            if(!$model->hasField($field)){  
				
                unset($config['fields'][$key]);   
                unset($model->data[$model->name][$field]);

                continue;
            }     
        }
 		$this->options = $config; 
    }
    

	public function beforeSave(&$model, $created) {  
		$this->Shorturl = ClassRegistry::init('Shorturl.Shorturl');   
		
		#$existing = $this->Shorturl->find('first', array('conditions' => array()))

		foreach($this->options['fields'] as $key => $field) { 
			$this->Shorturl->create();
			$this->Shorturl->data['Shorturl']['url'] = $model->data[$model->alias][$field];       
			$this->Shorturl->data['Shorturl']['key'] = $this->Shorturl->shorten($model->data[$model->alias][$field], $this->options);
			$this->Shorturl->data['Shorturl']['short'] = $this->options['url'].base64_encode($this->Shorturl->data['Shorturl']['key']);
			$this->Shorturl->data['Shorturl']['model'] = $model->name;   		                                         
			$model->data[$model->alias][$field] = $this->Shorturl->data['Shorturl']['short'];
		    $this->Shorturl->save();
		}         
		return true;
	} 
	                      
}
	
?>
