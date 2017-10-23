<?php

namespace Reyre8\EnsembleChallenge\Models;

use Reyre8\EnsembleChallenge\Services;
use Reyre8\EnsembleChallenge\Libs;

class OrderModel {

    private static $pdoService;

    public function __construct($config) {
        $this->pdoService = new Services\PDOService($config['host'], $config['database'], $config['user'], $config['password']);
    }

    // Retrieves all the orders with customer and products information
    public function getAll() {
        $sql = '   SELECT co.Id, ' .
               '          co.OrderCreation,' .
               '          c.Name,' .
               '          c.EmailAddress,' .
               '          c.Telephone,' .
               "          GROUP_CONCAT(CONCAT(i.Name, '|', oi.Quantity)) AS Products" .
               '     FROM customerorder co' .
               '     JOIN customer c ON c.id = co.CustomerId' .
               '     JOIN orderitem oi ON oi.OrderId = co.Id' .
               '     JOIN item i ON i.Id = oi.ItemId' .
               ' GROUP BY c.Id, co.OrderCreation, c.Name, c.EmailAddress, c.Telephone' .
               ' ORDER BY co.Id';

        $obj = $this->pdoService->getPdo()->prepare($sql);
        $obj->execute();
        return $obj->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Retrieves all the orders with customer and products information
    public function get($id) {
        $sql = '   SELECT co.Id, ' .
            '          co.OrderCreation,' .
            '          c.Name,' .
            '          c.EmailAddress,' .
            '          c.Telephone,' .
            "          GROUP_CONCAT(CONCAT(i.Name, '|', oi.Quantity)) AS Products" .
            '     FROM customerorder co' .
            '     JOIN customer c ON c.id = co.CustomerId' .
            '     JOIN orderitem oi ON oi.OrderId = co.Id' .
            '     JOIN item i ON i.Id = oi.ItemId' .
            '    WHERE co.Id = :id' .
            ' GROUP BY c.Id, co.OrderCreation, c.Name, c.EmailAddress, c.Telephone';

        $obj = $this->pdoService->getPdo()->prepare($sql);
        $obj->bindParam(':id', $id, \PDO::PARAM_INT);
        $obj->execute();
        return $obj->fetch(\PDO::FETCH_ASSOC);
    }

    // Retrieves all the orders with customer and products information
    public function post($data) {

        // Initialise transaction
        $this->pdoService->getPdo()->beginTransaction();

        // Verify data structure
        $this->validate($data);

        // Insert into customer
        $sql = "INSERT INTO customer (Name, EmailAddress, Telephone) VALUES (:Name, :EmailAddress, :Telephone)";
        $obj = $this->pdoService->getPdo()->prepare($sql);
        $obj->bindParam(':Name', $data['Name']);
        $obj->bindParam(':EmailAddress', $data['EmailAddress']);
        $obj->bindParam(':Telephone', $data['Telephone']);
        $obj->execute();
        $customerId = $this->pdoService->getPdo()->lastInsertId();

        // Insert into order
        $sql = "INSERT INTO customerorder (OrderCreation, CustomerID) VALUES (:OrderCreation, :CustomerID)";
        $obj = $this->pdoService->getPdo()->prepare($sql);
        $obj->bindParam(':OrderCreation', $data['OrderCreation']);
        $obj->bindParam(':CustomerID', $customerId);
        $obj->execute();
        $orderId = $this->pdoService->getPdo()->lastInsertId();

        // Insert into orderitem
        foreach($data['Products'] AS $product) {

            // Validate product
            $this->validateProduct($product);
            $productDetails = $this->getItemByName($product['Name']);

            // Verify that product exists
            if(empty($productDetails)) {
                throw new \Exception(sprintf(Libs\MessageHandler::BLM_005, $product['Name']));
            }

            $sql = "INSERT INTO orderitem (OrderID, ItemID, Quantity) VALUES (:OrderID, :ItemID, :Quantity)";
            $obj = $this->pdoService->getPdo()->prepare($sql);
            $obj->bindParam(':OrderID', $orderId);
            $obj->bindParam(':ItemID', $productDetails['Id']);
            $obj->bindParam(':Quantity', $product['Quantity']);
            $obj->execute();
        }

        // Commit transaction
        $this->pdoService->getPdo()->commit();
        return array('id' => $orderId);
    }

    // This could be validated using swagger open spec
    public function validate($data) {
        $errors = array();
        if(empty($data['Name'])) {
            $errors[] = sprintf(Libs\MessageHandler::BLM_003, 'Name');
        }
        if(empty($data['EmailAddress'])) {
            $errors[] = sprintf(Libs\MessageHandler::BLM_003, 'EmailAddress');
        }
        if(empty($data['Telephone'])) {
            $errors[] = sprintf(Libs\MessageHandler::BLM_003, 'Telephone');
        }
        if(empty($data['Products'])) {
            $errors[] = sprintf(Libs\MessageHandler::BLM_003, 'Products');
        }
        if(!is_array($data['Products'])) {
            $errors[] = Libs\MessageHandler::BLM_004;
        }
        if(!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
    }

    // This could be validated using swagger open spec
    public function validateProduct($data) {
        $errors = array();
        if(empty($data['Name'])) {
            throw new \Exception(sprintf(Libs\MessageHandler::BLM_003, 'Product->Name'));
        }
        if(empty($data['Quantity'])) {
            throw new \Exception(sprintf(Libs\MessageHandler::BLM_003, 'Product->Quantity'));
        }
        if(!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }
    }

    /**
     * @todo: implement in proper class model and factory
     * Retrieves an item by name
     */
    public function getItemByName($name) {
        $sql = '   SELECT * ' .
            '        FROM item' .
            '       WHERE Name = :Name';

        $obj = $this->pdoService->getPdo()->prepare($sql);
        $obj->bindParam(':Name', $name, \PDO::PARAM_STR);
        $obj->execute();
        return $obj->fetch(\PDO::FETCH_ASSOC);


    }
}


