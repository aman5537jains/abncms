<?php
namespace Aman5537jains\AbnCms\Editor;

use Aman5537jains\AbnCms\Editor\Plugins\HelloWorld;
use Aman5537jains\AbnCms\Editor\Plugins\LoanCalcultor;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Illuminate\Http\Request;

// {!! Aman5537jains\AbnCms\Editor\PageBuilder::assetManager("image") !!}
class PageBuilder{


    static function plugins(){
        return [
            // new LoanCalcultor()
        ];
    }

    static function loadPlugins(){
        $plugins  = self::plugins();
        $all = [];
        $allTypes = [];
        foreach($plugins as $plugin){

            $all[]= [
                "label"=>$plugin->label(),
                "content"=>$plugin->content(),
                "category"=>$plugin->category(),
                "media"=>$plugin->media(),
                "type"=>[
                    "identifier"=>$plugin->identifier(),
                    "scripts"=>$plugin->scripts(),
                    'customScript'=>$plugin->customScript(),
                    "fn"=>$plugin->scriptFunctionName(),
                    "defaults"=>$plugin->defaults(),
                ],


            ];
        }
        return $all;
    }


    static function load($body='',$attr=["name"=>'description']){
        $styleScript = self::getThemeStyleAndScript();

        return  view("AbnCms::grapjs-builder",['body'=>$body,
            'assets'=>self::getCmsFiles(),
            'styles'=>$styleScript['styles'],
            'scripts'=>$styleScript['scripts'],
            'shortcodes'=>self::getShortCodes(),
            'plugins'=>self::loadPlugins(),
            'attr'=> $attr
            ]
        );
    }

    static function getShortCodes(){
        return [
        ];
    }
    public static function getCmsFiles($storagePath=false){
        $files= [];
        $storage =[];
        // dd(config("app.multi_domain_site"));
        foreach (glob(storage_path("uploads/".config("app.multi_domain_site")."/cms/*")) as $key=>$filename) {
             $arr=explode("/",$filename);
            if($arr[count($arr)-1]!="thumb"){
              $files[]=url("storage/uploads/".config("app.multi_domain_site")."/cms/".$arr[count($arr)-1]);
              $storage[]= "storage/uploads/".config("app.multi_domain_site")."/cms/".$arr[count($arr)-1];
            }
        }
        if($storagePath){
           return ["files"=>$files,"storage"=>$storage];
        }
        else
        return  $files;
    }

    public static function getThemeStyleAndScript(){
        $activeThemeClass = AbnCms::getSettings("ACTIVE_THEME");
        $theme= new $activeThemeClass();
        $sheets = $theme->getStylesheets();
        $scripts = $theme->getScripts();
        $allSheets = [];
        $allScripts = [];
        foreach($sheets as $sheet){
            $allSheets[]=$sheet->getSrc();
        }
        foreach($scripts as $script){
            if(!$script->isInline() && !$script->isRaw())
            $allScripts[]=$script->getSrc();
        }
        $files= [
            "styles"=>$allSheets,
            "scripts"=>$allScripts

        ];

        return  $files;
    }

    public static function assetManager($name="image",$preview=""){

            return view("AbnCms::asset-manager",[
                'assets'=>self::getCmsFiles(true),
                'preview'=>$preview,
                'name'=>$name
            ]);
    }

    public static function upload(Request $request){
        $file = is_array($request->file('upload'))?$request->file('upload')[0]:$request->file('upload');
        $image = self::uploadMultiSite($file,"cms");
        return ["data"=>url($image),"file"=>$image,"url"=>url($image)];
    }

    public static function removeAsset(Request $request){
         $url =  $request->get("id");
        $domain = config("app.multi_domain_site");
        $parsedUrl = parse_url($url);
        $file = basename($url);

        unlink(storage_path("uploads/".$domain."/cms/".$file));
        return "DONE";
    }



    // public function getCmsFiles($storagePath=false){
    //     $files= [];
    //     $storage= [];


    //     foreach (glob(storage_path("uploads/cms/*")) as $key=>$filename) {
    //          $arr=explode("/",$filename);
    //         if($arr[count($arr)-1]!="thumb"){
    //          $files[]=url($arr[count($arr)-1]);
    //          $storage[]= ($arr[count($arr)-1]);
    //         }
    //     }
    //     if($storagePath){
    //         ["files"=>$files,"storage"=>$storagePath];
    //     }
    //     else
    //     return  $files;
    // }

    static function uploadMultiSite($fileName,$path="uploads")
    {
            $file = $fileName;
            $destinationPath = 'storage/uploads/'.config("app.multi_domain_site")."/".$path;

            // Check to see if directory already exists
            $exist = is_dir($destinationPath);
            // If directory doesn't exist, create directory

            if(!$exist) {

                \File::makeDirectory($destinationPath, 0755, true);
                // mkdir("$destinationPath");
                // chmod("$destinationPath", 0755);
            }

            $extension = $file->getClientOriginalExtension();
            $fileName = strtotime(now()).'-'.rand(11111,99999).'.'.$extension;
            $file->move($destinationPath, $fileName);
            return $destinationPath."/".$fileName;
    }
}


