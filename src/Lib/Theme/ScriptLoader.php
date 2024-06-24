<?php

namespace Aman5537jains\AbnCms\Lib\Theme;

class ScriptLoader extends AssetLoader{

    function render(){
        $str = $this->getAttributesString();
        if($this->inline){
            return "<script type='text/javascript' $str> $this->src </script>";
        }
        else if($this->raw){
            return $this->src;
        }
        else{

            return "<script type='text/javascript' src='$this->src' $str >  </script>";
        }
    }
}
