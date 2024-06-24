<?php
namespace Aman5537jains\AbnCms\Lib\Sidebar;
class SidebarItem {
    public $name = "";
    public $icon = "";
    public $link = "";
    public $permissions ;


    function __construct($name,$link='',$icon='',$permissions=null)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->link = $link;
        $this->permissions = $permissions;

    }


    function hasPermission(){
        return true;
        if($this->permissions){
            $cb = $this->permissions;
            return  $cb([]);
        }
        return true;
    }


    function render(){
            return <<<ITEM
                        <li class="daside-item">
                            <a href="$this->link">
                                $this->icon
                                <span>$this->name</span>
                            </a>
                        </li>
            ITEM;
    }



}
