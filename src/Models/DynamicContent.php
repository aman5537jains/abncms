<?php

namespace Aman5537jains\AbnCms\Models;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use File;

class DynamicContent extends Model
{
    use Sluggable;



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate'=>true
            ]
        ];
    }

    public function getDynamicContent()
    {
       return $this->belongsTo(\App\Models\DynamicContentType::class,"dynamic_content_type_id");
 
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
    






}
