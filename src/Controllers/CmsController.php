<?php
namespace Aman5537jains\AbnCms\Controllers;

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCms\Models\CmsManagement;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsController extends AbnCmsBackendController
{
    public static $module="cms";
    public static $moduleTitle="Cms";


    function __construct()
    {

        parent::__construct();
        if(request()->get("type","page")=="widget")
        self::$moduleTitle =    "Widget";
        // if(request()->get("type","page")=="layout")
        // self::$moduleTitle =    "Layout";

    }

    function search($model, $q)
    {

        return parent::search($model, $q)->where("type",request()->get("type","page"));
    }

    function action($name, $params = [], $module = null)
    {

       return parent::action($name, $params, $module)."?type=".request()->get("type","page");
    }

    function getModel()
    {

        return CmsManagement::class;
    }

    function viewBuilder($model)
    {
        $view = parent::viewBuilder($model);
        if(request()->get("type","page")=='page'){
            $view->addField("slug",new LinkComponent(["name"=>"View Page","target"=>"_blank","beforeRender"=>function($cmp){
                $row = $cmp->getData("row");
                $cmp->setConfig("link",url("page",["editor_page"=>$row->slug]));
            }]));
        }
        else{
            $view->addField("slug",new TextComponent(["name"=>"shortcode","beforeRender"=>function($cmp){
                $row = $cmp->getData("row");
                $cmp->setValue('[shortcode name="" ]');
            }]));
        }

        $view->onlyFields(["title","slug","status","actions"]);
        return $view;
    }
    function formBuilder($model = null)
    {
        $view = parent::formBuilder($model);
        $view->getField("title")->validator()->add(["required"]);
        $view->onlyFields(["title",'status',"description","submit"]);
        $view->setField("creater_id",new InputComponent(['name'=>'creater_id',"visible"=>false]))->getField('creater_id')->setValue(Auth::id());
        $view->setField("type",new InputComponent(['name'=>'type',"visible"=>false]))->getField('type')->setValue(request()->get("type","page"));

        $view->setField("description",new GrapejsComponent(['name'=>'description']));

        return $view;
    }
}
