<?php
namespace Aman5537jains\AbnCms\Controllers;

use Aman5537jains\AbnCms\Models\CmsManagement;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends AbnCmsBackendController
{
    public $uniqueKey="id";
    public static $module="admin-users";
    public static $moduleTitle="Users";

    function getModel()
    {
        return User::class;
    }

    function viewBuilder($model)
    {
        $builder = parent::viewBuilder($model);
        $builder->onlyFields(["name","email","actions"]);
        return $builder;
    }
    function formBuilder($model = null)
    {

        $builder = parent::formBuilder($model);
        $builder->onlyFields(["name","email","password","submit"]);
        $password = $builder->fields["password"]->setConfig("type","password");

        if($model->exists){
             $password->setValidations([]);
        }

        if(request()->isMethod("post") || request()->isMethod("patch")){
            $builder->setConfig("beforeSave",function($f,$model){
                $model->password = bcrypt($f->getField("password")->getValue());
                return $model;
            });


        }

        return $builder;
    }


}
