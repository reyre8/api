<?php

namespace Reyre8\EnsembleChallenge\Factories;

use Reyre8\EnsembleChallenge\Services;
use Reyre8\EnsembleChallenge\Models;

class OrderServiceFactory {
    public static function create($config) {
        $orderModel = new Models\OrderModel($config['db']);
        return new Services\OrderService($orderModel);
    }
}