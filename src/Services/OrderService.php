<?php

namespace Reyre8\EnsembleChallenge\Services;
use Reyre8\EnsembleChallenge\Models;

class OrderService {

    private $OrderModel;

    public function __construct(Models\OrderModel $OrderModel) {
        $this->OrderModel = $OrderModel;
    }

    /**
     * Retrieves all the orders with details
     */
    public function getAll() {

        // Get All orders
        $data = $this->OrderModel->getAll();

        // Format products in the order as an array of elements
        foreach($data AS $index => $row) {
            $data[$index] = $this->formatProductsInOrder($row);
        }
        return $data;
    }

    /**
     * Retrieves an order record, given the id
     */
    public function get($id) {

        // Get an order given the id
        $data = $this->OrderModel->get($id);

        // Format products in the order as an array of elements
        return $this->formatProductsInOrder($data);
    }

    /**
     * Creates a new order record
     */
    public function post(array $data) {
        return $this->OrderModel->post($data);
    }

    // Format products in the order, as an array of elements
    private function formatProductsInOrder($data) {
        // Format products in the order, as an array of elements
        if(!empty($data['Products'])) {
            $products = array();
            $arrProducts = explode(',', $data['Products']);
            foreach($arrProducts AS $product) {
                $arrProdDetails = explode('|', $product);
                array_push($products, array('Name' => $arrProdDetails[0], 'Quantity' => $arrProdDetails[1]));
            }
            $data['Products'] = $products;
        }
        return $data;
    }

    // Setters and Getters
    public function getOrderServiceModel() {
        return $this->OrderModel;
    }

    public function setOrderServiceModel(Models\OrderModel $OrderServiceModel) {
        $this->OrderModel = $OrderServiceModel;
    }
}
