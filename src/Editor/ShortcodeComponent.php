<?php
namespace Aman5537jains\AbnCms\Editor;

use App\Models\CmsManagement;

class ShortcodeComponent{

 public $shortcode;
 public $page;
    function __construct($shortcode,$page){
        $this->shortcode= $shortcode;
        $this->page     = $page;
    }

    function render(){
        if(isset($this->shortcode["attributes"]["component"])){
             return  (new Editor)->getPage($this->shortcode["attributes"]["component"])->render();
        }
    }

}
