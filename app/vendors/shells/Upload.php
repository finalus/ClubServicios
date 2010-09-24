<?php

App::import('Core', 'Xml');
App::import('Core','Validation');
App::import('Core', 'HttpSocket');
App::import('Core', 'File');
App::import('Core', 'Security');
    
App::import('Vendor', 'acs4signer', array('file' => 'acs4signer'.DS.'XMLSigningSerializer.php')); 

class UploadShell extends Shell {
	
	var $adeptNs = "http://ns.adobe.com/adept";
	
	var $dublinCoreNs = 'http://purl.org/dc/elements/1.1/';
	
	var $dublinCorePrefix = 'dc';
	
	var $targetUrl;
	
	var $password;
	
	var $expirationInterval = 15;
	
	var $verbose = false;
	
	var $useDataPath = false;
	
	var $useXmlSource = false;
	
	var $interactive = false;
	
	

	var $titleDefault = '';
	var $descriptionDefault = '';
	var $languageDefault = '';
	var $creatorDefault = '';
	var $publisherDefault = '';
	var $formatDefault = '';
	
	var $title = '';
	var $description = '';
	var $language = '';
	var $creator = '';
	var $publisher = '';
	var $format = '';
	
	
	var $thumbPNG = false;
	
	var $thumbJPG = false;
	
	var $thumbJPEG = false;
	
	var $thumbGIF = false;
	
	
	var $hasResource = false;
	var $hasVoucher = false;
	var $hasResourceItem = false;
	var $hasFileName = false;
	var $hasLocation = false;
	var $hasSrc = false;
	var $hasPermissions = false;
	var $hasThumbnailLocation = false;
	var $hasDataPath = false;
	var $hasDefaultMetadata = false;
	var $hasMetadata = false;
	
	var $currentFileName = '';
	
	var $errors = 0;
	
	var $success = 0;
	
	var $failedFiles = '';
	
	var $version = '1.0';
	function startup() {
		
		$this->_hasFlags();

		if (!empty($this->params['targetUrl'])) {
			$this->targetUrl = $this->params['targetUrl'];
		} elseif (!empty($this->args[0])) {
			$this->targetUrl = $this->params['targetUrl'] = $this->args[0];
		}

		if (!empty($this->params['dirName'])) {
			$this->dirName = $this->params['dirName'];
		} elseif (!empty($this->args[1])) {
			$this->dirName = $this->params['dirName'] = $this->args[1];
		}

	}
	
	private function _hasMetadata($arg, $value) {
		if ($arg == 'title') {
			$this->title = $value;
			return true;
		} elseif ($arg == 'description') {
			$this->description = $value;
			return true;
		} elseif ($arg == 'language') {
			$this->language = $value;
			return true;
		} elseif ($arg == 'creator') {
			$this->creator = $value;
			return true;
		} elseif ($arg == 'publisher') {
			$this->publisher = $value;
			return true;
		} elseif ($arg == 'format') {
			$this->format = $value;
			return true;
		}
		return false;
	}
	
	private function _hasPassword($arg, $value) {
		if ($arg == 'pass' || $arg == 'p') {
			$this->password = $value;
			return true;
		}
		return false;
	}
	
	private function _hasFlags() {
		foreach ($this->params as $name=>$value) {
			$name = strtolower($name);
			if ($name == 'verbose' || $name == 'v') {
				$this->verbose = true;
			} elseif ($name == 'datapath') {
				$this->useDataPath = true;
				$this->useXmlSource = true;
			} elseif ($name == 'png') {
				$this->thumbPNG = true;
			} elseif ($name == 'jpeg') {
				$this->thumbJPEG  = true;
			} elseif ($name == 'jpg') {
				$this->thumbJPG = true;
			} elseif ($name == 'gif') {
				$this->thumbGIF = true;
			} elseif ($name == 'xml') {
				$this->useXmlSource = true;
			} elseif ($name == 'help' || $name == '?') {
				$this->help();
			} elseif ($name == 'version') {
				$this->out(sprintf(__('Version %s', true), $this->version));
			}
			if ($this->_hasMetadata($name, $value)) {
				$this->hasDefaultMetadata = true;
			}
			if ($this->_hasPassword($name, $value)) {
				continue;
			}
		}	
	}
	
	private function _fileOrPathFilter($dirName) {
		$fileList = array();
		if ($fh = opendir($dirName)) {
			while(false != ($fileName = readdir($fh))) {
				$ext = substr(basename($fileName), strrpos(basename($fileName), '.') + 1);
				if ($this->useDataPath || $this->useXmlSource) {
					if ($ext == 'xml') {
						$fileList[] = $fileName;
					}
				} else {
					if ($ext == 'pdf' || $ext == 'epub') {
						$fileList[] = $fileName;
					}
				}
			}
		}
		closedir($fh);
		return $fileList;
	}
	
	private function _createConnection($targetUrl) {
		$this->out(sprintf(__('Creating connection to packaging server: %s', true), $targetUrl));
		$conn = new HttpSocket();
		if (!$conn) {
			$this->out(sprintf(__('Connection to packaging server: %s failed!', true), $targetUrl));
			$this->_stop();
			return false;
		}
		return $conn;
	}
	
	private function _removeExtension($fileName) {
		$ext = substr(basename($fileName), strrpos(basename($fileName), '.'));
		if ($ext == '.pdf' || $ext == '.epub' || $ext == '.xml') {
			if ($this->useDataPath && $ext == '.xml') {
				return substr(basename($fileName), 0, -strlen($ext));
			}
			return substr(basename($fileName), 0, -strlen($ext));
		} else {
			if ($this->useDataPath) {
				$this->out(__('File extension not .xml', true));
			} else {
				$this->out(__('File extension is neither .pdf nor .epub'));
			}
			return null;
		}
	}
	
	private function _getThumbnail($fileName) {
		$dir = dirname($fileName);
		$name = $this->_removeExtension($fileName);
		if ($this->thumbPNG) {
			if (is_file($dir."/".$name.".png")) {
				return $name.".png";
			}
		}
		if ($this->thumbJPEG) {
			if (is_file($dir."/".$name.".jpeg")) {
				return $name.".jpeg";
			}
		}
		if ($this->thumbJPG) {
			if (is_file($dir."/".$name.".jpg")) {
				return $name.".jpg";
			}
		}
		if ($this->thumbGIF) {
			if (is_file($dir."/".$name.".gif")) {
				return $name.".gif";
			}
		}
		return null;
	}
	
	
	private function _makeContent($fileName) {
		$this->out(sprintf(__('Creating package request for: %s', true), basename($fileName)));
		$name = $this->_removeExtension($fileName);

		
		$doc = new Xml(null, array(), $this->adeptNs);
		$packageElement = $doc->createElement('package', null, null);
		$packageElement->addNamespace("",$this->adeptNs);
		$xmlSourceDoc = $this->_useXmlSource($fileName);
		
		if ($this->useXmlSource && $xmlSourceDoc == null) {
			$this->out(__('An Error occured with the XML Source', true));
			$this->errors++;
			$this->failedFiles .= basename($fileName)."\n";
			return null;	
		}
	
		if ($this->hasResource) {
			$packageElement->createElement('resource', 
				$xmlSourceDoc->children[0]->child('resource')->children[0]->value, 
				null);
		}
		if ($this->hasVoucher) {
			$packageElement->createElement('voucher', 
				$xmlSourceDoc->children[0]->child('voucher')->children[0]->value, 
				null);
		}
		if ($this->hasResourceItem) {
			$packageElement->createElement('resourceItem', 
				$xmlSourceDoc->children[0]->child('resourceItem')->children[0]->value, 
				null);
		}
		if ($this->hasLocation) {
			$packageElement->createElement('location', 
				$xmlSourceDoc->children[0]->child('locaton')->children[0]->value, 
				null);
		}
		if ($this->hasSrc) {
			$packageElement->createElement('src', 
				$xmlSourceDoc->children[0]->child('src')->children[0]->value, 
				null);
			
		}
		if ($this->hasMetadata || $this->hasDefaultMetadata) {
			$metadataElement = $packageElement->createElement('metadata', null, null);
			$metadataElement->addNamespace($this->dublinCorePrefix, $this->dublinCoreNs);
			if (!$this->title == "") {
				$metadataElement->createElement('dc:title', $this->title, null);
			} elseif ($this->title == "" && !$this->titleDefault == "") {
				$metadataaElement->createElement('dc:title', $this->titleDefault, null);
			}
			if (!$this->description == "") {
				$metadataElement->createElement('dc:description', $this->description, null);
			} elseif ($this->description == "" && !$this->descriptionDefault == "") {
				$metadataaElement->createElement('dc:description', $this->descriptionDefault, null);
			}
			if (!$this->language == "") {
				$metadataElement->createElement('dc:language', $this->language, null);
			} elseif ($this->language == "" && !$this->languageDefault == "") {
				$metadataaElement->createElement('dc:language', $this->languageDefault, null);
			}
			if (!$this->creator == "") {
				$metadataElement->createElement('dc:creator', $this->creator, null);
			} elseif ($this->creator == "" && !$this->creatorDefault == "") {
				$metadataaElement->createElement('dc:creator', $this->creatorDefault, null);
			}
			if (!$this->publisher == "") {
				$metadataElement->createElement('dc:publisher', $this->publisher, null);
			} elseif ($this->publisher == "" && !$this->publisherDefault == "") {
				$metadataaElement->createElement('dc:publisher', $this->publisherDefault, null);
			}
			if (!$this->format == "") {
				$metadataElement->createElement('dc:format', $this->format, null);
			} elseif ($this->format == "" && !$this->formatDefault == "") {
				$metadataaElement->createElement('dc:format', $this->formatDefault, null);
			}
		}
		if ($this->hasPermissions) {
			$packageElement->append($xmlSourceDoc->children[0]->child('permissions'));
		}
		
		if ($this->hasDataPath && $this->useDataPath) {
			$packageElement->createElement('dataPath', $xmlSourceDoc->children[0]->child('dataPath')->children[0]->value);
		} else {     
			$file = '';
			if ($this->hasFileName) {
				if (is_file($xmlSourceDoc->children[0]->child('fileName')->children[0]->value)) {
					$file = new File($xmlSourceDoc->children[0]->child('fileName')->children[0]->value);
				} elseif (is_file(dirname($fileName)."/".basename($xmlSourceDoc->children[0]->child('fileName')->children[0]->value))) {
					$file = new File(dirname($fileName)."/".basename($xmlSourceDoc->children[0]->child('fileName')->children[0]->value));
				}
			}

			if (is_object($file)) {
				$packageElement->createElement('fileName', $file->name, null);
				$packageElement->createElement('data', base64_encode($file->read()));
			}
		}
		
		if ($this->hasThumbnailLocation) {
			$packageElement->createElement('thumbnailLocation', 
				$xmlSourceDoc->children[0]->child('thumbnailLocation')->children[0]->value);
		}
		if ($this->thumbPNG || $this->thumbJPEG || $this->thumbJPG || $this->thumbGIF) {
			$thumbnailName = $this->_getThumbnail($fileName);
			if ($thumbnailName != null) {
				$file = new File(dirname($fileName)."/".$thumbnailName);
				if ($file) {
					if ($this->verbose) {
						$this->out(sprintf(__('Found thumbnail file', true), $thumbnailName));
					}
					$packageElement->createElement('thumbnailData', base64_encode($file->read()));
				}
			} else {
				$this->out(__('Cannot find thumbnail file', true));
			}
		} elseif ($xmlSourceDoc->children[0]->child('thumbnailName') != null) {
			$file = '';
			if (is_file($xmlSourceDoc->children[0]->child('thumbnailName')->children[0]->value)) {
				$file = new File($xmlSourceDoc->children[0]->child('thumbnailName')->children[0]->value);
			} elseif (is_file(dirname($fileName)."/".basename($xmlSourceDoc->children[0]->child('thumbnailName')->children[0]->value))) {
				$file = new File(dirname($fileName)."/".basename($xmlSourceDoc->children[0]->child('thumbnailName')->children[0]->value));
			}  
			if (is_object($file)) {
				$packageElement->createElement('thumbnailData', base64_encode($file->read()));
			}  
		}  
		
		$packageElement->createElement('expiration', date("c", mktime()+36000));
		
		$packageElement->createElement('nonce', base64_encode(mt_rand(20000000,30000000000)));
	

		$hmacElement = $packageElement->createElement('hmac');
		$hmacElement->createTextNode(base64_encode($this->_createHmac($this->password, $packageElement)));
		
		$requestContent = $packageElement->toString(array('cdata' => false));

		if ($this->verbose) {
			$this->out(__("Package Request:", true));
			$this->out($requestContent);
		}
		return $requestContent;
	}
	
	private function _sendContent($output, $conn) {
		if ($conn !== false) {
			$this->out(__('Sending Package Request', true));
			
			$results = $conn->post($this->targetUrl, $output, array('header' => array('Content-Type' => 'application/vnd.adobe.adept+xml')));
			if ($results) {
				$code = $conn->response['status']['code'];
				$contentType = $conn->response['header']['Content-Type'];
				$this->_parseResponse($code, $contentType, $results);
			}
			return true;
		} else {
			return false;
		}
	}
	
	private function _parseResponse($code, $contentType, $results) {
		$responseXml = new Xml($results);
		if ((substr($results, 1, 5) == 'error') || $code != 200 || $contentType != 'application/vnd.adobe.adept+xml') {
			if ($this->verbose) {
				$this->out(sprintf(__('HTML Response Code: %s', true), $code));
				$this->out(sprintf(__('Response Content Type: %s', true), $contentType));
			}           
			$this->out(__('There was an error with the Package Request', true));
			$this->out($results);             
			$this->_finalProcess($this->currentFileName, $results);                  
			$this->failedFiles .= basename($this->currentFileName)."\n";
			$this->errors++;
		} else {
			$this->success++;         
			if ($this->verbose) {
				$this->out(sprintf(__('HTML Response Code: %s', true), $code));
				$this->out(sprintf(__('Response Content Type: %s', true), $contentType));
				$this->out(sprintf(__('Response: %s', true), $results)); 
			}         
			if (empty($this->title)) {
				$this->out(__('The book has been successfully packaged!'));
			} else {
				$this->out(sprintf(__('The book %s has been packaged successfully packaged!', true), $this->title));
			}     
			$this->_finalProcess($this->currentFileName, $results);
		   
		}
	}    
	
	private function _finalProcess($fileName, $results) {   
		$file = new File($fileName); 
		if ($file->delete()) {
			if ($this->verbose) {
		   		$this->out(sprintf(__('File "%s" was removed', true), basename($fileName)));
		   	} 
		} else {
			$this->out(sprintf(__('Unable to delete file: %s', true), basename($fileName)));
		}    
		$xmlResponse = new Xml($results);
		
		
	}
	
	private function _serializeXml($xmlNode) { 
		#$serializer = new XMLSigningSerializer($this->verbose);
 		#$xml = $serializer->serialize($xmlNode);
#die($xml);
	/*	switch(get_class($xmlNode)) {
			case "XmlElement":
				$e = $xmlNode;
				$ns = $e['namespace'];
				if ($ns == null) 
					$ns = '';
				}
				if ($ns == $this->adeptNs) {
					return;
				}
			break;
			case "XmlTextNode":
			break;
		}*/
		return $xmlNode;
		
	}
	
	private function _readFromFile($fileName) {
		
	}
	
	private function _createHmac($password, $packageElement) {
		$hmacKey = sha1($password);
		return hash_hmac('sha1', $this->_serializeXml($packageElement->toString()), $hmacKey);
	}
	
	private function _useXmlSource($fileName) {
		if (!$this->useXmlSource) {
			return null;
		}
		$name = $this->_removeExtension($fileName);
		$xmlSource = new Xml(dirname($fileName)."/".$name.".xml");
		
		if ($xmlSource) {
			$this->_metadataFromXml($xmlSource);
			
			if ($xmlSource->children[0]->child('resource') != null) {
				$this->hasResource = true;
			} else {
				$this->hasResource = false;
			}
			
			if ($xmlSource->children[0]->child('voucher') != null) {
				$this->hasVoucher = true;
			} else {
				$this->hasVoucher = false;
			}
			
			if ($xmlSource->children[0]->child('resourceItem') != null) {
				$this->hasResourceItem = true;
			} else {
				$this->hasResourceItem = false;
			}
			
			if ($xmlSource->children[0]->child('fileName') != null) {
				$this->hasFileName = true;
			} else {
				$this->hasFileName = false;
			}
			
			if ($xmlSource->children[0]->child('location') != null) {
				$this->hasLocation = true;
			} else {
				$this->hasLocation = false;
			}
			
			if ($xmlSource->children[0]->child('src') != null) {
				$this->hasSrc = true;
			} else {
				$this->hasSrc = false;
			}
			
			if ($xmlSource->children[0]->child('permissions') != null) {
				$this->hasPermissions = true;
			} else {
				$this->hasPermissions = false;
			}
			
			if ($xmlSource->children[0]->child('thumbnailLocation') != null) {
				$this->hasThumbnailLocation = true;
			} else {
				$this->hasThumbnailLocation = false;
			}
			
			if ($xmlSource->children[0]->child('dataPath') != null) {
				$this->hasDataPath = true;
			} else {
				$this->hasDataPath = false;
			}
			
			$this->_runCoocurrenceChecks();
			
			if ($this->useDataPath && !$this->hasDataPath) {
				$this->out(__('When dataPath mode is on, all XML Source documents must include dataPath element.', true));
				return null;
			}
			return $xmlSource;
		} else {
			$this->out(sprintf(__('Could not find XML Source: %s', true), basename($fileName)));
		}	
	}
	
	private function _metadataFromXml(Xml $xmlSource) {
		$metadata = $xmlSource->children[0]->child('metadata');
		
		if ($metadata->child('title') != null) {
			$this->hasMetadata = true;
			$this->title = $metadata->child('title')->children[0]->value;
		}
		if ($metadata->child('description') != null) {
			$this->hasMetadata = true;
			$this->description = $metadata->child('description')->children[0]->value;
		}
		if ($metadata->child('language') != null) {
			$this->hasMetadata = true;
			$this->language = $metadata->child('language')->children[0]->value;
		}
		if ($metadata->child('creator') != null) {
			$this->hasMetadata = true;
			$this->creator = $metadata->child('creator')->children[0]->value;
		}
		if ($metadata->child('publisher') != null) {
			$this->hasMetadata = true;
			$this->publisher = $metadata->child('publisher')->children[0]->value;
		}
		if ($metadata->child('format') != null) {
			$this->hasMetadata = true;
			$this->format = $metadata->child('format')->children[0]->value;
		}
	}
	
	private function _runCoocurrenceChecks() {
		if ($this->hasResourceItem && !$this->hasResource) {
			$this->out(__('XML Validation Error', true));
			$this->out(__('If <resourceItem> is used, <resource> must be used as well.', true));
			$this->out(__('<resourceItem> will be ignored', true));
			$this->hasResourceItem = false;
		}
		if ($this->hasFileName && ($this->hasLocation || $this->hasSrc)) {
			$this->out(__('XML Validation Error', true));
			$this->out(__('If <fileName> is used, <location> and <src> must not be used', true));
			$this->out(__('<location> and <src> will be ignored', true));
			$this->hasLoction = false;
			$this->hasSrc = false;
		}
		if ($this->hasSrc && !$this->hasLocation) {
			$this->out(__('XML Validation Error', true));
			$this->out(__('If <src> is used, <location> must be used as well.', true));
			$this->out(__('<src> will be ignored', true));
			$this->hasSrc = false;
		}
		if ($this->hasLocation && !$this->hasSrc) {
			$this->out(__('XML Validation Error', true));
			$this->out(__('If <location> is used, <src> must be used as well.', true));
			$this->out(__('<loction> will be ignored', true));
			$this->hasLocation = false;
		}
	}
	
	function main() {
		$this->out(__('Content Server XML Import', true));
		$this->hr();
		$this->out(__('[I]mport package file(s)', true));
		$this->out(__('[H]elp', true));
		$this->out(__('[Q]uit', true));

		$choice = strtolower($this->in(__('What would you like to do?', true), array('I', 'H', 'Q')));
		switch ($choice) {
			case 'i':
				$this->import();
			break;
			case 'h':
				$this->help();
			break;
			case 'q':
				exit(0);
			break;
			default:
				$this->out(__('You have made an invalid selection. Please choose a command to execute by entering E, I, H, or Q.', true));
		}
		$this->hr();
		$this->main();
	}
	
	function import() {
		if (empty($this->args)) {
			$this->_interactive();
		}
		$this->_import();
	}
	
	private function _import() {
		
		if (!Validation::url($this->targetUrl)) {
			$this->out(__('Target URL Invalid', true));
			$this->_stop();
		}
		
		if (isset($this->dirName)) {
			$dirName = realPath($this->dirName);
			if (is_file($dirName)) {
				if ($this->useDataPath) {
					$this->out(__('Second argument is a file.', true));
					$this->out(__('DataPath mode requires that to be a directory.', true));
					$this->_stop();
				}
				if ($this->verbose) {
					$this->out(__('Target file found.', true));
				}
				
				$this->currentFileName = $dirName;
				$output = $this->_makeContent($dirName);
				if ($output != null) {
					if (!$this->_sendContent($output, $this->_createConnection($this->targetUrl))) {
						$this->errors++;
						$this->failedFiles .= basename($dirName)."\n";
					}
				} else {
					$this->errors++;
				}
				
			} elseif (is_dir($dirName)) {
				if ($this->verbose) {
					$this->out(sprintf(__('Found Target Directory: %s', true), $dirName));
				}

				$fileList = $this->_fileOrPathFilter($dirName);
				
				if (empty($fileList)) {
					if ($this->useDataPath) {
						$this->out('No .xml files found in directory.', true);
					} else {
						$this->out('No .pdf or .epub files found in directory.', true);
					}
					$this->_stop();
				} else {
					if (substr($dirName, -1) != '/'){
						$dirName = $dirName.'/';
					}
					for($i=0;$i<count($fileList);$i++) {
						$this->currentFileName = $dirName.$fileList[$i];
						$output = $this->_makeContent($dirName.$fileList[$i]);
						if ($output != null) {
							if (!$this->_sendContent($output, $this->_createConnection($this->targetUrl))) {
								continue;
							}
						} else {
							$this->errors++;
						}
					}
				}
			} else {
				$this->out(__('Second arg must be a file or directory to package', true));
				$this->_stop();
			}
		} else {
			$this->out(__('Please supply a valid Directory', true));
			$this->_stop();
		}
		
		$this->out(__('Finished!'), true);
		$this->out(sprintf(__('Successful packages created: %d', true), $this->success));
		$this->out(sprintf(__('Unsuccessful packages attempts: %d', true), $this->errors));
		if (count($this->errors) > 0) {
			$this->out(sprintf(__("Here are the files that failed to package: \n %s", true), $this->failedFiles));
		}
		
	}
	
	
	private function _interactive() {
		$this->hr();
		$this->out("Package Import", true);
		$this->hr();
		$this->interactive = true;
	}
	
	function help() {
		
	}
	
}