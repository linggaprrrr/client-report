<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login-proccess', 'Auth::loginProses');
$routes->get('/logout', 'Auth::logout');

// Admin Side
$routes->get('/admin/dashboard', 'Admin\Reports::index');
$routes->get('/admin/client-activities', 'Admin\Reports::clientActivities');
$routes->get('/admin/user-management', 'Admin\Users::index');
$routes->get('/admin/account-setting', 'Admin\Users::accountSetting');
$routes->post('/upload-report', 'Admin\Reports::uploadReport');
$routes->delete('/report/(:num)', 'Admin\Reports::deleteReport/$1');
$routes->post('/add-client', 'Admin\Users::addClient');
$routes->delete('/delete-client/(:num)', 'Admin\Users::deleteClient/$1');
$routes->get('/edit-client/(:num)', 'Admin\Users::editClient/$1');
$routes->post('/update-client', 'Admin\Users::updateClient');
$routes->get('/admin/p-and-l-report', 'Admin\Reports::plReport');
$routes->post('/upload-pl-report', 'Admin\Reports::uploadPLReport');
$routes->delete('/pl-report/(:num)', 'Admin\Reports::deletePLReport/$1');

// client side
$routes->get('/dashboard', 'Clients::index');
$routes->post('/dashboard', 'Clients::index');
$routes->get('/account-setting', 'Clients::accountSetting');
$routes->post('/update-setting', 'Clients::updateSetting');

$routes->get('/news', 'News::index');
$routes->get('/admin/news', 'News::news');
$routes->post('/create-news', 'News::createNews');
$routes->get('/news/(:num)', 'News::showNews/$1');
$routes->post('/update-news', 'News::updateNews');
$routes->delete('/delete-news/(:num)', 'News::deleteNews/$1');

$routes->get('/purchase-inventory', 'Clients::purchaseInventory');
$routes->get('/pl-report', 'Clients::plReport');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
