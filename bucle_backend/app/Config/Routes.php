<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Permitir OPTIONS para todas las rutas del grupo para evitar el error 404 en preflight
    $routes->options('(:any)', 'CategoriaController::index');

    // Rutas de Categorías
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias', 'CategoriaController::create');
    $routes->get('categorias/schema/(:num)', 'CategoriaController::getSchema/$1');
    
    // Rutas de Subcategorías (Eventos)
    $routes->get('subcategorias/(:num)', 'SubcategoriaController::filterByCategory/$1');
    $routes->put('subcategorias/(:num)', 'SubcategoriaController::update/$1');
    $routes->post('subcategorias', 'SubcategoriaController::create');
});