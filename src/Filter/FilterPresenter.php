<?php

namespace Ufucms\DcatDistpicker\Filter;

use Dcat\Admin\Grid\Filter\Presenter\Presenter;

class FilterPresenter extends Presenter
{
    public function view(): string
    {
        return 'china-distpicker::filter';
    }
}
