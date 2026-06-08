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
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::doForgotPassword');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password', 'Auth::doResetPassword');

// User Dashboard
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('profile', 'User\Dashboard::profile');
    $routes->get('settings', 'User\Dashboard::settings');
});

// Admin Dashboard
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('profile', 'Admin\Dashboard::profile');
    $routes->get('settings', 'Admin\Dashboard::settings');
    $routes->get('history', 'Admin\History::index');
    $routes->get('projects', 'Admin\Projects::index');
    $routes->get('projects/create', 'Admin\Projects::create');
    $routes->get('uploads', 'Admin\Uploads::index');
    $routes->get('uploads/view/(:any)', 'Admin\Uploads::view/$1');
    
    // Research CRUD - GET only for archive_viewer
    $routes->group('researchers', function ($routes) {
        $routes->get('/', 'Admin\Researchers::index');
        $routes->get('high-school', 'Admin\Researchers::highSchool');
        $routes->get('college', 'Admin\Researchers::college');
        $routes->get('create', 'Admin\Researchers::create');
        $routes->get('edit/(:num)', 'Admin\Researchers::edit/$1');
        $routes->get('update-status', 'Admin\Researchers::updateStatusPage');
        $routes->get('delete/(:num)', 'Admin\Researchers::delete/$1');
    });
    
    // User Management CRUD - GET only for archive_viewer
    $routes->group('users', function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->get('create', 'Admin\Users::create');
        $routes->get('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
    });
    
    // Admin-only POST/PUT/DELETE routes
    $routes->group('', ['filter' => 'adminonly'], function ($routes) {
        $routes->post('projects/store', 'Admin\Projects::store');
        $routes->post('uploads/store', 'Admin\Uploads::store');
        $routes->post('uploads/delete', 'Admin\Uploads::delete');
        
        // Research CRUD - POST/DELETE
        $routes->group('researchers', function ($routes) {
            $routes->post('store', 'Admin\Researchers::store');
            $routes->post('update/(:num)', 'Admin\Researchers::update/$1');
            $routes->post('update-status/(:num)', 'Admin\Researchers::updateStatus/$1');
            $routes->post('save-status/(:num)', 'Admin\Researchers::saveStatus/$1');
            $routes->post('add-category', 'Admin\Researchers::addCategory');
            $routes->post('edit-category', 'Admin\Researchers::editCategory');
            $routes->post('delete-category/(:num)', 'Admin\Researchers::deleteCategory/$1');
            $routes->post('add-designation', 'Admin\Researchers::addDesignation');
            $routes->post('edit-designation', 'Admin\Researchers::editDesignation');
            $routes->post('delete-designation/(:num)', 'Admin\Researchers::deleteDesignation/$1');
            $routes->post('add-schoolYear', 'Admin\Researchers::addSchoolYear');
            $routes->post('edit-schoolYear', 'Admin\Researchers::editSchoolYear');
            $routes->post('delete-schoolYear/(:num)', 'Admin\Researchers::deleteSchoolYear/$1');
            $routes->post('add-strand', 'Admin\Researchers::addStrand');
            $routes->post('edit-strand', 'Admin\Researchers::editStrand');
            $routes->post('delete-strand/(:num)', 'Admin\Researchers::deleteStrand/$1');
            $routes->post('add-course', 'Admin\Researchers::addCourse');
            $routes->post('edit-course', 'Admin\Researchers::editCourse');
            $routes->post('delete-course/(:num)', 'Admin\Researchers::deleteCourse/$1');
            $routes->post('add-status', 'Admin\Researchers::addStatus');
            $routes->post('edit-status', 'Admin\Researchers::editStatus');
            $routes->post('delete-status/(:num)', 'Admin\Researchers::deleteStatus/$1');
            $routes->post('add-adviser', 'Admin\Researchers::addAdviser');
            $routes->post('edit-adviser', 'Admin\Researchers::editAdviser');
            $routes->post('delete-adviser/(:num)', 'Admin\Researchers::deleteAdviser/$1');
            $routes->post('add-grammarian', 'Admin\Researchers::addGrammarian');
            $routes->post('edit-grammarian', 'Admin\Researchers::editGrammarian');
            $routes->post('delete-grammarian/(:num)', 'Admin\Researchers::deleteGrammarian/$1');
            $routes->post('add-remark', 'Admin\Researchers::addRemark');
            $routes->post('edit-remark', 'Admin\Researchers::editRemark');
            $routes->post('delete-remark/(:num)', 'Admin\Researchers::deleteRemark/$1');
            $routes->post('add-researchTeacher', 'Admin\Researchers::addResearchTeacher');
            $routes->post('edit-researchTeacher', 'Admin\Researchers::editResearchTeacher');
            $routes->post('delete-researchTeacher/(:num)', 'Admin\Researchers::deleteResearchTeacher/$1');
            $routes->post('add-abstract', 'Admin\Researchers::addAbstract');
            $routes->post('edit-abstract', 'Admin\Researchers::editAbstract');
            $routes->post('delete-abstract/(:num)', 'Admin\Researchers::deleteAbstract/$1');
            $routes->post('add-defense-status', 'Admin\Researchers::addDefenseStatus');
            $routes->post('edit-defense-status', 'Admin\Researchers::editDefenseStatus');
            $routes->post('delete-defense-status/(:num)', 'Admin\Researchers::deleteDefenseStatus/$1');
        });
        
        // User Management CRUD - POST/DELETE
        $routes->group('users', function ($routes) {
            $routes->post('store', 'Admin\Users::store');
            $routes->post('update/(:num)', 'Admin\Users::update/$1');
        });
    });
    
    // Audit logs accessible by both admin and archive viewer
    $routes->get('audit-logs', 'Admin\Dashboard::auditLogs');
});
