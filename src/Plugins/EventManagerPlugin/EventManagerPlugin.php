<?php
namespace Aman5537jains\AbnCms\Plugins\EventManagerPlugin;

use Aman5537jains\AbnCms\Lib\Plugin;

class EventManagerPlugin extends Plugin {

    public function getName()
    {
        return "Event Manager By Aman";

    }
    public function onActivate()
    {
        return true;
    }
    public function onInActivate()
    {
        return true;
    }
    public function install()
    {

        return true;
    }
    public function unInstall()
    {
        return true;
    }



}
