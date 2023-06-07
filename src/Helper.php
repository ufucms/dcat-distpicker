<?php

namespace Ufucms\Distpicker;


class Helper
{
    protected $distpicker = [];

    public static function init(): array
    {
        if(!$this->distpicker){
            $this->distpicker = config('dcat-distpicker');
        }
        return $this->distpicker;
    };

    /**
     * 查询指定 code 的名称
     * @param  int|string  $code
     * @return string
     * @author super-eggs
     */
    public static function getAreaName($code): string
    {
        return self::init()[$code] ?? "";
    }

    /**
     * 查询指定codes的名称
     * @param  array $codes
     * @return array|string
     */
    public static function getAreaNames($codes, $delimiter='/'): array|string
    {
        $names = [];
        foreach ($codes as $key => $code) {
            $names[$key] = self::init()[$code] ?? "";
        }
        if($delimiter){
            return implode($delimiter, $names);
        }else{
            return $names;
        }
    }

    /**
     * 合并两个数组来创建一个新数组
     * @param  array  $keys
     * @param  array  $values
     * @return array
     * @author super-eggs
     */
    public static function arrayCombine(array $keys, array $values): array
    {
        $arr = array();
        foreach ($values as $k => $value) {
            $arr[$keys[$k]] = $value;
        }

        return $arr;
    }
    
    /**
     * 根据县区code 获取省市区信息详情
     * @param  number $districtCode
     * @return array
     */
    public static function getAreaInfoByDistrictCode($districtCode)
    {
        // 处理有些市下面没有区县 直接是乡镇
        $regions = $districtCode;
        if($districtCode > 999999){
            $regions = (int) floor($districtCode / 1000);
        }
        // 获取省市数据
        $provinceCode = (int) floor($regions / 10000) * 10000;
        $cityCode = (int) floor($regions / 100) * 100;
        $area_codes = [
            $provinceCode,
            $cityCode,
            $districtCode
        ];
        // 获取省市区 KV数据
        $area = [];
        foreach ($area_codes as $key => $val) {
            $area[$val] = self::getAreaName($val);
        }
        // 省市区字符串拼接
        $area_names = self::getAreaNames($area_codes);
        return compact('area', 'area_codes', 'area_names');
    }

}
