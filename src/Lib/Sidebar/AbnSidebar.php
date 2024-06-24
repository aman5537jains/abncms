<?php
namespace Aman5537jains\AbnCms\Lib\Sidebar;

use Aman5537jains\AbnCms\Lib\AbnCmsTrait;

class AbnSidebar {
    use AbnCmsTrait;
    public $sidebars = [];
    public $name = "";


    function __construct($type="backend_sidebar")
    {
        $this->sidebars =  config("abncms.$type");
    }

    function push($name,$clas){

        $this->sidebars[$name] = $clas;
        $this->save();
        return $this;
    }
    function after($afterName,$name,$clas){
        $newSidebar = [];
        foreach($this->sidebars as $sb=>$val){
            $newSidebar[$sb] =  $val;
            if($sb==$afterName){
                $newSidebar[$name] = $clas;
            }
        }
        $this->sidebars = $newSidebar;
        $this->save();
        return $this;
    }
    function before($beforeName,$name,$clas){
        $newSidebar = [];
        foreach($this->sidebars as $sb=>$val){

            if($sb==$beforeName){
                $newSidebar[$name] = $clas;
            }
            $newSidebar[$sb] = $val;
        }
        $this->sidebars = $newSidebar;
        $this->save();
        return $this;
    }

    function save(){

       $config =  $this->getConfig();
       $config['backend_sidebar'] = $this->sidebars;
       $this->updateConfig($config);

    }

}
