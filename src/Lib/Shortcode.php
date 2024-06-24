<?php
namespace Aman5537jains\AbnCms\Lib;

use Aman5537jains\AbnCms\Lib\Theme\Theme;

abstract class Shortcode{
    public $shortcode;
    public  $description;

    function __construct($shortcode,$description)
    {
        $this->shortcode = $shortcode;
        $this->description = $description;

    }
    public function getActiveTheme():Theme{

        return AbnCms::getActiveTheme();
    }

    function render(){
        return "";
    }
}
