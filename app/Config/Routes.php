<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

// Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');
$routes->get('logout', 'Auth::logout');

// User Dashboard
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
});

// Admin Dashboard
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('audit-logs', 'Admin\Dashboard::auditLogs');
    $routes->get('history', 'Admin\History::index');
    
    // Researchers List CRUD
    $routes->group('researchers', function ($routes) {
        $routes->get('/', 'Admin\Researchers::index');
        $routes->get('high-school', 'Admin\Researchers::highSchool');
        $routes->get('college', 'Admin\Researchers::college');
        $routes->get('create', 'Admin\Researchers::create');
        $routes->post('store', 'Admin\Researchers::store');
        $routes->get('edit/(:num)', 'Admin\Researchers::edit/$1');
        $routes->post('update/(:num)', 'Admin\Researchers::update/$1');
        $routes->get('delete/(:num)', 'Admin\Researchers::delete/$1');
        $routes->post('add-category', 'Admin\Researchers::addCategory');
    });
    
    // User Management CRUD
    $routes->group('users', function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->get('create', 'Admin\Users::create');
        $routes->post('store', 'Admin\Users::store');
        $routes->get('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('update/(:num)', 'Admin\Users::update/$1');
        $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
    });
});
