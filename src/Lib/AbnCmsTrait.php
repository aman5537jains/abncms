<?php
namespace Aman5537jains\AbnCms\Lib;

use Aman5537jains\AbnCms\Lib\Sidebar\AbnSidebar;

trait AbnCmsTrait{



    function getConfig($name="",$default=null){
        // dd(config_path("abncms.php"));
        if(file_exists(base_path("bootstrap/cache/abncms.php"))){
            file_put_contents(base_path("bootstrap/cache/abncms.php"),var_dump([

            ]));
        }
       return  include config_path("abncms.php");

        // return config("abncms.$name");
    }

    function updateConfig($config){
        return file_put_contents(config_path("abncms.php"),"<?php \n return ".var_export($config,true).";");
    }

    public function addShortcode($name,$class){
        $this->setConfigSettings("shortcodes",$name,$class);
    }
    public function removeShortcode($name,$class){
        $this->setConfigSettings("shortcodes",$name,$class,true);
    }
    public function addTheme($name,$class){
        $this->setConfigSettings("themes",$name,$class);
    }
    public function addPermission($name){
        $this->setConfigSettings("permissions",$name,get_called_class());
    }
    public function removePermission($name){
        $this->setConfigSettings("permissions",$name,get_called_class(),true);
    }
    public function addBackendSidebar():AbnSidebar{
       return new AbnSidebar();
    }
    public function removeBackendSidebar($name){
        $this->setConfigSettings("backend_sidebar",$name,get_called_class(),true);
    }


    public function addPlugin($name,$class){
        $this->setConfigSettings("plugins",$name,$class);
    }

    public function setConfigSettings($name,$settingName,$class,$remove=false){
        $config =   $this->getConfig();
        if($remove){
            unset($config[$name][$settingName]);
        }
        else{
            if(isset($config[$name][$settingName])){
                throw new \Exception("$settingName name already exist");
             }
             else{
                  $config[$name][$settingName] =$class;
             }
        }

        $this->updateConfig($config);
    }
    public function updateConfigSettings($name,$settingName,$class){
        $config =   $this->getConfig();

            if(!isset($config[$name][$settingName])){
                throw new \Exception("$settingName name already exist");
             }
             else{
                  $config[$name][$settingName] =$class;
             }

        $this->updateConfig($config);
    }

}
