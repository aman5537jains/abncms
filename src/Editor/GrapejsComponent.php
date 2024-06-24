<?php
namespace Aman5537jains\AbnCms\Editor;

use Aman5537jains\AbnCmsCRUD\FormComponent;

class GrapejsComponent extends FormComponent{


    function buildInput($name, $attrs)
    {
        return PageBuilder::load($this->getValue(),["name"=>$name]);
    }
}
