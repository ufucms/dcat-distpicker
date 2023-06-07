<?php

namespace Ufucms\Distpicker;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Column;
use Dcat\Admin\Grid\Filter;
use Ufucms\Distpicker\Filter\DistpickerFilter;
use Ufucms\Distpicker\Form\Distpicker;


class DistpickerServiceProvider extends ServiceProvider
{
    public function init()
    {
        parent::init();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dcat-distpicker');
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'dcat-distpicker');
        }

        //加载插件
        Admin::booting(static function () {
            Column::extend('distpicker', Grid\Distpicker::class);
            Form::extend('distpicker', Distpicker::class);
            Filter::extend('distpicker', DistpickerFilter::class);
        });
    }
}
