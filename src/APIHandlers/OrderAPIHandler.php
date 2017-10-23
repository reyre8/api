<?php

namespace Reyre8\EnsembleChallenge\APIHandlers;

use Reyre8\EnsembleChallenge\APIHandlers;
use Reyre8\EnsembleChallenge\Libs;
use Reyre8\EnsembleChallenge\Factories;

use Reyre8\EnsembleChallenge\Exceptions;

/**
 * Class to handle requests for orders
 */
class OrderAPIHandler implements APIHandlers\APIHandlerInterface {

    /**
     * Handler that retrieves all the orders with details
     */
    public function getAll(Libs\RequestHandler $requestHandler) {
        $orderService = Factories\OrderServiceFactory::create($GLOBALS['config']);
        $result = $orderService->getAll();
        $requestHandler->respondSuccess($result);
    }

    /**
     * Handler that retrieves an order record, given the id
     */
    public function get(Libs\RequestHandler $requestHandler) {
        $orderService = Factories\OrderServiceFactory::create($GLOBALS['config']);
        $result = $orderService->get($requestHandler->getArgs()['id']);
        if(!empty($result)) {
            $requestHandler->respondSuccess($result);
        } else {
            $requestHandler->respondFailure(array(Libs\MessageHandler::BLM_001), Libs\RequestHandler::NOT_FOUND_CODE);
        }
    }

    /**
     * Handler that creates a new order record
     */
    public function post(Libs\RequestHandler $requestHandler) {
        $orderService = Factories\OrderServiceFactory::create($GLOBALS['config']);
        $result = $orderService->post($requestHandler->getBody());
        $requestHandler->respondSuccess($result);
    }

    /**
     * To be implemented
     */
    public function put(Libs\RequestHandler $requestHandler) {}

    /**
     * To be implemented
     */
    public function patch(Libs\RequestHandler $requestHandler) {}

    /**
     * To be implemented
     */
    public function delete(Libs\RequestHandler $requestHandler) {}
}


