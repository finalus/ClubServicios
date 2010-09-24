<?php
class AclAco extends AclAppModel {

    var $name = 'AclAco';
    var $useTable = 'acos';

    var $actsAs = array('Tree');

    function generateJson($acos) {
        $list = array();
        if (!empty($acos)) {
            foreach ($acos as $key=>$value) {
                if (substr($value, 0, 1) != '_') {
                    $list[] = $this->__getNodeJson(array('id' => $key, 'alias' => $value), 1); 
                }   
            }   
        }   
        return $list;
    }   



    protected function __getNodeJson($node, $level=0) {
        $item = array();

        if ($level !=0) {
            $item['data'] = Inflector::humanize($node['alias']);
            $item['title'] = Inflector::humanize($node['alias']);
            $item['attr'] = array('id' => 'node_'.$node['id']);
            if ($this->childCount($node['id'])>0) {
                $item['state'] = "open";
                $children = $this->children($node['id']);
            }   
        }   

        if (empty($children)) {
            return $item;
        }   

        if ($children) {
            $items['children'] = array();
            foreach ($children as $child) {
                if ($level !=0) {
                    $item['children'][] = $this->__getNodeJson($child['AclAco'], $level+1);
                } else {
                    $item = $this->__getNodeJson($child['AclAco'], $level+1);
                }   
            }   
        }

        return $item;

    }


}
?>
