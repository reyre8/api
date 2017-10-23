<?php

require __DIR__ . '/vendor/autoload.php';

use Reyre8\EnsembleChallenge\Libs;

// Initialise dispatcher
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    // Order endpoints
    $r->addGroup('/ensemblechallenge/orders', function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '', 'Reyre8\EnsembleChallenge\APIHandlers\OrderAPIHandler/getAll');
        $r->addRoute('GET', '/{id:\d+}', 'Reyre8\EnsembleChallenge\APIHandlers\OrderAPIHandler/get');
        $r->addRoute('POST', '', 'Reyre8\EnsembleChallenge\APIHandlers\OrderAPIHandler/post');
    });
});

// Initialise request handler
Libs\RequestHandler::init($dispatcher);