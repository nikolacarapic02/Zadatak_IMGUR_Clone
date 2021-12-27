<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function fileFormBegin($action, $method, $enctype)
    {
        echo sprintf('<form action="%s" method="%s" enctype="%s">', $action, $method, $enctype);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }

    public function check($error , $attribute)
    {
        if($error)
        {
            echo 
                '<div class="invalid-feedback">
                    This field is required
                 </div>'
            ;
        }
        else
        {
            echo sprintf('
                <small id="emailHelp" class="form-text text-muted">Add a %s to your gallery.</small>
                ',
                $attribute
            );
        }
    }
}