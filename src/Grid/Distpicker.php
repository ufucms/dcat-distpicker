<?php

namespace Ufucms\DcatDistpicker\Grid;

use Dcat\Admin\Grid\Displayers\AbstractDisplayer;
use Ufucms\DcatDistpicker\Helper;

class Distpicker extends AbstractDisplayer
{
    public function display()
    {
        return Helper::getAreaName($this->value);
    }
}
