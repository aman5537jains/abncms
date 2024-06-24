<?php

namespace Aman5537jains\AbnCms\Lib\Theme;

use Aman5537jains\AbnCms\Editor\Editor;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\SeoBuilder\SeoBuilder;
use Aman5537jains\AbnCms\Models\CmsManagement;
use Aman5537jains\AbnCmsCRUD\CrudService;

class Theme{
    private $stylesheets =[];
    private $scripts =[];
    private $seo;
    protected $key="AbnCms";
    protected $page="";
    protected $layout="AbnCms.theme.index";
    protected $layout_config=[];

    function __construct()
    {
      $this->seo = (new SeoBuilder());


    }
    public function getKey(){
        return "AbnCms";
    }
    public function getName(){
        return "Main";
    }
    public function getScreenshot(){
        return "Main";
    }
    public function getSeo(){
        return $this->seo;
    }
    public function setPageTitle($title){
          $this->seo->setTitle($title);
          return $this;
    }
    public function setPageDescription($title){
          $this->seo->setDescription($title);
          return $this;
    }

    public function getDescription(){
        return "this is a test layout";
    }

    public function getAuthor(){
        return "Test";
    }
    public function getSetting($name){
        return AbnCms::getSettings($name);
    }

    public function view($name,$arr=[]){

        return view($this->getKey()."::$name",["theme"=>$this]+$arr);
    }

    public function getHeader(){
        return $this->view("{$this->key}::theme.header");
    }

    public function getLayout2($layout="index"){
        return $this->view("$layout");
    }
    public function getPageContent(){
        $message='';
// dd(session()->get('message'));
        if(session()->has('message')){

             $class = session()->get('alert-class', 'alert-info');
            $message = '<p id="alert-abn-cms" class="alert-abncms '.$class.'"><span onclick="this.parentElement.classList.add(\'hidden\');" class="closebtn">&times;</span> <strong>'.($class=="alert-abncms-info"?"Success!":"Error!").'</strong> '.session()->get('message').'</p>';
         }


        return  $message.$this->page ;
    }

    public function setPageContent($content){
        $this->page =$content;
        return $this;
    }

    public function setEditorPage(){

        if(request("editor_page","")){

            $this->setPageContent((new Editor())->getPage(request("editor_page",""))->render());
           
            return $this;
        }
        return  $this->setPageContent("");
    }

    public function getFooter(){
        return $this->view("{$this->key}::theme.footer");
    }
    public function addStylesheets($sheets=[]){

        foreach($sheets as $sheet){
            $this->stylesheets[] = $sheet;
        }


        return $this;
    }
    public function addScripts($scripts=[]){

        foreach($scripts as $script){
            $this->scripts[] = $script;
        }

        return $this;
    }
    public function loadScripts(){
        $links='';
        $this->addScripts([(new  ScriptLoader(CrudService::js()))->raw(true)]);
        if(count($this->scripts)>0){
            foreach($this->scripts as $sheet){
                $links.= $sheet->render();
            }
        }

        return $links;
    }
    public function loadStylesheets(){
        $links='';
        $this->addStylesheets([
                (new StylesheetLoader(".alert-abncms{ padding:20px;color:white;  
                    position: fixed;
                    
                    top: 74px;
                    z-index: 9999;
                    min-width: 304px;
                    transition: opacity 0.5s ease;
                    right: 16px;} .hidden {
                        opacity: 0;
                    } .alert-abncms-info{ background: #04AA6D} .alert-abncms-danger{ background:#f44336} 
                    .closebtn {
                        margin-left: 15px;
                        color: white;
                        font-weight: bold;
                        float: right;
                        font-size: 22px;
                        line-height: 20px;
                        cursor: pointer;
                        transition: 0.3s;
                      }
                      
                      .closebtn:hover {
                        color: black;
                      }
                    "))->inline(true),
                    (new  ScriptLoader(CrudService::rawJs()))->raw(true)
             ]);

             $this->addStylesheets([
                (new StylesheetLoader(" <script> document.addEventListener('DOMContentLoaded', function() {

                    // Wait for 2 seconds before removing the container
                    setTimeout(function() {
                        var container = document.getElementById('alert-abn-cms');

                        if(container) {
                            container.classList.add('hidden'); // Apply CSS animation
                            // After the animation completes, remove the container from the DOM
                            container.addEventListener('transitionend', function() {
                                container.parentNode.removeChild(container);
                            });
                        }
                    }, 5000);
                }); </script> "))->raw(true)
             ]);
        if(count($this->stylesheets)>0){
            foreach($this->stylesheets as $sheet){
                $links.= $sheet->render();
            }
        }

        return $links;
    }
    public function getStylesheets(){

        return $this->stylesheets;
    }
    public function getScripts(){

        return $this->scripts;
    }
    public function heads(){
        return [];
    }
    public function getTitle(){
        return [];
    }
    public function mixinStylesheet(){
        return '';
    }

    public function beforeRender(){
        // if(request("editor_page","")){
        //      $this->setPageContent((new Editor())->getPage(request("editor_page",""))->render());
        // }
        $this->addStylesheets([(new StylesheetLoader($this->mixinStylesheet()))->inline(true)]);
    }

    public function setLayout($layout,$config=[]){
        $this->layout = $layout;
        $this->layout_config = $config;
        return $this;
    }
    public function getLayout(){

        return $this->layout  ;
    }
    public function onActivate(){
        return true;
    }
    public function onInActivate(){
        return true;
    }

    public function render($arr=[]){
        $this->beforeRender();

        return $this->view($this->getLayout(),array_merge($this->layout_config,$arr));
    }

}


