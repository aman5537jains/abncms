<?php

namespace Aman5537jains\AbnCms\Lib\Theme;

class StylesheetLoader extends AssetLoader{

    function render(){
        $str = $this->getAttributesString();
        if($this->inline){
            return "<style  $str> $this->src </style>";
        }
        else if($this->raw){

            return $this->src;
        }
        else{
           return "<link rel='stylesheet' href='$this->src' $str > ";
        }
    }


}
