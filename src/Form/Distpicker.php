<?php

namespace Ufucms\Distpicker\Form;

use Dcat\Admin\Form\Field;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Ufucms\Distpicker\Helper;

class Distpicker extends Field
{
    /**
     * @var string
     */
    protected $view = 'dcat-distpicker::select';

    /**
     * @var array
     */
    protected static $js = [
        '@extension/ufucms/dcat-distpicker/dist/distpicker.js',
    ];

    /**
     * @var array
     */
    protected $columnKeys = ['province', 'city', 'district'];

    /**
     * @var string
     */
    protected $valueType = 'code';

    /**
     * @var array
     */
    protected $placeholder = [];

    /**
     * Distpicker constructor.
     *
     * @param  array  $column
     * @param  array  $arguments
     */
    public function __construct(array $column, $arguments)
    {
        parent::__construct($column, $arguments);
        if (!Arr::isAssoc($column)) {
            $this->column = Helper::arrayCombine($this->columnKeys, $column);
        } else {
            $this->column = Helper::arrayCombine($this->columnKeys, array_keys($column));
            $this->placeholder = Helper::arrayCombine($this->columnKeys, array_values($column));
        }

        $this->label = empty($arguments) ? '地区选择' : current($arguments);
    }

    /**
     * 获取验证器
     * @param  array  $input
     * @return false|Application|Factory|Validator|mixed
     */
    public function getValidator(array $input)
    {
        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        foreach ($this->column as $column) {
            if (!Arr::has($input, $column)) {
                continue;
            }
            $input[$column] = Arr::get($input, $column);
            $rules[$column] = $fieldRules;
            $attributes[$column] = $this->label."[$column]";
        }

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * @param  int  $count
     * @return $this
     */
    public function autoselect(int $count = 0): self
    {
        return $this->attribute('data-autoselect', $count);
    }

    /**
     * @param  String  $type
     * @return $this
     */
    public function valueType(string $type = 'code'): self
    {
        $this->valueType = $type;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->attribute('data-value-type', $this->valueType);

        $province = old($this->column['province'], Arr::get($this->value(), 'province')) ?: Arr::get($this->placeholder,
            'province');
        $city = "";
        $district = "";
        if (isset($this->column['city'])) {
            $city = old($this->column['city'], Arr::get($this->value(), 'city')) ?: Arr::get($this->placeholder,
                'city');
        }
        if (isset($this->column['district'])) {
            $district = old($this->column['district'],
                Arr::get($this->value(), 'district')) ?: Arr::get($this->placeholder, 'district');
        }

        $id = uniqid('distpicker-', false);
        $this->script = <<<JS
Dcat.init('#{$id}', function (self) {
    self.distpicker({
      valueType: '$this->valueType',
      province: '$province',
      city: '$city',
      district: '$district'
    });
})
JS;
        $this->addVariables(compact('id'));

        return parent::render();
    }
}
