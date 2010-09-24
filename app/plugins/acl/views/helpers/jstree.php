<?php

App::Import('Html');

class JstreeHelper extends AppHelper {

    var $html = null;

    function __construct() {
        parent::__construct();
        $this->html = new HtmlHelper();
    }

    function getScript($id = null) {
        $code = '$(function() {';
            if (!empty($id)) {
            $code .= '$(\'#'.$id.'\').jstree({
                    "plugins" : ["themes", "json_data", "ui", "checkbox"],
                    "json_data" : {
                        "ajax" : {
                            "url" : "'.$this->html->url(array('controller' => 'acl_permissions', 'action' => 'aco.json', 'plugin' => 'acl', 'admin' => true)).'",
                        }
                    },
                    "themes": {
                        "url": "/acl/css/tree/style.css",
                    },
                    "ui": {
                        "initially_select": ["node_10","node_20"], 
                    },
                });
                $(".jstree li").live("click", function(e) {
                    var action = $(this).hasClass(\'jstree-checked\', \'jstree-undetermined\')?\'grant\':\'deny\';
                    var aroId = $(\'#GroupId\').val();
                    var acoId = $(this).attr(\'id\').replace("node_","");
                    var loadUrl = \'/admin/acl/acl_permissions/toggle/\';
                        loadUrl += action+\'/\';
                        loadUrl += acoId+\'/\'+aroId+\'/\';
                    $.ajax({
                        url: loadUrl,
                        success: function(data) {
                        }
                    })
                });

            ';

                
            } else {
                $code .= "alert('".__('Jstree Helper needs to have and ID to work', true)."');";
            }
        $code .= '});';
        $return = $this->html->script('/js/jquery/jquery.jstree.js');
        $return .= $this->html->scriptBlock($code);
        return $return;


    }

}
