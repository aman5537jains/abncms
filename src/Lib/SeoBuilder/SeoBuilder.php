<?php

namespace Aman5537jains\AbnCms\Lib\SeoBuilder;

class SeoBuilder{
    public $meta =[];
    function setTitle($title){
        $this->meta["title"]=["value"=>$title,"rendered"=>"<title>$title</title>"];
    }
    function setDescription($title){
        $this->meta["description"]=["value"=>$title,"rendered"=>"<meta type='description' value='$title' />"];
    }
    function setIcon(){

        // $this->meta["icon"]=["value"=>"icon","rendered"=>" <link rel="apple-touch-icon" sizes="180x180" href="{{$theme->getSetting('FAV_ICON')}}">
        // <link rel="icon" type="image/png" sizes="32x32" href="{{$theme->getSetting('FAV_ICON')}}">
        // <link rel="icon" type="image/png" sizes="16x16" href="{{$theme->getSetting('FAV_ICON')}}">"];

    }
    function getTitle(){
        return '';
    }
    function getDescription(){

    }
    function setMeta(){

    }
    function getMeta(){
        return '';
    }
    function render(){
        $seos = '';

        foreach($this->meta as $k=>$value){
            $seos.=   $value['rendered'];

        }
        return $seos;
    }
    function __toString()
    {
        return $this->render();
    }
}
