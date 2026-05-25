<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Lecture et Index
$routes->get('/', 'EvenementController::index');
$routes->get('evenements', 'EvenementController::index');
$routes->get('evenements/show/(:num)', 'EvenementController::show/$1');

// Création
$routes->get('evenements/create', 'EvenementController::create');
$routes->post('evenements/store', 'EvenementController::store');

// Modification
$routes->get('evenements/edit/(:num)', 'EvenementController::edit/$1');
$routes->post('evenements/update/(:num)', 'EvenementController::update/$1');

// Suppression
$routes->get('evenements/delete/(:num)', 'EvenementController::delete/$1');


// Pages d'authentification
$routes->get('login', 'AuthController::login');
$routes->post('auth/auth', 'AuthController::auth');
$routes->get('logout', 'AuthController::logout');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::store');

// Afficher le formulaire de réservation
$routes->get('inscriptions/formulaire/(:num)', 'InscriptionController::formulaire/$1');

// Traiter la réservation
$routes->post('inscriptions/reserver/(:num)', 'InscriptionController::reserver/$1');

// Page de confirmation après réservation
$routes->get('inscriptions/confirmation', 'InscriptionController::confirmation');

$routes->get('concerts', 'EvenementController::concerts');
$routes->get('festivals', 'EvenementController::festivals');
$routes->get('admin', 'EvenementController::admin');
$routes->get('contact', 'EvenementController::contact');
$routes->post('contact', 'EvenementController::contact');
