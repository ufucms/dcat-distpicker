<?php

namespace Ufucms\Distpicker\Grid;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;
use Ufucms\Distpicker\Helper;

class Distpicker extends AbstractDisplayer
{
    public function display()
    {
        return Helper::getAreaName($this->value);
    }
}
