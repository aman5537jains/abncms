<?php
namespace Aman5537jains\AbnCms\Lib;


trait Sluggable{

    public static function bootSluggable()
    {



        // registering a callback to be executed upon the creation of an activity AR
        static::creating(function($model) {
            $options = $model->sluggable();

            // produce a slug based on the activity title
            $slug = \Str::slug($model->{$options["slug"]['source']});

            // check to see if any other slugs exist that are the same & count them
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            // if other slugs exist that are the same, append the count to the slug
            $model->slug = $count ? "{$slug}-{$count}" : $slug;

        });
        static::updating(function($model) {
            $options = $model->sluggable();
            if($options["slug"]["onUpdate"]){
                // produce a slug based on the activity title
                $slug = \Str::slug($model->{$options["slug"]['source']});

                // check to see if any other slugs exist that are the same & count them
                $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                // if other slugs exist that are the same, append the count to the slug
                $model->slug = $count ? "{$slug}-{$count}" : $slug;
            }

        });

    }
}
