<?php

namespace Aman5537jains\AbnCms\Models;

use Aman5537jains\AbnCms\Lib\Sluggable;
use Illuminate\Database\Eloquent\Model;

use File;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class CmsManagement extends Model
{
    use Sluggable;

    use HasSEO;

    public function getDynamicSEOData(): SEOData
    {
        $pathToFeaturedImageRelativeToPublicPath = "";// ..;

        // Override only the properties you want:
        return new SEOData(
            title: $this->title,
            description: \Str::words($this->description,10) //will show first 10 words$this->description
        );
    }

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
