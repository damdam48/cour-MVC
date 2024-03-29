<?php

namespace App\Models;

use App\Core\Db;
use DateTime;
use PDOStatement;

abstract class Model extends Db
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
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table")->fetchAll()
        );
    }

    /**
     * Find One data in table filter by ID
     *
     * @param integer $id
     * @return boolean|static
     */
    public function find(int $id): bool|static
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE id = :id", [
                'id' => $id,
            ])->fetch()
        );
    }

    /**
     * Find data in database with dynamic filter
     *
     * @param array $filters
     * @return array
     */
    public function findBy(array $filters): array
    {
        // SELECT * FROM users WHERE lastName = :lastName AND firstName = :firstName

        $champs = [];
        $params = [];

        foreach ($filters as $key => $value) {
            $champs[] = "$key = :$key";
            $params[$key] = $value;
        }

        $champStr = implode(' AND ', $champs);

        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE $champStr", $params)->fetchAll()
        );
    }

    /**
     * Find one data in DB with dynamic filter
     *
     * @param array $filters
     * @return array|boolean|static
     */
    public function findOneBy(array $filters): array|bool
    {
        // SELECT * FROM users WHERE lastName = :lastName AND firstName = :firstName

        $champs = [];
        $params = [];

        foreach ($filters as $key => $value) {
            $champs[] = "$key = :$key";
            $params[$key] = $value;
        }

        $champStr = implode(' AND ', $champs);

        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE $champStr", $params)->fetch(),
        );
    }

    /**
     * Create data in Db
     *
     * @return PDOStatement|boolean|static
     */
    public function create(): PDOStatement|bool
    {
        // INSERT INTO users(firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)
        $champs = [];
        $markers = [];
        $params = [];

        // On boucle sur l'objet pour remplir dynamiquement les tableaux
        foreach ($this as $key => $value) {
            // On vérifie que la valeur n'est pas null et que la propriété
            // N'est pas table (Pas un champ en BDD)
            if ($key !== 'table' && $key !== 'db' && $value !== null) {
                $champs[] = $key;
                $markers[] = ":$key";

                if (is_array($value)) {
                    $params[$key] = json_encode($value);
                } elseif ($value instanceof DateTime) {
                    $params[$key] = $value->format('Y-m-d H:i:s');
                } else {
                    $params[$key] = $value;
                }
            }
        }

        // On transforme les tableaux en chaîne de caractères pour les intégrer
        // Dans la requête SQL
        $strChamps = implode(', ', $champs);
        $strMarkers = implode(', ', $markers);

        return $this->runQuery(
            "INSERT INTO $this->table($strChamps) VALUES ($strMarkers)",
            $params
        );
    }

    /**
     * Update Data in database
     *
     * @return PDOStatement|bool
     */
    public function update(): PDOStatement|bool
    {
        // UPDATE user SET firstName = :firstName, lastName = :lastName WHERE id = :id
        $champs = [];
        $params = [];

        foreach ($this as $key => $value) {
            if ($key !== 'table' && $key !== 'db' && $key !== 'id' && $value !== null) {
                $champs[] = "$key = :$key";

                if (is_array($value)) {
                    $params[$key] = json_encode($value);
                } elseif ($value instanceof DateTime) {
                    $params[$key] = $value->format('Y-m-d H:i:s');
                } else {
                    $params[$key] = $value;
                }
            }
        }

        $strChamps = implode(', ', $champs);

        /** @var User $this */
        $params['id'] = $this->id;

        return $this->runQuery(
            "UPDATE $this->table SET $strChamps WHERE id = :id",
            $params,
        );
    }

    /**
     * Delete a data from DB
     *
     * @return PDOStatement|boolean
     */
    public function delete(): PDOStatement|bool
    {
        // DELETE FROM users WHERE id = :id 
        /** @var User $this */
        return $this->runQuery(
            "DELETE FROM $this->table WHERE id = :id",
            ['id' => $this->id]
        );
    }

    /**
     * Méthode d'hydratation d'un objet à partir d'un tableau associatif
     *      $donnees = [
     *          'titre' => "Titre de l'objet",
     *          'description' => 'Desc',
     *          'actif' => true,
     *      ];
     * 
     *      RETOURNE:
     *          $article->setTitre('Titre de l'objet')
     *              ->setDescription('Desc')
     *              ->setActif(true);
     *
     * @param array|object $donnees
     * @return static
     */
    public function hydrate(array|object $data): static
    {
        // On boucle sur le tableau Data
        foreach ($data as $key => $value) {
            // On créé dynamiquement le nom du setter
            $setter = 'set' . ucfirst($key);

            // On vérifie que le setter exist dans l'objet
            if (method_exists($this, $setter)) {
                if ($key === 'roles') {
                    $value = $value ? json_decode($value) : null;
                    $this->$setter($value);
                } else {
                    $this->$setter($value);
                }
            }
        }

        return $this;
    }

    /**
     * Méthode pour transformer automatiquement les données transmises par PDO
     * en recherche DB. Transforme un object (StdClass) en instance de notre model (new User)
     *
     * @param mixed $query
     * @return static|array|boolean
     */
    public function fetchHydrate(mixed $query): static|array|bool
    {
        // On vérifie s'il y a plus d'un objet
        if (is_array($query) && count($query) > 0) {
            // Si plus d'un objet on boucle pour rénvoyer des objet Model (User ou Article)
            $data = array_map(function (object $object) {
                return (new static)->hydrate($object);
            }, $query);

            return $data;
            // Si un seul objet, on renvoie directement l'objet Model (User ou Article)
        } elseif (!empty($query)) {
            return (new static())->hydrate($query);
        } else {
            // Si false, on retourne directement la query
            return $query;
        }
    }

    /**
     * Execute SQL query in database
     *
     * @param string $sql
     * @param array|null $params
     * @return PDOStatement|boolean|static
     */
    protected function runQuery(string $sql, ?array $params = null): PDOStatement|bool
    {
        $this->db = Db::getInstance();

        if ($params) {
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($params);
        } else {
            // Requête simple
            $query = $this->db->query($sql);
        }

        return $query;
    }
}
