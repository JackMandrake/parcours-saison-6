<?php 

namespace App\Controllers;

use App\Models\Teacher;

class TeacherController extends CoreController
{
    /**
     * Page permettant d'afficher la liste des catégories
     *
     * @return void
     */
    public function list()
    {
        $teachers = Teacher::findAll();

        $dataToDisplay = [
            'teachers' => $teachers
        ];
        $this->show('teacher/list', $dataToDisplay);
    }

    public function add() {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller

        // On va appeler (require) le fichier views/category/add.tpl.php
        /**
         * Les méthodes add et create utilisent le même template (add.tpl.php)
         * 
         * Or dans la méthode create on passe en argument un objet $category, ce qui déclenchait une erreur lorsqu'on essayait d'afficher la page d'ajout en GET.
         * 
         * Solutions : 
         * 
         * 1) Créer des vues (templates) différents pour les méthodes
         * 2) Faire un test d'existence (isset :: is Set ? Est ce définit) de la variable $category dans le template add.tpl.php.
         * 
         * 3) Transmettre un objet vide (new Category()) à la vue depuis la méthode add (voir ci-dessous)
         * 
         */
        $this->show('teacher/add', [
            'teacher' => new Teacher(),
        ]);
    }

    public function create() {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $job = filter_input(INPUT_POST, 'job', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        $errorList = [];
        if (empty($firstname)) {
            $errorList[] = 'Vous devez saisir un prénom';
        }
                
        if (empty($lastname)) {
            $errorList[] = 'Vous devez saisir un nom';
        }
        
        if (empty($job)) {
            $errorList[] = 'Vous devez saisir un job';
        }

        if (empty($status)) {
            $errorList[] = 'Vous devez saisir un status';
        }
        
        // dump($_POST, $name, $subtitle, $picture, $errorList);

        // S'il n'y a aucune erreur dans les données...
        // Si le tableau d'erreur est vide
        if (empty($errorList)) {
            // On va créer notre catégorie


            // On crée un nouveau Model
            $newTeacher = new Teacher();

            // On renseigne les propriétés
            $newTeacher->setFirstname($firstname);
            $newTeacher->setLastname($lastname);
            $newTeacher->setJob($job);
            $newTeacher->setStatus($status);

            // On sauvergarde en DB
            if ($newTeacher->save()) {
            }

            // J'appelle la méthode insert qui va créer une requete d'insertion
            // de mes données en Base de données.
            $inserted = $newTeacher->insert();

            /// Si mes données ont correctement été ajoutées en base...
            if ($inserted) {
                // On va rediriger l'utilisateur vers une autre page, pour éviter
                // une double soumission du formulaire (Ex : double virement bancaire...)
                global $router;
                // header('Location: /category/list');
                header('Location: '. $router->generate('teacher-list'));
                return; // Le return permet un arrêt plus cordial du script PHP. exit = Claquer la porte au nez...Pas cool;
            } else {
                $errorList[] = 'L\'insertion des données s\'est mal passée';
            }
        }

        // S'il y a des erreurs dans les données ou l'insert...
        // Si le tableau d'erreur n'est pas (!) vide
        if (!empty($errorList)) {
            // On affiche les erreurs dans le formulaire de saisie d'une catégorie
            // On va appeler (require) le fichier views/category/add.tpl.php
            $teacher = new Teacher();
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            $this->show('teacher/add', [
                'errorList' => $errorList,
                'teacher' => $teacher
            ]);
        }

    }
}
