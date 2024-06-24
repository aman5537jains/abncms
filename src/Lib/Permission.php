<?php
namespace Aman5537jains\AbnCms\Lib;


class Permission {
    public $name = "";
    public $permissions = ['view' => 'view', 'add' => 'add', 'edit' => 'edit', 'status'=>'status', 'delete'=>'delete', "options"=>["label"=>'Speciality']];
    function __construct($name)
    {
        $this->name = $name;
        $this->permissions["options"]["label"]=$name;
    }
    function getName()
    {
        return $this->name;;
    }
    function getPermissions()
    {

        return $this->permissions;;
    }
    function setLabel($name)
    {
        $this->permissions["options"]["label"]=$name;
        return $this;
    }
    function addPermission($name)
    {
        $this->permissions[$name]=$name;
        return $this;
    }
    function removePermission($name)
    {
        unset($this->permissions[$name]);
        return $this;
    }
}
