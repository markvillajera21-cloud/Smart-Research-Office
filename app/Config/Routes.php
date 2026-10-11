<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

// Auth Routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('(:segment)/login', 'Auth::departmentLogin/$1');
$routes->post('(:segment)/login', 'Auth::doDepartmentLogin/$1');
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
    $routes->post('settings', 'User\Dashboard::doSettings');
});

$routes->group('department', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Department\\Dashboard::index');
});

// Sub-Admin Dashboard
$routes->group('sub-admin', ['filter' => 'subadmin'], function ($routes) {
    $routes->get('dashboard', 'SubAdmin\Dashboard::index');
    $routes->get('profile', 'SubAdmin\Dashboard::profile');
    $routes->get('settings', 'SubAdmin\Dashboard::settings');
    $routes->post('settings', 'SubAdmin\Dashboard::doSettings');
});

// Admin Dashboard
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('profile', 'Admin\Dashboard::profile');
    $routes->get('settings', 'Admin\Dashboard::settings');
    $routes->post('settings', 'Admin\Dashboard::doSettings');
    $routes->get('history', 'Admin\History::index');
    $routes->get('projects', 'Admin\Projects::index');
    $routes->get('projects/create', 'Admin\Projects::create');
    $routes->get('uploads', 'Admin\Uploads::index', ['filter' => 'adminonly']);
    $routes->get('uploads/view/(:any)', 'Admin\Uploads::view/$1', ['filter' => 'adminonly']);
    $routes->get('generated-reports', 'Admin\Dashboard::generatedReports');

    // ===== 1. Research Scheduling & Calendar of Activities =====
    $routes->group('scheduling', function ($routes) {
        $routes->get('/', 'Admin\Scheduling::index');
        $routes->get('calendar', 'Admin\Scheduling::calendar');
        $routes->get('edit/(:num)', 'Admin\Scheduling::edit/$1');
        $routes->get('delete/(:num)', 'Admin\Scheduling::delete/$1');
        $routes->get('event/(:num)', 'Admin\Scheduling::ajaxGetEvent/$1');
    });

    $routes->group('pre-oral-scheduling', function ($routes) {
        $routes->get('/', 'Admin\PreOralScheduling::index');
        $routes->get('schedule-modal/(:num)', 'Admin\PreOralScheduling::ajaxGetScheduleModal/$1');
        $routes->get('edit/(:num)', 'Admin\PreOralScheduling::edit/$1');
        $routes->get('delete/(:num)', 'Admin\PreOralScheduling::delete/$1');
        $routes->get('event/(:num)', 'Admin\PreOralScheduling::ajaxGetEvent/$1');
        $routes->get('allowed-dates', 'Admin\PreOralScheduling::ajaxGetAllowedDates');
    });

    $routes->group('activities', function ($routes) {
        $routes->get('/', 'Admin\CalendarActivities::index');
        $routes->get('calendar', 'Admin\CalendarActivities::calendar');
        $routes->get('create', 'Admin\CalendarActivities::create');
        $routes->get('edit/(:num)', 'Admin\CalendarActivities::edit/$1');
        $routes->get('delete/(:num)', 'Admin\CalendarActivities::delete/$1');
        $routes->get('allowed-dates', 'Admin\CalendarActivities::ajaxGetAllowedDates');
    });

    // ===== 2. Research Title Management =====
    $routes->group('research-titles', function ($routes) {
        $routes->get('/', 'Admin\ResearchTitles::index');
        $routes->get('approve/(:num)', 'Admin\ResearchTitles::approve/$1');
        $routes->get('reject/(:num)', 'Admin\ResearchTitles::reject/$1');
        $routes->get('for-revision/(:num)', 'Admin\ResearchTitles::forRevision/$1');
        $routes->get('mark-completed/(:num)', 'Admin\ResearchTitles::markCompleted/$1');
        $routes->get('ajax-abstract/(:num)', 'Admin\ResearchTitles::ajaxGetAbstract/$1');
        $routes->get('edit/(:num)', 'Admin\ResearchTitles::edit/$1');
        $routes->get('delete/(:num)', 'Admin\ResearchTitles::delete/$1');
        $routes->post('check-duplicate', 'Admin\ResearchTitles::checkDuplicateAjax');
    });

    // ===== 3. Research Monitoring =====
    $routes->group('monitoring', function ($routes) {
        $routes->get('/', 'Admin\Monitoring::index');
        $routes->get('completed', 'Admin\Monitoring::completed');
        $routes->get('incomplete', 'Admin\Monitoring::incomplete');
        $routes->get('pre-oral-summary', 'Admin\Monitoring::preOralSummary');
        $routes->get('pre-oral-list', 'Admin\Monitoring::preOralList');
    });

    // ===== 4. Evaluation and Remarks =====
    $routes->group('evaluations', function ($routes) {
        $routes->get('/', 'Admin\Evaluations::index');
        $routes->post('store', 'Admin\Evaluations::store');
        $routes->get('mark-compliance/(:num)', 'Admin\Evaluations::markComplianceSubmitted/$1');
        $routes->get('delete/(:num)', 'Admin\Evaluations::delete/$1');
    });

    // ===== 8. Reports =====
    $routes->group('reports', function ($routes) {
        $routes->get('/', 'Admin\Reports::index');
        $routes->get('schedule', 'Admin\Reports::generateScheduleReport');
        $routes->get('status', 'Admin\Reports::generateStatusReport');
        $routes->get('completion', 'Admin\Reports::generateCompletionReport');
        $routes->get('researchers', 'Admin\Reports::generateResearcherReport');
        $routes->get('history', 'Admin\Reports::history');
        $routes->get('export', 'Admin\Reports::export');
    });

    // Research CRUD - GET only for archive_viewer
    $routes->group('researchers', function ($routes) {
        $routes->get('/', 'Admin\Researchers::index');
        $routes->get('high-school', 'Admin\Researchers::highSchool');
        $routes->get('college', 'Admin\Researchers::college');
        $routes->get('research-teachers', 'Admin\Researchers::researchTeachers');
        $routes->get('grammarians', 'Admin\Researchers::grammarians');
        $routes->get('statisticians', 'Admin\Researchers::statisticians');
        $routes->get('advisers', 'Admin\Researchers::advisers');
        $routes->get('create', 'Admin\Researchers::create');
        $routes->get('edit/(:num)', 'Admin\Researchers::edit/$1');
        $routes->get('update-status', 'Admin\Researchers::updateStatusPage');
        $routes->get('ajax-abstract/(:num)', 'Admin\Researchers::ajaxGetAbstract/$1');
        $routes->get('delete/(:num)', 'Admin\Researchers::delete/$1');
    });
    
    // User Management CRUD - Only for Admin
    $routes->group('users', ['filter' => 'adminonly'], function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->get('create', 'Admin\Users::create');
        $routes->get('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
    });

    // ===== 6. Expert Registration Pool =====
    $routes->group('experts', function ($routes) {
        $routes->get('/', 'Admin\Experts::index');
        $routes->get('create', 'Admin\Experts::create');
        $routes->get('edit/(:num)', 'Admin\Experts::edit/$1');
        $routes->get('toggle/(:num)', 'Admin\Experts::toggle/$1');
        $routes->get('delete/(:num)', 'Admin\Experts::delete/$1');
    });
    
    // Admin-only POST/PUT/DELETE routes
    $routes->group('', ['filter' => 'adminonly'], function ($routes) {
        $routes->post('projects/store', 'Admin\Projects::store');
        $routes->post('uploads/store', 'Admin\Uploads::store');
        $routes->post('uploads/delete', 'Admin\Uploads::delete');

        // ===== Scheduling POST routes =====
        $routes->group('scheduling', function ($routes) {
            $routes->post('store', 'Admin\Scheduling::store');
            $routes->post('update/(:num)', 'Admin\Scheduling::update/$1');
            $routes->post('change-committee/(:num)', 'Admin\Scheduling::changeCommittee/$1');
        });

        // ===== Pre-Oral Scheduling POST routes =====
        $routes->group('pre-oral-scheduling', function ($routes) {
            $routes->post('store', 'Admin\PreOralScheduling::store');
            $routes->post('update/(:num)', 'Admin\PreOralScheduling::update/$1');
            $routes->post('add-calendar-schedule', 'Admin\PreOralScheduling::ajaxAddCalendarSchedule');
        });

        // ===== Activities POST routes =====
        $routes->group('activities', function ($routes) {
            $routes->post('store', 'Admin\CalendarActivities::store');
            $routes->post('update/(:num)', 'Admin\CalendarActivities::update/$1');
        });

        // ===== Research Titles POST routes =====
        $routes->group('research-titles', function ($routes) {
            $routes->post('store', 'Admin\ResearchTitles::store');
            $routes->post('update/(:num)', 'Admin\ResearchTitles::update/$1');
            $routes->post('reject/(:num)', 'Admin\ResearchTitles::reject/$1');
            $routes->post('for-revision/(:num)', 'Admin\ResearchTitles::forRevision/$1');
            $routes->post('submit-abstract/(:num)', 'Admin\ResearchTitles::submitAbstract/$1');
            $routes->post('assign-teacher/(:num)', 'Admin\ResearchTitles::assignTeacher/$1');
        });

        // ===== Monitoring POST routes =====
        $routes->post('monitoring/update-stage', 'Admin\Monitoring::updateStage');
        
        // Research CRUD - POST/DELETE
        $routes->group('researchers', function ($routes) {
            $routes->post('store', 'Admin\Researchers::store');
            $routes->post('update/(:num)', 'Admin\Researchers::update/$1');
            $routes->post('update-status/(:num)', 'Admin\Researchers::updateStatus/$1');
            $routes->post('save-status/(:num)', 'Admin\Researchers::saveStatus/$1');
            $routes->post('submit-abstract/(:num)', 'Admin\Researchers::submitAbstract/$1');
            $routes->post('assign-teacher/(:num)', 'Admin\Researchers::assignTeacher/$1');
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
            $routes->post('add-statistician', 'Admin\Researchers::addStatistician');
            $routes->post('edit-statistician', 'Admin\Researchers::editStatistician');
            $routes->post('delete-statistician/(:num)', 'Admin\Researchers::deleteStatistician/$1');
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

        // Expert Registration Pool - POST/DELETE
        $routes->group('experts', function ($routes) {
            $routes->post('store', 'Admin\Experts::store');
            $routes->post('update/(:num)', 'Admin\Experts::update/$1');
        });
    });
    
    // Audit logs accessible by both admin and archive viewer
    $routes->get('audit-logs', 'Admin\Dashboard::auditLogs');
});

$routes->group('teacher', ['filter' => 'teacher'], function ($routes) {
    $routes->get('dashboard', 'Teacher\Dashboard::index');
    $routes->get('students', 'Teacher\Dashboard::students');
    $routes->get('schedules', 'Teacher\Dashboard::schedules');
    $routes->get('titles', 'Teacher\Dashboard::titles');
    $routes->get('evaluations', 'Teacher\Dashboard::evaluations');
    $routes->post('titles/update-status/(:num)', 'Teacher\Dashboard::updateTitleStatus/$1');
});

// ===== 6. Student Features =====
$routes->group('student', ['filter' => 'student'], function ($routes) {
    $routes->get('dashboard', 'Student\Dashboard::index');
    $routes->get('my-schedules', 'Student\Dashboard::mySchedules');
    $routes->get('my-titles', 'Student\Dashboard::myTitles');
    $routes->get('submit-title', 'Student\Dashboard::submitTitle');
    $routes->get('search-titles', 'Student\Dashboard::searchTitles');
    $routes->get('completed-researches', 'Student\Dashboard::completedResearches');
    $routes->get('view-abstract/(:num)', 'Student\Dashboard::viewAbstract/$1');
    $routes->get('expert-pool', 'Student\Dashboard::expertPool');
    $routes->post('submit-title/store', 'Student\Dashboard::storeTitle');
    $routes->post('submit-abstract/(:num)', 'Student\Dashboard::submitAbstract/$1');
});
