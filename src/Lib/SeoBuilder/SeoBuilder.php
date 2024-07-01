<?php

namespace Aman5537jains\AbnCms\Lib\SeoBuilder;

use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

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
    function setMeta($key,$value,$rendered=''){
        $this->meta[$key]=["value"=>$value,"rendered"=>$rendered];
    }
    function getMeta(){
        return '';
    }
    function render(){

        return seo(new SEOData(
            title: isset($this->meta["title"])?$this->meta["title"]["value"]:null,
            description: isset($this->meta["description"])?$this->meta["description"]["value"]:null,
            image: isset($this->meta["image"])?$this->meta["image"]["value"]:null,
            author: isset($this->meta["author"])?$this->meta["author"]["value"]:null,
            schema: SchemaCollection::make()->addArticle(),
        ));
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
