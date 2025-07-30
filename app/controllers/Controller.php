<?php

namespace App\Controllers;

use Core\Database;

class Controller {
    /**
     * @param object Database
     */
    protected $pdo;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
}