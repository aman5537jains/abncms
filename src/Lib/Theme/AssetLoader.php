<?php

namespace Aman5537jains\AbnCms\Lib\Theme;

class AssetLoader{
    protected $inline =false;
    protected $raw =false;
    protected $assetType = "script";
    protected $src ="";
    protected $attrs =[];

    public function __construct($srcOrScript)
    {
        $this->src = $srcOrScript;
    }
    function getSrc(){
        return $this->src;

    }
    function inline($bool){
        $this->inline = $bool;
        return $this;
    }
    function isInline(){
       return $this->inline  ;
    }
    function isRaw(){
        return $this->raw  ;
     }

    function raw($bool){
        $this->raw = $bool;
        return $this;
    }
    function setAttributes($attr){
        $this->attrs = $attr;
    }
    function getAttributes(){
        return $this->attrs ;
    }
    function getAttributesString(){
        $attr = $this->getAttributes();
        $str='';
        foreach($attr as $k=>$v){
            $str.="$k=$v";
        }
        return $str;
    }

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
