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
$routes->post('/find-username', 'Auth::findUsername');
$routes->get('/forgot-password', 'Auth::forgotPassword');
$routes->post('/forgot-password', 'Auth::forgotPasswordProcess');

// Admin Side
$routes->get('/admin/dashboard', 'Admin\Reports::index');
$routes->get('/admin/client-activities', 'Admin\Reports::clientActivities');
$routes->get('/admin/user-management', 'Admin\Users::index');
$routes->get('/admin/account-setting', 'Admin\Users::accountSetting');
$routes->post('/upload-report', 'Admin\Reports::uploadReport');
$routes->post('/upload-report-bulk', 'Admin\Reports::uploadReportBulk');
$routes->post('/assign-report-bulk', 'Admin\Reports::assignReportBulk');
$routes->delete('/report/(:num)', 'Admin\Reports::deleteReport/$1');
$routes->post('/add-client', 'Admin\Users::addClient');
$routes->delete('/delete-client/(:num)', 'Admin\Users::deleteClient/$1');
$routes->get('/edit-client/(:num)', 'Admin\Users::editClient/$1');
$routes->post('/update-client', 'Admin\Users::updateClient');
$routes->get('/admin/p-and-l-report', 'Admin\Reports::plReport');
$routes->post('/upload-pl-report', 'Admin\Reports::uploadPLReport');
$routes->delete('/pl-report/(:num)', 'Admin\Reports::deletePLReport/$1');
$routes->get('/admin/assignment-report', 'Admin\Reports::assignmentReport');
$routes->get('/admin/assignment-process', 'Admin\Reports::assignmentReportProcess');
$routes->post('/upload-assignment', 'Admin\Reports::assignmentReportSubmit');
$routes->get('/admin/checklist-report', 'Admin\Reports::checklistReport');
$routes->post('/admin/checklist-report-save', 'Admin\Reports::checklistReportSave');
$routes->post('/save-assignment', 'Admin\Reports::saveAssignmentReport');
$routes->post('/save-assignment-process', 'Admin\Reports::saveAssignmentProcess');
$routes->get('/reset-assignment', 'Admin\Reports::resetAssignment');
$routes->post('/update-price-item', 'Admin\Reports::updatePriceBox');
$routes->post('/save-box-details', 'Admin\Reports::saveBoxDetails');
$routes->post('/va/save-box-details', 'VA\Reports::saveBoxDetails');
$routes->get('/admin/assignment-completed', 'Admin\Reports::assignmentCompleted');
$routes->get('/admin/assignment-history', 'Admin\Reports::assignmentHistory');
$routes->post('/reset-password', 'Admin\Users::resetPassword');

$routes->get('/admin/completed-investments', 'Admin\Reports::completedInvestments');
$routes->post('/reupload-pl-report', 'Admin\Reports::reuploadPL');
$routes->post('/company-update', 'Admin\Users::updateCompanySetting');

$routes->post('/save-periode-setting', 'Admin\Reports::savePeriodSetting');
$routes->get('/admin/brand-approvals', 'Admin\Reports::brandApproval');
$routes->post('/reassign-box', 'Admin\Reports::reassignBox');
$routes->post('/upload-brand', 'Admin\Reports::uploadBrand');
$routes->post('/upload-brand-per-store', 'Admin\Reports::uploadBrandPerStore');
$routes->get('/admin/push-notification', 'News::getPushNotifications');
$routes->post('create-notification', 'News::pushNotification');
$routes->get('/admin/promocode', 'Admin\PromoCode::index');
$routes->get('/admin/logs', 'Logs::index');
$routes->post('/admin/logs', 'Logs::index');
$routes->get('/admin/generate-p-l', 'Admin\Reports::getGeneratePL');
$routes->post('admin/upload-generate-pl-report', 'Admin\Reports::uploadGeneratePL');
$routes->post('admin/save-chart', 'Admin\Reports::saveChart');
$routes->get('/admin/upc', 'UPC::index');
$routes->post('upload-upc', 'UPC::uploadUPC');
$routes->post('load-upc', 'UPC::loadUPC');
$routes->post('search-brand', 'Admin\Reports::searchBrand');
$routes->post('search-brand2', 'Admin\Reports::searchBrand2');
$routes->post('/add-promo','Admin\PromoCode::addPromo');

$routes->get('/admin/need-to-upload', 'UPC::needToUpload');
$routes->post('/create-need-to-upload', 'UPC::createNeedToUpload');
$routes->get('/resubmit-upc', 'UPC::refreshUnknownUPC');
$routes->get('/admin/extract-unlisted-upc', 'UPC::extractUnkownUPC');
$routes->get('/admin/extract-missing-upc/(:any)/(:any)', 'UPC::extractMissingUPC/$1/$2');
$routes->post('/change-box-category', 'UPC::changeBoxCategory');
$routes->post('/box-history-save', 'Admin\Reports::boxHistorySave');
$routes->get('/export-box/(:any)', 'Admin\Reports::exportBox/$1');
$routes->get('admin/search-brand', 'Admin\Reports::searchBrandPage');
$routes->post('/search-brand-history', 'Admin\Reports::brandHistory');
$routes->get('/send-email', 'Admin\Email::sendMail');
$routes->get('/export-all-report', 'Admin\Reports::exportAllReport');
$routes->get('/get-promocode', 'Admin\PromoCode::getPromocode');
$routes->post('/update-promocode', 'Admin\PromoCode::updatePromocode');

// client side
$routes->get('/get-started', 'Clients::getStarted');
$routes->get('/brand-approvals', 'Clients::brandApprovals');
$routes->get('/dashboard', 'Clients::index');
$routes->post('/dashboard', 'Clients::index');
$routes->get('/account-setting', 'Clients::accountSetting');
$routes->post('/update-setting', 'Clients::updateSetting');
$routes->get('download-receipt/(:any)', 'Clients::exportReceipt/$1');
$routes->get('download-receipt-elite/(:any)', 'Clients::exportReceiptElite/$1');


$routes->get('/news', 'News::index');
$routes->get('/admin/news', 'News::news');
$routes->post('/create-news', 'News::createNews');
$routes->get('/news/(:num)', 'News::showNews/$1');
$routes->post('/update-news', 'News::updateNews');
$routes->delete('/delete-news/(:num)', 'News::deleteNews/$1');
$routes->get('admin/company-setting', 'Admin\Users::companySetting');

$routes->get('/purchase-inventory', 'Clients::purchaseInventory');
$routes->get('/pl-report', 'Clients::plReport');
$routes->get('/test_json', 'Clients::test_json');
$routes->post('bulk-upload-pl-report', 'Admin\Reports::bulkUpload');


//va 
$routes->get('va/dashboard', 'VA\Reports::index');
$routes->get('/va/assignment-report', 'VA\Reports::assignmentReport');
$routes->get('/va/assignment-process', 'VA\Reports::assignmentReportProcess');
$routes->get('/va/assignment-completed', 'VA\Reports::assignmentCompleted');
$routes->get('/va/assignment-history', 'VA\Reports::assignmentHistory');
$routes->get('/reset-second-phase', 'VA\Reports::resetSecondPhase');
$routes->post('/create-box', 'VA\Reports::createNewBox');

// warehouse
$routes->get('/warehouse/scan-log', 'Warehouse\Reports::index');
$routes->get('/warehouse/history-box', 'Warehouse\Reports::historyBox');
$routes->get('/warehouse/input-item', 'Warehouse\Reports::inputItem');
$routes->post('/warehouse/input-manual', 'Warehouse\Reports::saveItem');
$routes->get('/warehouse/export-manual-input/(:any)/(:any)', 'Warehouse\Reports::exportDataInput/$1/$2');
$routes->get('/warehouse/upc/', 'UPC::clientUPC');
$routes->get('/warehouse/upc-database/', 'UPC::warehouseUPC');
$routes->get('/warehouse/manifest', 'UPC::clientManifest');
$routes->get('/warehouse/manifest/(:num)', 'UPC::clientManifest/$1');
$routes->post('load-client-upc', 'UPC::loadClientUPC');
$routes->post('load-client-upc/(:num)', 'UPC::loadClientUPC/$1');
$routes->post('load-client-manifest/(:num)', 'UPC::loadClientManifest/$1');
$routes->get('/export-manifest/(:num)', 'UPC::exportManifest/$1');

//json
$routes->get('/get-company/(:num)', 'Admin\Reports::getCompany/$1');
$routes->post('/update-link-spreadhsheet', 'Admin\Reports::updateLink');
$routes->post('/get-investment-client', 'Admin\Reports::getInvestmentClient');
$routes->post('/get-investment-client-all', 'Admin\Reports::getInvestmentClientAll');
$routes->post('/assign-box', 'Admin\Reports::assignBox');
$routes->get('get-box-summary', 'Admin\Reports::getBoxSummary');
$routes->get('get-box-summary-history', 'Admin\Reports::getBoxSummaryHistory');
$routes->post('save-fba-number', 'VA\Reports::saveFBANumber');
$routes->post('save-shipment-number', 'VA\Reports::saveShipmentNumber');
$routes->get('/get-category', 'Admin\Reports::getCategory');
$routes->get('/refresh-dashboard', 'Admin\Reports::refreshDashboard');
$routes->get('/get-piechart', 'Admin\Reports::getPiechart');
$routes->get('/get-plclient', 'Admin\Reports::getPLClient');
$routes->get('/get-summary-box', 'Admin\Reports::getSummaryBox');
$routes->get('/get-top-investments', 'Admin\Reports::getTopInvestment');
$routes->get('/get-top-continuity', 'Admin\Reports::getContinuityInvestment');
$routes->get('/get-top-readyassign', 'Admin\Reports::getTopReadyToAssign');
$routes->get('/get-total-cat', 'Admin\Reports::getTotalItemByCat');
$routes->get('/get-brands-client', 'Admin\Reports::getBrandClient');
$routes->get('/get-client-by-brand', 'Admin\Reports::getClientBrand');
$routes->post('/save-brand-client', 'Admin\Reports::saveClientBrand');
$routes->post('/add-brand', 'Admin\Reports::addBrand');
$routes->post('/rollback-assignment', 'Admin\Reports::rollbackAssignment');
$routes->get('/get-client-by-branddesc', 'Admin\Reports::getClientByDescBrand');
$routes->get('/get-pl-graph', 'Admin\Reports::getPLGraph');
$routes->get('/fb-click', 'Logs::fbClick');
$routes->get('/ig-click', 'Logs::igClick');
$routes->get('/yt-click', 'Logs::ytClick');
$routes->get('/in-click', 'Logs::inClick');
$routes->get('/chat-click', 'Logs::chatClick');
$routes->get('/credit-click', 'Logs::creditClick');
$routes->get('/search-upc', 'UPC::findUPC');
$routes->post('save-log', 'UPC::saveLog');
$routes->post('save-box', 'UPC::saveBox');

$routes->get('/export-log-login', 'Logs::exportLoginAct');
$routes->get('/export-log-login/(:any)', 'Logs::exportLoginAct/$1');
$routes->get('/export-log-page', 'Logs::exportPageAct');
$routes->get('/export-log-page/(:any)', 'Logs::exportPageAct/$1');
$routes->get('/export-log-social', 'Logs::exportSocialAct');
$routes->get('/export-log-social/(:any)', 'Logs::exportSocialAct/$1');

// mobile version
$routes->get('/mobile', 'Mobile::index');
$routes->post('/mobile-login-proccess', 'Mobile::loginProses');
$routes->get('/mobile/logout', 'Mobile::logout');

$routes->get('/mobile/dashboard', 'Mobile::dashboard');
$routes->post('/mobile/dashboard', 'Mobile::dashboard');
$routes->get('/mobile/account-setting', 'Mobile::accountSetting');
$routes->post('/mobile/update-setting', 'Mobile::updateSetting');

$routes->get('/mobile/get-started/(:num)', 'Mobile::getStarted');
$routes->get('/mobile/brand-approvals', 'Mobile::brandApprovals');
$routes->get('/mobile/purchase-inventory', 'Mobile::purchaseInventory');
$routes->get('/mobile/pl-report', 'Mobile::plReport');
$routes->get('/mobile/news', 'Mobile::news');

// API
$routes->get('/mobile/client-cost-left/(:num)', 'Mobile::getClientCostLeft/$1');
$routes->post('/api/send-device-token', 'News::sendDeviceToken');
$routes->get('/get-summary-pl', 'Admin\Reports::getSummaryPL');
$routes->get('/export-search/(:num)/(:any)', 'UPC::extractResult/$1/$2');
$routes->get('/export-search-all/(:any)', 'UPC::extractResultAll/$1');

// master
$routes->get('/master/manifest', 'Admin\Reports::master');
$routes->get('/master/manifest/(:num)', 'Admin\Reports::master/$1');
$routes->post('/master/manifest', 'Admin\Reports::master');
$routes->get('/master/pl-report/(:num)', 'Admin\Reports::masterPLReport/$1');


$routes->get('/mobile/master/manifest', 'Mobile::master');
$routes->get('/mobile/master/manifest/(:num)', 'Mobile::master/$1');
$routes->post('/mobile/master/manifest', 'Mobile::master');
$routes->get('/mobile/master/pl-report/(:num)', 'Mobile::masterPLReport/$1');


// android
$routes->get('/android', 'Android::index');
$routes->post('/android-login-proccess', 'Android::loginProses');
$routes->get('/android/logout', 'Android::logout');

$routes->get('/android/dashboard', 'Android::dashboard');
$routes->post('/android/dashboard', 'Android::dashboard');
$routes->get('/android/account-setting', 'Android::accountSetting');
$routes->post('/android/update-setting', 'Android::updateSetting');

$routes->get('/android/get-started/(:num)', 'Android::getStarted');
$routes->get('/android/brand-approvals', 'Android::brandApprovals');
$routes->get('/android/purchase-inventory', 'Android::purchaseInventory');
$routes->get('/android/pl-report', 'Android::plReport');
$routes->get('/android/news', 'Android::news');

$routes->get('/test-mail', 'Admin\Reports::sendMailPhase1');
$routes->post('/set-reminder', 'Clients::setReminder');
$routes->get('/push-reminder', 'Admin\Reports::pushReminder');
$routes->get('/get-reminder', 'Admin\Reports::getReminder');
$routes->post('/clear-reminder', 'Admin\Reports::clearReminder');

$routes->get('/how-amazon-payments-work', 'Clients::AmazonPayment');
$routes->get('/mobile/how-amazon-payments-work', 'Mobile::AmazonPayment');
$routes->get('/android/how-amazon-payments-work', 'Android::AmazonPayment');

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
