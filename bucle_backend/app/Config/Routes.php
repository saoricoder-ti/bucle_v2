<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // Rutas de Configuración Dinámica
    $routes->get('config/schema/(:segment)', 'EntidadController::getSchema/$1');
    
    // Rutas de Categorías
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias', 'CategoriaController::create');
    
    // Rutas de Subcategorías
    $routes->get('subcategorias/(:num)', 'SubcategoriaController::filterByCategory/$1');
    $routes->put('subcategorias/(:num)', 'SubcategoriaController::update/$1');
    $routes->post('subcategorias', 'SubcategoriaController::create');

    // Rutas de Gestión de Entidades (Legacy/General)
    $routes->get('entidades', 'EntidadController::index');
    $routes->get('entidades/categorias', 'EntidadController::getCategories');
    $routes->get('entidades/subcategorias/(:segment)', 'EntidadController::getSubcategories/$1');
    $routes->post('entidades/registrar', 'EntidadController::store');
    $routes->get('entidades/detalle/(:segment)', 'EntidadController::show/$1');
    $routes->put('entidades/update-blocks/(:segment)', 'EntidadController::updateBlocks/$1');
});