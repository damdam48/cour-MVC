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
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table")->fetchAll()

        );
    }

    /**
     * find One data in table filter by ID
     *
     * @param integer $id
     * @return bool|static
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
     * find data in datebasez with dynamic filter
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
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE $champStr", $params)->fetchAll()

        );
    }



    /**
     * find one entry in datebase with dynamic filter
     *
     * @param array $filters
     * @return array|bool
     */
    public function findOneBy(array $filters): array|BOOL
    {
        $champs = [];
        $params = [];

        foreach ($filters as $key => $value) {
            $champs[] = "$key = :$key";
            $params[$key] = $value;
        }
        $champStr = implode(' AND ', $champs);
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE $champStr", $params)->fetch()

        );
    }


    /**
     * create
     *
     * @return PDOStatement|boolean
     */
    public function create(): PDOStatement|bool
    {
        // INSERT INTO users(firsName, lastName, email, password) VALUE (:firsName, :lastName, :email, :password)
        $champs = [];
        $markers = [];
        $params = [];

        // on boucle sur l'objet pour remplis dynamiquement les tableaux
        foreach ($this as $key => $value) {
            // on verifie que la valeur n'est pas null et que la propriété
            //n'est pas table (pas un champ en BDD)
            if ($key !== 'table' && $value !== null) {
                $champs[] = $key;
                $markers[] = ":$key";
                $params[$key] = $value;
            }
        }
        //on transforme les tableaux en chaine de caractère pour les integrer
        // dnas la requete sql
        $strChamps = implode(', ', $champs);
        $strMarkers = implode(', ', $markers);
        var_dump($strChamps, $strMarkers);

        return $this->runQuery(
            "INSERT INTO $this->table($strChamps) VALUES ($strMarkers)",
            $params,
        );
    }


    /**
     * Update date in database
     *
     * @return PDOStatement|boolean
     */
    public function update(): PDOStatement|bool
    {
        // UPDATE user SET firName = :firName, lastName = :lastName WHERE id = :id
        $champs = [];
        $params = [];

        foreach ($this as $key => $value) {
            if ($key !== 'table' && $key !== 'id' && $value !== null) {
                $champs[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        $strChamps = implode(', ', $champs);

        /**
         * @var User $this
         */
        $params['id'] = $this->id;
        return $this->runQuery(
            "UPDATE $this->table SET $strChamps WHERE id = :id",
            $params,
        );
    }

    /**
     * DELETE a data from DB
     *
     * @return PDOStatement|boolean
     */
    public function delete(): PDOStatement|bool
    {
        // delete from users WHERE id = :id
        /**
         * @var User $this
         */
        return $this->runQuery(
            "DELETE FROM $this->table WHERE id = :id",
            ['id' => $this->id]
        );
    }


    /**
     * methode pour transformer automatique les donnees transmises par PDO
     * en recherche DB. transforme un objet (StdClass) en instance de notre model(new User) 
     *
     * @param mixed $query
     * @return static|array|boolean
     */
    public function fetchHydrate(mixed $query): static|array|bool
    {
        if (is_array($query) && count($query) > 1) {

            $data = array_map(function (object $object) {
                return (new static)->hydrate($object);
            }, $query);

            return $data;
        } elseif (!empty($query)) {
            return (new static())->hydrate($query);
        } else {
            return $query;
        }
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
        // on boucle sur le tabeau data
        foreach ($data as $key => $value) {
            // on crer dynamiquement le nom du seter
            $setter = 'set' . ucfirst($key);
            // on verifie que le setter exit dans l'objet
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
        return $this;
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
