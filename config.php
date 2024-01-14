<?php

namespace visitor;

class AppConfig
{
    private static array $config;

    private function __construct()
    {
    }

    public static function load($path = "config.json")
    {
        $data = file_get_contents($path);
        if ($data === false) {
            return new \ErrorException("config not found");
        }

        self::$config = json_decode($data, true);
        if (is_array(self::$config) === false) {
            return new \ErrorException("cannot load config");
        }

        return true;
    }

    public static function getConfig()
    {
        if (isset(self::$config) === false) {
            $ok = self::load();
            if ($ok === false) {
                return false;
            }
        }
        return self::$config;
    }

}
