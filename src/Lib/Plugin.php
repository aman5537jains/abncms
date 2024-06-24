<?php
namespace Aman5537jains\AbnCms\Lib;

use Aman5537jains\AbnCms\Lib\Theme\Theme;

abstract class Plugin {
    use AbnCmsTrait;
    public $options=[];
    abstract public function getName();

    abstract public function install();

    abstract public function unInstall();

    abstract public function onActivate();

    abstract public function onInActivate();

    abstract public function render();

   function setOptions($options){
       $this->options= $options;
       return $this;

    }
    function getOptions(){
        return $this->options;

     }
     function getOption($option){
        return $this->options[$option];

     }
    public function getVersion(){
        return "1.0";
    }
    public function getScreenShot(){
        return "";
    }
    public function getDescription(){
        return "this is a test Plugin";
    }

    public function getAuthor(){
        return "Author";
    }
    public function getSetting($name){
        return AbnCms::getSettings($name);
    }
    public function getActiveTheme():Theme{
        return AbnCms::getActiveTheme();
    }
    public function publishBackendSidebar($sidebar){

    }

}
