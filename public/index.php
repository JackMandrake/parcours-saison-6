<?php

// POINT D'ENTRÉE UNIQUE : 
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)
require_once '../vendor/autoload.php';

session_start();

/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va 
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire

// dd($_SERVER);
if (array_key_exists('BASE_URI', $_SERVER)) {
    /**
     * la fonction PHP arrray_key_exists permet de s'assurer de l'existence
     * d'une clé nommée 'BASE_URI' dans la variable $_SERVER (Tableau associatif)
     * 
     * Si c'est le cas, alors on peut appeler la méthode setBasePath de AltoRouter
     * Cette méthode permet de dire à AltoRouter quelle est la partie "statique" du
     * site internet
     * 
     * Voir la docu de array_key_exists : https://www.php.net/manual/fr/function.array-key-exists.php
     */
    $router->setBasePath($_SERVER['BASE_URI']);
    // Alors on définit le basePath d'AltoRouter
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
}
// sinon
else {
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter, afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"

/**=====================
 * Routes générales
 *=======================*/
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-home'
);

/**=====================
 * Routes Teacher
 *=======================*/
$router->map(
    'GET',
    '/teacher/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-list'
);

$router->map(
    'GET',
    '/teacher/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-add'
);

$router->map(
    'POST',
    '/teacher/add',
    [
        'method' => 'create',
        'controller' => '\App\Controllers\TeacherController'
    ]
);

/**=====================
 * Routes Student
 *=======================*/
$router->map(
    'GET',
    '/student/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-list'
);

/* -------------
--- DISPATCH ---
--------------*/
/**
 * Le dispatching permet d'associer une route à une action particulière
 */

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();