<?php
namespace Aman5537jains\AbnCms\Editor;

use Aman5537jains\AbnCms\Models\AbnCmsModule;

class ShortCodeRegistrar{

    public $activeShortCode;
    public $page;
    public $shortCodes = [];
    function __construct()
    {
        $this->shortCodes = $this->getAllShortcodes();
    }
    function getAllShortcodes(){
        return AbnCmsModule::where("type","SHORTCODE")->pluck("class_name","name");

    }
    function map($shortcode,$page){

        return isset($this->shortCodes[$shortcode])?$this->shortCodes[$shortcode]:"";
    }

}
