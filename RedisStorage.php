<?php

namespace visitor;

use Redis;

require_once "config.php";

class RedisStorage
{
    private static Redis $redis;


    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (isset(self::$redis) === false) {
            self::$redis = new Redis();
            try {
                $ok = self::$redis->connect(AppConfig::getConfig()["redis"]["host"]);
                if ($ok === false) {
                    error_log("cannot connect to redis");
                    return false;
                }

            } catch (\RedisException $e) {
                error_log("cannot connect to redis: " . $e->getMessage());
                return false;
            }
        }
        return self::$redis;
    }

    public static function increment($country)
    {
        $redis = self::getInstance();
        if ($redis === false) {
            error_log("cannot get redis instance");
            return false;
        }

        try {
            $count = $redis->get($country);
            if ($count === false) {
                $count = 0;
            }

            $count++;

            $redis->append($country, $count);
        } catch (\RedisException $e) {
            error_log("cannot increment visit count of country: " . $e->getMessage());
            return false;
        }

        return true;
    }

    public static function statistic()
    {
        $countries = self::$redis->keys("*");
        if (is_array($countries) === false) {
            return false;
        }

        $res = [];
        foreach ($countries as $country) {
            $count = self::$redis->get($country);
            if ($count === false) {
                continue;
            }
            $res[] = [$country => $count];
        }

        return $res;
    }

}