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

class ThemesController extends AbnCmsBackendController
{
    use AbnCmsTrait;
    public static $module="themes";
    public static $moduleTitle="Themes";
    public $uniqueKey="id";

    function getModel()
    {
        return AbnCmsModule::class;
    }


    function search($model, $q)
    {
        $model = parent::search($model, $q);
        return $model->where("type","THEME");

    }

    public function canAdd(){
        return false;
    }

    public function viewBuilder($model)
    {
        $builder = parent::viewBuilder($model);
        $builder->setField("is_active",new LinkComponent(["name"=>'is_active',"beforeRender"=>function($component){
            $row = $component->getData("row");
            if($row->is_active=='0'){
                $component->setConfig("link",route("themes.changeStatus",[$row->id]));
                $component->setConfig("label",$row->is_active=='1'?"InActivate":"Activate" );
            }
            else{
                $component->setConfig("label","Active");
            }
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
                    ->update(["is_active"=>'0']);
                    $this->search($this->getModelObject(),"")
                    ->where("id",$slug)
                    ->update(["is_active"=>$plugin->is_active=='0'?'1':'0']);
                    AbnCms::setSettings("ACTIVE_THEME",$class);



                }
                else{
                    throw new \Exception("Unable to activate");
                }
            }

        }
        catch(\Exception $e){
            throw $e;
        }

        return redirect()->back();
    }


}
