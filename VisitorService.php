<?php

namespace visitor;

require_once "RedisStorage.php";

use ErrorException;

class VisitorService
{
    private function __construct()
    {
    }

    private static VisitorService $instance;

    public static function getInstance(): VisitorService
    {
        if (isset(self::$instance) === false) {
            self::$instance = new VisitorService();
        }

        return self::$instance;
    }

    public function VisitRecord($country)
    {
        $ok = RedisStorage::increment($country);
        if ($ok === false) {
            return new ErrorException("cannot record new visit");
        }

        return true;
    }

    public function GetVisitStatistic()
    {
        $statistic = RedisStorage::statistic();

        if (!is_array($statistic)) {
            return new ErrorException("cannot get visit statistic");
        }

        return $statistic;
    }

}