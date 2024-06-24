<?php

namespace Aman5537jains\AbnCms\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use File;

class CmsManagement extends Model
{
    use Sluggable;



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate'=>false
            ]
        ];
    }


    public static function getCmsManagements(){
        return  self::where('status','1')->pluck("title","id")->toArray();
    }

    public function getImageAttribute()
    {
        $path = "storage/uploads/cms_management/".$this->attributes['image'];
        if(isset($this->attributes['image']) && File::exists($path)){
            return asset($path);
        } else {
            return  asset("public/asset/images/no_image.png");
        }
    }
     public function getIconAttribute()
    {
        $path = "storage/uploads/cms_management/".$this->attributes['icon'];
        if(isset($this->attributes['icon']) && File::exists($path)){
            return asset($path);
        } else {
            return  asset("public/asset/images/no_image.png");
        }
    }






}
