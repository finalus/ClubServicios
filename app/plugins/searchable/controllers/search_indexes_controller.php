<?php
class SearchIndexesController extends SearchableAppController {

  var $name = 'SearchIndexes';
  var $paginate = array('SearchIndex' => array('limit' => 10));
  var $helpers = array('Searchable.Searchable');
  var $components = array('RequestHandler');

  function index() {
    // Redirect with search data in the URL in pretty format
    if (!empty($this->data)) {
      $redirect = array();
      if (isset($this->data['SearchIndex']['term'])
      && !empty($this->data['SearchIndex']['term'])) {
        $redirect['term'] = urlencode(urlencode($this->data['SearchIndex']['term']));
      } else {
        $redirect['term'] = 'null';
      }
      if (isset($this->data['SearchIndex']['type'])
      && !empty($this->data['SearchIndex']['type'])) {
        $redirect['type'] = $this->data['SearchIndex']['type'];
      } else {
        $redirect['type'] = 'All';
      }
      $this->redirect($redirect);
    }

    // Add default scope condition
    $this->paginate['SearchIndex']['conditions'] = array('SearchIndex.active' => 1);

    // Add published condition NULL or < NOW()
    $this->paginate['SearchIndex']['conditions']['OR'] = array(
      array('SearchIndex.published' => null),
      array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
    );

    // Add type condition if not All
    if (isset($this->params['type'])
    && $this->params['type'] != 'All') {
      $this->data['SearchIndex']['type'] = $this->params['type'];
      $this->paginate['SearchIndex']['conditions']['model'] = $this->params['type'];
    }

    // Add term condition, and sorting
    if (isset($this->params['term'])
    && $this->params['term'] != 'null') {
      $this->data['SearchIndex']['term'] = $this->params['term'];
      $term = $this->params['term'];
      App::import('Core', 'Sanitize');
      $term = Sanitize::escape($term);
      $this->paginate['SearchIndex']['conditions'][] = "MATCH(data) AGAINST('$term' IN BOOLEAN MODE)";
      $this->paginate['SearchIndex']['fields'] = "*, MATCH(data) AGAINST('$term' IN BOOLEAN MODE) AS score";
      $this->paginate['SearchIndex']['order'] = "score DESC";
    }

    $results = $this->paginate();

    // Get types for select drop down
    $types = $this->SearchIndex->getTypes();
    $this->set(compact('results', 'types'));
    $this->pageTitle = 'Search';
	}


	function search() {
		$this->layout = 'ajax';
		if (!$this->RequestHandler->isAjax()) {
			$this->redirect(array('plugin'=>false, 'controller' => 'dashboards', 'action' => 'index'));
		} else {
			$this->disableCache();
			if (!empty($this->params['url']['query'])) {

			    // Add default scope condition
			    $this->paginate['SearchIndex']['conditions'] = array('SearchIndex.active' => 1);

			    // Add published condition NULL or < NOW()
			    $this->paginate['SearchIndex']['conditions']['OR'] = array(
			      array('SearchIndex.published' => null),
			      array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
			    );

			    // Add term condition, and sorting
			  	$this->data['SearchIndex']['term'] = $this->params['url']['query'];
			    $term = $this->params['url']['query'];
			    App::import('Core', 'Sanitize');
			   	$term = Sanitize::escape($term);
			   	$this->paginate['SearchIndex']['conditions'][] = "MATCH(data) AGAINST('$term' IN BOOLEAN MODE)";
			   	$this->paginate['SearchIndex']['fields'] = "*, MATCH(data) AGAINST('$term' IN BOOLEAN MODE) AS score";
			   	$this->paginate['SearchIndex']['order'] = "score DESC";
			
			    $results = $this->paginate();

			    $this->set(compact('results'));
			}
		}
	}
}

?>