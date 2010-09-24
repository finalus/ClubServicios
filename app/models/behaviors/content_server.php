<?php

App::import('Core', 'Xml');
App::import('Core', 'File'); 

class ContentServerBehavior extends ModelBehavior{
   
	var $adeptNs = "http://ns.adobe.com/adept";
	
	var $dublinCoreNs = 'http://purl.org/dc/elements/1.1/';
	
	var $dublinCorePrefix = 'ds';
	
	var $options = array(
		'directory'         => 'files/xml', 
		'content' 			=> 'files/content',
		'thumbnail' 		=> 'img/thumb',
		'query' 			=> array(),
	);
	
	var $xmlSource = null;

    /**
	 * Array of errors
	 */
	var $errors = array();

    var $__fields;

    function setup(&$model, $config = array()) {
		$config_temp = array();
		
	    $config = array_merge(array($model->name => $this->options), $config);
	
		foreach($config as $field => $options){
			if ($model->name !== $field) {
                unset($config[$field]);
                continue;
            }

			if(substr($options['directory'], -1) != '/'){
                $options['directory'] = $options['directory'] . DS;
            }
			if(substr($options['content'], -1) != '/'){
                $options['content'] = $options['content'] . DS;
            }
			if(substr($options['thumbnail'], -1) != '/'){
                $options['thumbnail'] = $options['thumbnail'] . DS;
            }

            $config_temp[$field] = $options;
        }
		$this->options = $config_temp[$model->name];
    }

	function afterSave(&$model) { 
		if (!empty($model->data[$model->name])) {
			if (empty($model->data[$model->name][$model->primaryKey])) {
				$model->read();
			}
			
			$this->options['query']['conditions'] = array($model->alias.".".$model->primaryKey ." = ". $model->data[$model->name][$model->primaryKey]);	

			$data = $model->find('first', $this->options['query']);

			$array = array();   
			print_r($data);
			die();
	  
			if (!empty($data)) {    
				if (!empty($data['Publicacion'])) {
					$i=0;
					$this->_xmlSource = new Xml(null, array(), $this->adeptNs);
					$packageElement = $this->_xmlSource->createElement('package');
					$packageElement->addNamespace("",$this->adeptNs);
				
					$metadataElement = $packageElement->createElement('metadata');
					$metadataElement->addNameSpace($this->dublinCorePrefix, $this->dublinCoreNs);

					$permissionElement = $packageElement->createElement('permissions');
					$permissionElement->createElement('display');
					$permissionElement->createElement('excerpt');
					$permissionElement->createElement('print');

					
					if ($data['Publicacion']['publicacion'] != null) {
						$this->_addMetadataXml('title', $data['Publicacion']['publicacion']);
					}
					if ($data['Publicacion']['descripcion'] != null) {
						$this->_addMetadataXml('description', $data['Publicacion']['descripcion']);
					}
					if ($data['Publicacion']['autor'] != null) {
						$this->_addMetadataXml('creator', $data['Publicacion']['autor']);
					}
					if ($data['Publicacion']['editorial'] != null) {
						$this->_addMetadataXml('publisher', $data['Publicacion']['editorial']);
					}
					if ($data['Publicacion']['image'] != null) {                                                
					   	$this->_addPackageXml('thumbnailName', WWW_ROOT. $this->options['thumbnail'].$data['Publicacion']['image']); 
						//$this->_addPackageXml('thumbnailLocation', WWW_ROOT. $this->options['thumbnail'].$data['Publicacion']['image']);
					}
					if (!empty($data['Publicacion']['Idioma'])) {
						$this->_addMetadataXml('language', $data['Publicacion']['Idioma']['code']);
					} elseif (!empty($data['Idioma'])) {
						$this->_addMetadataXml('language', $data['Idioma']['code']);
					} 
					
					print_r($data);
					die();   

					if (!empty($data['Contenido'])) {
						if (is_array($data['Contenido']) && empty($data['Contenido']['id'])) {  
							foreach ($data['Contenido'] as $content) {
								// Adding the Format
								$description = '';
								if (!empty($content['Formato']['tipo'])) {
									$this->_addMetadataXml('format', $content['Formato']['tipo']);
								}
								if (!empty($content['contenido'])) {
									$description = $content['contenido'];
								}
								if (!empty($content['descripcion'])) {
									$description .=  " ".$content['descripcion'];
								}
								if (!empty($data['Publicacion']['descripcion'])) {
									$description .= " ".$data['Publicacion']['descripcion'];
								}
								if (!empty($description)) {
									$this->_addMetadataXml('description', trim($description));
								}
								if ($content['archivo']) {
									$this->_addPackageXml('fileName', WWW_ROOT. $this->options['content'].$content['archivo']);
								}   
			
								if (!empty($content['Producto'])) {
									if (is_array($content['Producto'])) {
										foreach ($content['Producto'] as $pkey => $product) {
											$this->_addPackageXml('identifier', $product['id']);
											if (!empty($product['resource'])) {
												$this->_addPackageXml('resource', $product['resource']);
											}
											#$this->_addPermissionXml('display', 'test3', array('test' => 'test'));   
					   
											$this->_createXmlFile($this->_xmlSource, $this->_removeExtension($content['archivo'])."-".$product['id']);
										}
									}
								} elseif (!empty($data['Producto'])) {
									if (is_array($data['Producto'])) {
			
									}
								}
							}
						} else {
							// If Editing Contents
							$description = '';
							if (!empty($data['Formato']['tipo'])) {
								$this->_addMetadataXml('format', $data['Formato']['tipo']);
							}
							if (!empty($data['Contenido']['contenido'])) {
								$description = $data['Contenido']['contenido'];
							}
							if (!empty($data['Contenido']['descripcion'])) {
								$description .=  " ".$data['Contenido']['descripcion'];
							}
							if (!empty($data['Publicacion']['descripcion'])) {
								$description .= " ".$data['Publicacion']['descripcion'];
							}
							if (!empty($description)) {
								$this->_addMetadataXml('description', trim($description));
							}
							if ($data['Contenido']['archivo']) {
								$this->_addPackageXml('fileName', WWW_ROOT. $this->options['content'].$data['Contenido']['archivo']);
							}  
			  
							// Check for Products
							if (!empty($data['Producto'])) {
								// If Editing Contents
								if (is_array($data['Producto']) && empty($data['Producto']['id'])) {
									foreach ($data['Producto'] as $product) {
										$this->_addPackageXml('identifier', $product['id']);
										if (!empty($product['resource'])) {
											$this->_addPackageXml('resource', $product['resource']);
										}
										$this->_addPermissionXml('display', 'test2', 'val');
										$this->_createXmlFile($this->_xmlSource, $this->_removeExtension($data['Contenido']['archivo'])."-".$product['id']);
									}
								} else {
									// If Editing Product
									$this->_addPackageXml('identifier', $data['Producto']['id']);
									if (!empty($product['resource'])) {
										$this->_addPackageXml('resource', $data['Producto']['resource']);
									}
									#$this->_addPermissionXml('display', 'test1', array('test' => 'test'));
									$this->_createXmlFile($this->_xmlSource, $this->_removeExtension($data['Contenido']['archivo'])."-".$data['Producto']['id']);
								}
							}
						}
					}
				}
			}
		}
	}
	
	private function _addPackageXml($key, $value = null) {
		if (!empty($this->_xmlSource)) {
			$package = $this->_xmlSource->child('package');
			if ($package->hasChildren() && count($package->children($key)) == 1) {
				$package->child($key)->children[0]->value = $value;
			} else {
				$package->createElement($key, $value);
			}
		}
	}
	
	private function _addMetadataXml($key, $value = null) {
		if (!empty($this->_xmlSource)) {
			$metadata = $this->_xmlSource->children[0]->child('metadata');
			if ($metadata->hasChildren() && count($metadata->children($key)) == 1) {
				$metadata->child($key)->children[0]->value = $value;
			} else {
				$metadata->createElement($key, $value);
			}
		}
	}	
	
	private function _addPermissionXml($type, $key, $value = null) {
		if (!empty($this->_xmlSource)) {
			switch($type) {
				case "display":
					$permission = $this->_xmlSource->children[0]->
						child('permissions')->child('display');
				break;
				case "excerpt":
					$permission = $this->_xmlSource->children[0]->
						child('permissions')->child('excerpt');
				break;
				case "print":
					$permission = $this->_xmlSource->children[0]->
						child('permissions')->child('print');
				break;
			}

			if (is_array($value)) {
				$value = new XmlElement($key, $value);
			}
			if ($permission->hasChildren() && count($permission->children($key)) == 1) {
				
				$permission->child($key)->children[0]->value = $value;
			} else {
				$permission->createElement($key, $value);
			}
		}
	}
	
	private function _removeExtension($fileName) {
		$ext = substr(basename($fileName), strrpos(basename($fileName), '.'));
		return substr(basename($fileName), 0, -strlen($ext));
	}

	private function _createXmlFile($xmlSource, $fileName) {
		
		$file = new File(WWW_ROOT . $this->options['directory'].hash_hmac('sha1', $fileName, 'test').'.xml', true);
		$file->write($xmlSource->toString());
		$file->close();
		
	}


}

?>
