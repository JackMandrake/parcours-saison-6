<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models

// On ne veut pas donner la possibilité d'instancier la class CoreModel
// Pour cela, on utilise le mot-clé "abstract" ==> comme pour dire que CoreModel est
// une maison témoin (on est pas censé y habiter)
/**
 * Ce n'est pas la recette qui se mange, mais celle-ci contient une liste de recommandations pour réaliser le plat désiré.  
 * Les étapes de la recette doivent être appliqués à la cuisson courante, 
 * dans le strict respect des recommandations (trop sel ou de sucre et c'est la catastrophe assuré !! 😉  ).
 * 
 * De même, on ne veut pas instancier CoreModel (la recette), 
 * par contre on va y déclarer des méthodes abstraites (étapes de la recette), 
 * qui devront être créées dans les classes enfants (une étape d'une recette de cuisine cesse d'être abstraite lorsqu'on l'applique : on passe des paroles à l'acte).
 */
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;
    /**
     * Get the value of id
     *
     * @return  int
     */ 

    // j'oblige mes enfants à avoir une méthode insert
    abstract public function insert();

    // j'oblige mes enfants à avoir une méthode update
    abstract public function update();

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */ 
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @param  string  $created_at
     *
     * @return  self
     */ 
    public function setCreated_at(string $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */ 
    public function getUpdated_at()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @param  string  $updated_at
     *
     * @return  self
     */ 
    public function setUpdated_at(string $updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Méthode permettant la sauvegarde ou l'ajout d'une donnée
     * en base
     *
     * @return void
     */
    public function save()
    {
        if (is_null($this->id)) {
            $queryExecuted = $this->insert();
        } else {
            $queryExecuted = $this->update();
        }

        return $queryExecuted;
    }

}