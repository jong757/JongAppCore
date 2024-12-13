<?php
namespace App\Library\Interfaces;

interface ConfigInterface
{
    public static function get($namespace, $key = null);
    public static function set($namespace, $key, $value);
    public static function delete($namespace, $key = null);
    public static function load($namespace);
    public static function save($namespace);
}
