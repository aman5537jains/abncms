<?php
namespace Aman5537jains\AbnCms\Editor\Plugins;

use Aman5537jains\AbnCms\Editor\Plugin;

class ShortCode extends Plugin{


    public function label(){
        return "Shortcode";
    }

    public function traits(){
        $shortcodes = [];
        [
            [
                "type"=>"select",
                "name"=>"name",
                "label"=>"Name",
                "options"=> $shortcodes
            ]
        ];

    }


}
