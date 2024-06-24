<?php
namespace Aman5537jains\AbnCms\Lib;

use AbnCms\RolesPermission\Models\Permission;
use Aman5537jains\AbnCms\Lib\Theme\Theme;
use Aman5537jains\AbnCms\Models\AbnCmsModule;
use Config;
use \Aman5537jains\AbnCms\Models\User;
use Aman5537jains\AbnCmsSettingPlugin\SettingService;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AbnCms{

    public static $activeTheme;


    public static function flash($message,$type="info"){
        Session::flash('message', $message);
        Session::flash('alert-class', 'alert-abncms-'.$type);
    }

    public static function setDatabase(){

        if(request()->getHttpHost()=="abnocloud.com") {

        }
        else   {
            $client = User::where("domain",request()->getHttpHost())->where("status",1)->first();
            if($client){
               self::setDatabaseConfig($client);
               Config::set("app.multi_domain_site",request()->getHttpHost());
            }
            else{
                die("not found");
            }
        }
    }

    public static function isPluginActive($plugin){

        return AbnCmsModule::where("class_name",$plugin)->where("is_active",'1')->count()>0?true:false;
    }

    public static function setDatabaseConfig($client){
                $config = Config::get("database.connections.mysql");
                $config["host"] = $client->db_host;
                $config["database"] =  $client->db_database;
                $config["username"] = $client->db_username;
                $config["password"] =$client->db_password;
                Config::set("database.connections.mysql",$config);
                Config::set("database.connections.{$client->id}-domain",$config);


    }

    public static function getAllClients($domains=[]){
        if(count($domains)<=0)
            return User::where("status",1)->get();
        if(count($domains)>0)
            return User::whereIn("domain",$domains)->where("status",1)->get();
    }
    public static function getSettings($slug,$default=''){
        return SettingService::getSettings($slug,$default);
    }
    public static function setSettings($slug,$default=''){
        return SettingService::setSettings($slug,$default);
    }

    public static function getActiveTheme($type="ACTIVE_THEME"):Theme{
        if(AbnCms::$activeTheme){

            return AbnCms::$activeTheme;
        }

        $activeThemeClass = self::getSettings($type);

        if($activeThemeClass!=''){
            AbnCms::$activeTheme = new $activeThemeClass();

            return AbnCms::$activeTheme;
        }
        else{
            throw new \Exception( "Theme not set" );
        }

    }
    public static function getPermissions(){
         $permissions =  config("abncms.permissions");
         $all = [];
         foreach($permissions as $permission){
            $per = $permission::permissions();
            $all[$per->getName()]=$per->getPermissions();
         }
         return $all;

    }
    public static function getSidebar($type="backend_sidebar"){
        $all = "";
        $menus=  MenuItems::where("menu",1)->where("parent",0)->orderBy("sort")->get();

        foreach($menus as $menu){

            $class = $menu->menu_handler;
            if($menu->menu_handler==""){
                $class= 'Harimayco\Menu\DefaultMenuHandler';
            }

            $obj = new $class($menu,json_decode($menu->menu_handler_config));
            $all.=$obj->render();
        }
        return $all;
        $sidebars =  config("abncms.".$type);
        $all = "";

        foreach($sidebars as $sidebar){
            if(is_string($sidebar)){
                $all .= $sidebar::sidebar()->render();
            }
            else{
                $action =$sidebar[1];
                $all .= $sidebar[0]::$action()->render();
            }
        }
        return $all;

   }

   public static function removeAdminMenu($name){
    MenuItems::where("label",$name)->delete();
   }

   public static function createAdminMenu($name,$link='',$icon='',$parent=0,$permissions=[],$handler="Harimayco\Menu\DefaultMenuHandler",$type="ROUTE_NAME",$config=[]){
        $Menus = new MenuItems();
        $Menus->label = $name;
        $Menus->link_type = $type;
        $Menus->link = $link;
        $Menus->icon = $icon;
        $Menus->permissions = json_encode($permissions);
        $Menus->menu_handler = $handler;
        $Menus->menu_handler_config = json_encode($config);
        $Menus->parent = $parent;
        $Menus->menu = 1;
        $Menus->depth = $parent>0?1:0;
        $Menus->sort = MenuItems::getNextSortRoot(1);
        $Menus->save();
        return $Menus;
    }

    public static function defaultPermissions(){
    return [
        "users"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "roles"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "cms"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "menu-builder"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "plugins"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "themes"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        // "shortcodes"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"],
        "settings"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]
    ];
   }
   public static function createDefaultAdminSidebar(){
        $menu = new Menus();
        $menu->name = "ADMIN_MENU";
        $menu->is_editable = "0";
        $menu->save();

        self::createAdminMenu("Dashboard");
        $menu =  self::createAdminMenu("Cms");

        self::createAdminMenu("All Pages","cms.index","",$menu->id,[["module"=>"cms","action"=>"view"]]);

        self::createAdminMenu("All Widgets","cms.index","",$menu->id,[["module"=>"cms","action"=>"view"]],'Harimayco\Menu\DefaultMenuHandler',"ROUTE_NAME",["params"=>["type"=>"widget"]]);
        $menu =AbnCms::createAdminMenu("Settings");


        self::createAdminMenu("Add New User","users.store","",$menu->id,[["module"=>"users","action"=>"add"]]);
        self::createAdminMenu("All Users","users.index","",$menu->id,[["module"=>"users","action"=>"view"]]);
        self::createAdminMenu("Roles & Permissions","roles.index","",$menu->id,[["module"=>"roles","action"=>"view"]]);
        self::createAdminMenu("Menu Builder","menu-builder.index","",$menu->id,[["module"=>"menu-builder","action"=>"view"]]);
        self::createAdminMenu("Plugins","plugins.index","",$menu->id,[["module"=>"plugins","action"=>"view"]]);
        self::createAdminMenu("Themes","themes.index","",$menu->id,[["module"=>"themes","action"=>"view"]]);
        AbnCms::createAdminMenu("Global Settings","admin-settings","",$menu->id,[["module"=>"settings","action"=>"view"]]);


        return $menu;
    }

    public static function addPermissions($permissions){
         return   Permission::addPermissions($permissions);
    }
    public static function removePermissions($permissions){
         return   Permission::removePermissions($permissions);
    }

    public static function addModule($name,$class,$type="PLUGIN",$option=[]){
        if(AbnCmsModule::where("class_name",$class)->count()<=0){
            $AbnCmsModule=  new AbnCmsModule;
            $AbnCmsModule->name=$name;
            $AbnCmsModule->class_name=$name;
            $AbnCmsModule->type=$type;
            $AbnCmsModule->configs=isset($option['configs'])?$option['configs']:"";
            $AbnCmsModule->description=isset($option['description'])?$option['description']:"";
            $AbnCmsModule->is_active='0';

            return $AbnCmsModule->save();
        }


   }
   public static function removeModule($class){
        return AbnCmsModule::where("class_name",$class)->delete();
   }







}
