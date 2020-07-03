<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Student extends CoreModel
{
    public static function find($id)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM student
            WHERE id = ' . $id;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject(self::class);
        
        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table teacher
     *
     * @return Teacher[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `student`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    public function insert()
    {
        // 1) Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // 2) Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `category` (name, subtitle, picture, home_order)
            VALUES (:name, :subtitle, :picture, :home_order)
        ";

        /**
         * 3) On va déléguer le traitement des données du formulaire à PDO, pour
         * éviter les injections SQL.
         * 
         * La méthode PDO::prepare — Prépare une requête à l'exécution et retourne un objet
         */
        $query = $pdo->prepare($sql);

        // 4) Execution de la requête d'insertion
        // On peut utiliser la méthode bindValue pour chaque input 
        // qui va préparer nos données : Associer une valeur à un paramètre (Ex :name ==> $this->name)
        // Voir la documentation ici : https://www.php.net/manual/fr/pdostatement.bindvalue.php
        // $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        // $query->bindValue(':price', $this->price, PDO::PARAM_INT);

        // La méthode binValue nous obligerait quand même à appeler plus tard la méthode execute.
        // (Voir la déclaration de la méthode insert du modèle Product)
        // Exemple complet
        // $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        // $query->bindValue(':subtitle', $this->price, PDO::PARAM_STR);
        // ...
        // $query->execute();
        // ...
        // ...On peut faire ça (en deux étapes : binValue + execute)....Ou alors

        /**
         * On envoie les données « brutes (parce que provenant du client, dont on a pas confiance) » 
         * à execute() en arguments, qui va les "sanitize" pour SQL, tout en executant la requete. 
         * 
         * C'est la méthode TOUT en UN (couteau suisse)
         */

        $query->execute([
            ':name' => $this->name,
            ':subtitle' => $this->subtitle,
            ':home_order'=>$this->home_order,
            ':picture' => $this->picture
        ]);
        
        // On récupère le nombre d'élements affectés par la requete. 
        // Puisqu'on qu'on insert q'une seule
        // données à la fois, on aura toujours $insertedRows = 1.
        $insertedRows = $query->rowCount();

        // Si au moins une ligne ajoutée
        if ($insertedRows === 1) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE : avec des alias pour empécher les injections SQL
        $sql = "
            UPDATE `category`
            SET
                name = :name,
                subtitle = :subtitle, 
                picture = :picture,                
                home_order = :home_order,
                updated_at = NOW()
            WHERE id = :id
        ";

        $query = $pdo->prepare($sql);

        $query->execute([
            ':name' => $this->name,
            ':subtitle' => $this->subtitle,
            ':picture' => $this->picture,
            ':home_order' => $this->home_order,
            ':id' => $this->id,
        ]);


        $updatedRows = $query->rowCount();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($updatedRows > 0);
    }

    /**
     * Get the value of Firstname
     *
     * @return  string
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of Firstname
     *
     * @param  string  $Firstname
     *
     * @return  self
     */ 
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of Lastname
     *
     * @return  string
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of Lastname
     *
     * @param  string  $Lastname
     *
     * @return  self
     */ 
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of Job
     *
     * @return  string
     */ 
    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    /**
     * Set the value of Job
     *
     * @param  string  $Job
     *
     * @return  self
     */ 
    public function setTeacherId(string $teacherId)
    {
        $this->teacher_id = $teacherId;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     *
     * @return  self
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }
}