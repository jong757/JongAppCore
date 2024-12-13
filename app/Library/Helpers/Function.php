<?php
/**
 * 系统级别的全局函数文件
 * 这里定义的函数会被加载到全局作用域
 */

// 例如：字符串处理函数
if (!function_exists('str_to_upper')) {
    /**
     * 将字符串转换为大写
     *
     * @param string $string
     * @return string
     */
    function str_to_upper($string)
    {
        return strtoupper($string);
    }
}

// 例如：日期处理函数
if (!function_exists('format_date')) {
    /**
     * 格式化日期
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    function format_date($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
}

// 例如：数组操作函数
if (!function_exists('array_flatten')) {
    /**
     * 将多维数组扁平化
     *
     * @param array $array
     * @return array
     */
    function array_flatten($array)
    {
        $result = [];
        array_walk_recursive($array, function($value) use (&$result) {
            $result[] = $value;
        });
        return $result;
    }
}


// 例如：生成唯一 ID 函数
if (!function_exists('generate_unique_id')) {
    /**
     * 生成一个唯一的 ID
     *
     * @return string
     */
    function generate_unique_id()
    {
        return uniqid('', true);
    }
}

