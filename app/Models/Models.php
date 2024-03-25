<?php

namespace App\Models;

use App\DB\Db;


abstract class Models extends Db
{
    public function __construct(
        protected ?string $table = null,
        protected ?Db $db = null,
    ){

    }

    protected function runQuery(string $sql, array $params = null)
    {
        $this->db = Db::getInstance();

        if ($params) {
            // requete préparée
            
        }
    }
}

