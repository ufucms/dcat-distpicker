<?php

namespace Ufucms\Distpicker;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Column;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Support\Helper;
use Ufucms\Distpicker\Filter\DistpickerFilter;
use Ufucms\Distpicker\Form\Distpicker;


class DistpickerServiceProvider extends ServiceProvider
{
    public function init()
    {
        parent::init();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dcat-distpicker');
        if ($this->app->runningInConsole()) {
            $extension_path = Helper::path($this->getName());
            $extension_dir = Admin::asset()->getRealPath('@extension');
            $path = "{$extension_dir}/{$extension_path}";
            $this->publishes([__DIR__.'/../config' => config_path()], 'dcat-distpicker');
            $this->publishes([__DIR__.'/../resources/assets' => public_path($path)], 'dcat-distpicker-assets');
        }

        //加载插件
        Admin::booting(static function () {
            Column::extend('distpicker', Grid\Distpicker::class);
            Form::extend('distpicker', Distpicker::class);
            Filter::extend('distpicker', DistpickerFilter::class);
        });
    }
}
