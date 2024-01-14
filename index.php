<?php

namespace visitor;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once "VisitorService.php";

require_once __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {

    $visitCountry = $request->getAttribute("country", "none");

    if ($visitCountry === "none") {
        $lang = $request->getHeader("lang");

        if (is_array($lang) && count($lang) == 0) {
            $payload = json_encode(["error" => "empty country"]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    $ok = VisitorService::getInstance()->VisitRecord($visitCountry);

    if ($ok === false) {
        $payload = json_encode(["error" => "record new visit"]);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    return $response;
});

$app->get('/statistic', function (Request $request, Response $response, $args) {

    $statistic = VisitorService::getInstance()->GetVisitStatistic();

    if ($statistic === false) {
        $payload = json_encode(["error" => "record new visit"]);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    return $response;
});

$app->run();