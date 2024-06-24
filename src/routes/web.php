<?php

use Aman5537jains\AbnCms\Controllers\CmsController;
use Aman5537jains\AbnCms\Controllers\PluginsController;
use Aman5537jains\AbnCms\Controllers\ThemesController;
use Aman5537jains\AbnCms\Controllers\UserController;
use Aman5537jains\AbnCms\Editor\PageBuilder;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCmsCRUD\CrudService;
use Illuminate\Http\Request;
use Aman5537jains\AbnCms\Editor\Editor;

Route::post('abn-cms/upload', function (Request $request){
    return PageBuilder::upload($request);
});

\Route::group(["middleware"=>["web","auth"],"prefix"=>"cpadmin"],function(){



    Route::get('abn-cms/delete-upload', function (Request $request){
       return PageBuilder::removeAsset($request);
    });
    Route::get('plugin-action', [PluginsController::class,"activateAction"] )->name('plugins-action');
    CmsController::resource();
    // UserController::resource();
    PluginsController::resource();
    ThemesController::resource();


});

\Route::group(["middleware"=>["web"]],function(){
    Route::get('/{editor_page}', function (Request $request,$page){
       
      return AbnCms::getActiveTheme()->setPageContent((new Editor())->getPage($page)->render())->render();
    });
    // Route::fallback(function () {
    //    $page =  trim(request()->getPathInfo(),"/");
    //    // dd($page);
    //    return AbnCms::getActiveTheme()->setPageContent((new Editor())->getPage($page)->render())->render();

    // });
});
