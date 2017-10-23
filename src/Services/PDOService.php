<?php

namespace Reyre8\EnsembleChallenge\Services;
use \PDO;

class PDOService
{
    private $pdo;

    public function __construct($dbHost, $dbName, $dbUser, $dbPassword) {
        try {
            $pdo = new PDO('mysql:host='.$dbHost.'; dbname='.$dbName, $dbUser, $dbPassword);
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $pdo->exec("SET CHARACTER SET utf8");
            $this->pdo = $pdo;
        }
        catch (PDOException $err) {
            echo "Connection failure: ";
            $err->getMessage() . "<br/>";
            file_put_contents('PDOErrors.txt',$err, FILE_APPEND);
            exit();
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function setPdo($pdo) {
        $this->pdo = $pdo;
    }
}