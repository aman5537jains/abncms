<?php
namespace Aman5537jains\AbnCms\Lib\Sidebar;
class Sidebar {
    public $items = [];
    public $name = "";

    function __construct($name,$items)
    {
           $this->name = $name;
           $this->items = $items;
    }

    function render(){
        $list = "";
        if(count($this->items)==1 && $this->items[0]->hasPermission()){
            $list =  $this->items[0]->render();
        }
        else{

            foreach($this->items as $item){
                if($item->hasPermission()){
                    $list .= $item->render();
                }
            }

            if($list!=""){
                $list= "<li class='daside-item has-child'> <a href='#'>
                         <span>$this->name</span>
                     </a><ul class='dropdown-menu' >$list</ul><li> ";
            }
        }

        return $list;
    }



}
