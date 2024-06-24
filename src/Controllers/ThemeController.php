<?php
namespace Aman5537jains\AbnCms\Controllers;

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCms\Lib\AbnCmsTrait;
use Aman5537jains\AbnCms\Models\CmsManagement;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends AbnCmsBackendController
{
    use AbnCmsTrait;
    public static $module="thems";
    public static $moduleTitle="Themes";


    function getModel()
    {
        return CmsManagement::class;
    }

    function index(Request $request,$id=""){
        $this->theme="AbnCms::";
        $this->view="";
        $plugins =  config("abncms.themes");
        $infos=[];

        foreach($plugins as $plugin=>$options){
            $obj = new $plugin;
            $obj->setOptions($options);
            $infos[]=$obj;
        }

       return $this->view("plugins",["plugins"=>$infos]);
    }

    function activateAction(Request $request){
        $name = $request->get("name");;

        $plugins =  config("abncms.themes");
        $options = $plugins[$name];

        $obj = new $name;

        if($request->get("action")=="activate"){
            if($obj->onActivate()){
                $options["activate"]=true;
            }
        }
        else if($request->get("action")=="inactivate"){
            if($obj->onInActivate()){
                $options["activate"]=false;
            }
        }

        $this->updateConfigSettings("plugins",$name,$options);
        return redirect()->back();

    }
}
