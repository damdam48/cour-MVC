<?php

namespace App\Models;

use App\DB\Db;
use PDOStatement;

abstract class Models extends Db
{
    public function __construct(
        protected ?string $table = null,
        protected ?Db $db = null,
    ) {
    }

    /**
     * Find all data in one table
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->runQuery("SELECT * FROM $this->table")->fetchAll();
    }

    /**
     * find One data in table filter by ID
     *
     * @param integer $id
     * @return array|boolean
     */
    public function find(int $id): array|bool
    {
        return $this->runQuery("SELECT * FROM $this->table WHERE id = :id", [
            'id' => $id,
        ])->fetch();
    }


    /**
     * find data in datebasez with multiple filter
     *
     * @param array $filters
     * @return array
     */
    public function findBy(array $filters): array
    {
        $champs = [];
        $params = [];

        foreach ($filters as $key => $value) {
            $champs[] = "$key = :$key";
            $params[$key] = $value;
        }
        $champStr = implode(' AND ', $champs);

        return $this->runQuery("SELECT * FROM $this->table WHERE $champStr", $params)->fetchAll();
    }



    /**
     * Execute SQL query in database
     *
     * @param string $sql
     * @param array|null $params
     * @return PDOStatement|boolean
     */
    protected function runQuery(string $sql, array $params = null): PDOStatement|bool
    {
        $this->db = Db::getInstance();

        if ($params) {
            // requete préparée
            $query = $this->db->prepare($sql);
            $query->execute($params);
        } else {
            $query = $this->db->query($sql);
        }
        return $query;
    }
}
