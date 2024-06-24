<?php
namespace Aman5537jains\AbnCms\Controllers;

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\AbnCmsTrait;
use Aman5537jains\AbnCms\Models\AbnCmsModule;
use Aman5537jains\AbnCms\Models\CmsManagement;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PluginsController extends AbnCmsBackendController
{
    use AbnCmsTrait;
    public static $module="plugins";
    public static $moduleTitle="Plugins";
    public $uniqueKey="id";

    function getModel()
    {
        return AbnCmsModule::class;
    }


    function search($model, $q)
    {
        $model = parent::search($model, $q);
        return $model->where("type","PLUGIN");

    }

    public function canAdd(){
        return false;
    }

    public function viewBuilder($model)
    {
        $builder = parent::viewBuilder($model);
        $builder->setField("is_active",new LinkComponent(["name"=>'is_active',"beforeRender"=>function($component){
            $row = $component->getData("row");
            $component->setConfig("link",route("plugins.changeStatus",[$row->id]));
            $component->setConfig("label",$row->is_active=='1'?"InActivate":"Activate" );
        }]));
        return $builder;

    }

    function changeStatus(Request $request, $slug)
    {
        try{
            $plugin = $this->search($this->getModelObject(),"")->where("id",$slug)->first();
            if($plugin->is_active=='0'){
                $class = $plugin->class_name;
                $classObject =new $class;
                if($classObject->onActivate()){
                    $this->search($this->getModelObject(),"")
                    ->where("id",$slug)
                    ->update(["is_active"=>$plugin->is_active=='0'?'1':'0']);
                    AbnCms::flash("Activated Successfully");
                }
                else{
                    throw new \Exception("Unable to activate");
                }
            }
            else{
                $class = $plugin->class_name;
                $classObject =new $class;
                if($classObject->onInActivate()){
                    $this->search($this->getModelObject(),"")
                    ->where("id",$slug)
                    ->update(["is_active"=>$plugin->is_active=='0'?'1':'0']);
                }
                else{
                    throw new \Exception("Unable to inactivate");
                }
            }
        }
        catch(\Exception $e){
            throw $e;
        }

        return redirect()->back();
    }

    function activateAction(Request $request){
        $name = $request->get("name");;

        $plugins =  config("abncms.plugins");
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
