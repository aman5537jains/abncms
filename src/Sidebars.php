<?php
namespace Aman5537jains\AbnCms;


use Aman5537jains\AbnCms\Lib\Sidebar\Sidebar;
use Aman5537jains\AbnCms\Lib\Sidebar\SidebarItem;


class Sidebars{

    public static function dashboard(){
        return new Sidebar("Dashboard",[
            new SidebarItem("Dashboard","#","",function($permissions){
                return isset($permissions["contact-form__view"]);
            })

        ]);
    }
    public static function cmsPages(){

        return new Sidebar("Cms Pages",[
            new SidebarItem("All Pages",route("cms.index"),"",function($permissions){
                return isset($permissions["contact-form__view"]);
            }),
            new SidebarItem("Add New Page",route("cms.create"),"",function($permissions){
                return isset($permissions["contact-form__view"]);
            }),
            new SidebarItem("All Widgets",route("cms.index",["type"=>"widget"]),"",function($permissions){
                return isset($permissions["contact-form__view"]);
            }),
            new SidebarItem("Add New Widget",route("cms.create",["type"=>"widget"]),"",function($permissions){
                return isset($permissions["contact-form__view"]);
            })

        ]);
    }
    public static function users(){
        return new Sidebar("Users",[

        ]);
    }

}
