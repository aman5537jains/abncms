<?php
namespace Aman5537jains\AbnCms\Editor;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Models\CmsManagement  ;

use Illuminate\Contracts\View\View;

class Editor{

    public $page ;
    public function __consturuct(){
        $this->page = (object)["description"=>"Page not found"];
    }



    public function getPage($slug){
            $this->page =  CmsManagement::whereSlug($slug)->first();
           
            if(empty($this->page)){
                $this->page = (object)["description"=>"Page not found","title"=>"Not found"];
            }
            return $this;
    }
    public function setPage($page){
        $this->page =  $page;
        return $this;
}

    public function hasShortCodes($description){
        $shortCodes  = ShortCodeParser::findMatches("shortcode",$description);
        return $shortCodes;
    }

    public function parseShortCode($description){
        $shortCodes = $this->hasShortCodes($description);

        if(count($shortCodes)>0){

            $ShortCodeRegistrar =   new ShortCodeRegistrar();

            foreach($shortCodes as $k=>$shortCode){

                $maps= $ShortCodeRegistrar->shortCodes;
                $name = $shortCode['attributes']['name'];

                if(isset($maps[$name])){

                    if(is_string($maps[$name])){

                        $class  = new $maps[$name]($shortCode,$description);
                        $html = $class->render();
                    }
                    else{
                        $html = ($maps[$name]($shortCode,$description));
                        if( $html instanceof View){
                            $html = $html->render();
                        }
                   }
                    $shortCodes[$k]['rendered_html'] =$html;
                    $description =   str_replace($shortCode['shortcode'],$shortCodes[$k]['rendered_html'],$description);
                    if($this->hasShortCodes($description)>0){
                        $description =  $this->parseShortCode($description);
                    }
                }
            }
        }
        return $description;
    }

    public function render(){
        if(isset($this->page->title) && !empty($this->page->title))
            AbnCms::getActiveTheme()->getSeo()->setTitle($this->page->title);

        return $this->parseShortCode($this->page->description);
    }

}

class ShortCode{
    public $shortcode=[];
    function __construct($shortCode)
    {
        $this->shortcode=$shortCode;
    }

    function getAttribute($name,$default=''){
        if(isset($this->shortcode['attributes'][$name])){
            return $this->shortcode['attributes'][$name];
        }
        else{
            return $default;
        }

    }
}
