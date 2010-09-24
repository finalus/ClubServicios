<?php

class MenuHelper extends Helper {
	
	var $_title = null;
	var $_menu = array();

    function __construct() {
        parent::__construct();
    }

	function addMenuArray($menu) {
		$this->_menu = $menu;
	}

	function getMenuArray() {
		return $this->_compileMenuArray($this->_buildMenuArray());
	}
	
	function addMenu($name, $options = null, $parent=null) {
		if (!is_null($options) && is_array($options)) {
			if (!is_null($parent) && !empty($this->_menu[$parent])) {
				$this->_menu[$parent]['children'][Inflector::underscore($name)] = $options;
			} else {
				$this->_menu[Inflector::underscore($name)] = $options;
			}
		}
	}
	
	function addTitle($name=null) {
		$this->_title = $name;
	}
	
	function getTitle() {
		return $this->_title;
	}
	
	private function _getHelperValue($child) {
		return $child['title'];
	}
	
	private function _sortMenu($a, $b) {
		return $a['sort_order']<$b['sort_order'] ? -1 : ($a['sort_order']>$b['sort_order'] ? 1 : 0);
	}
	
	private function _compileMenuArray($array) {
		$menu = $this->_buildMenuArray();
		$active = $this->_getActiveElement($menu);
		if (!empty($menu[$active])) {
			$menu[$active]['active'] = true;
		}
		return $menu;
	}
	
	private function _buildMenuArray($parent = null, $path = '/admin', $level =0, $parentVal = null) {
		if (is_null($parent)) {
			$parent = $this->_menu;
		}
		$parentArr = array();
		$sortOrder = 0;
		foreach ($parent as $childName => $child) {
			//Check if Has Access
			if ($level == 0) {
				$parentVal = $childName;
			}

			$menuArr = array();
			$menuArr['label'] = $this->_getHelperValue($child);
			$menuArr['sort_order'] = !empty($child['sort_order']) ? (int)$child['sort_order']:$sortOrder;
			if (!empty($child['url'])) {
				if (is_array($child['url']) && empty($child['url']['plugin'])) {
					$child['url']['plugin'] = false;
				}
				$menuArr['url'] = $this->url($child['url']);
			} else {
				$menuArr['url'] = '#';
				$menuArr['click'] = 'return false';
			}

			$menuArr['active'] = (($this->here == $path.DS.$childName) || $this->params['controller'] == $childName) 
				|| (strpos($this->here, $path.DS.$childName.'/')===0)
				|| (strpos($this->here, $childName)>0);
			
			$menuArr['level'] = $level;
			$menuArr['parent'] = $parentVal;
			if (!empty($child['children']) && is_array($child['children'])) {
				$menuArr['children'] = $this->_buildMenuArray($child['children'], $path.DS.$childName, $level+1, $parentVal);
			}
			$parentArr[$childName] = $menuArr;
			$sortOrder++;
		}
		
		
		uasort($parentArr, array($this, '_sortMenu'));

		while(list($key, $value) = each($parentArr)) {
			$last = $key;
			if ($value['active']) {
				$activeKey = $key;
				$active = $value['parent'];
			}
		}
		
		if (isset($active)) {
			$parentArr[$activeKey]['active'] = $active;
		}

		if (isset($last)) {
			$parentArr[$last]['last'] = true;
		}
		
		return $parentArr;
	}
	
	private function _getActiveElement($array) {
		$active = null;
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				if (!empty($value['children']) && is_array($value['children'])) {
					$active = $this->_getActiveElement($value['children']);
				} else {
					if (!empty($value['active']) && $value['active']) {
						$active = $value['parent'];
					}
				}
			}
		}
		return $active;
	}
	
}


