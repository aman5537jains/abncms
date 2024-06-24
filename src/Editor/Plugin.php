<?php
namespace Aman5537jains\AbnCms\Editor;


class Plugin{

    function customScript(){
        return false;
    }
    function defaults(){
        return [];
    }
    function getFullClass(){
        return get_class($this);
    }
    function getClassName(){
        $class = $this->getFullClass();
        $splitted =  explode("\\",$class);
        return $splitted[count($splitted)-1];
    }

    function scriptFunctionName(){
        $class = $this->getFullClass();

        return  strtolower(str_replace("\\","_",$class));
    }

    function identifier(){
        $class = $this->getFullClass();
        return  str_replace("\\","-",$class);
    }

    function label(){
        return $this->getClassName();
    }
    function content(){
        return "<div class='dummy-cls'>Dummy content</div>";
    }
    function scripts(){
        return "alert('loaded')";
    }
    function styles(){
        return ".dummy-cls{color:red}";
    }
    function media(){
        return "svg";
    }
    function category(){
        return "Others";
    }

}
