<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
//App\Controllers
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('index');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

// $routes->get('/', 'Auth::index',['filter' => 'authenticated']);

$routes->get('/', 'Auth::index', ['filter' => 'authenticated']);

// $routes->get('login', 'App\Controllers\Index::index');

// $routes->group('/admin', ['filter'=>'authenticate'], static function($routes){
//     $routes->match(['post'], '/login', 'index::index');
// });

$routes->group('admin', static function ($routes) {
    $routes->match(['post'], 'dashboard', 'Index::index');
    $routes->match(['post'], 'savenewroles', 'Index::savenewroles');
    $routes->match(['post'], 'editnewroles', 'Index::editnewroles');
    $routes->get('Admindashboard', 'Index::dashboard');
    $routes->get('adminlogout', 'Auth::logout');
    $routes->get('addrole', 'Index::addrole');
    $routes->get('addNew', 'Index::addNew');
    $routes->get('addnewroles', 'Index::addnewroles');
    $routes->get('user_rolesedit/(:segment)', 'Index::user_rolesedit/$1/$2');
    $routes->get('user_delete/(:any)', 'Index::user_delete/$1');
    $routes->get('addemployee', 'Index::addemployee');
    $routes->get('user_edit/(:any)', 'Index::user_edit/$1/$2');
    $routes->get('access/(:any)', 'Index::access/$1');
    $routes->get('permission/(:any)', 'Index::permission/$1');
    $routes->get('guest_list', 'Index::guest_list');
    $routes->match(['post'], 'editnewuser', 'Index::editnewuser/$1');
    $routes->match(['post'], 'addnewusers', 'Index::addnewuser/$1');
    $routes->match(['post'], 'saveaccess', 'Index::saveaccess');
    $routes->match(['post'], 'savepermission', 'Index::savepermission');

    
    // Transaction Details
    $routes->get('transactiondetails', 'Transactiondetails');
    $routes->match(['post'], 'savetransactiondetails', 'Transactiondetails::savetransactiondetails');
	$routes->get('transactionlist', 'Transactiondetails::transactionlist');
	
	
	//Forward Cover Details
	 $routes->get('forwardcoverdetails', 'ForwardCoverdetails');
	 $routes->match(['post'], 'saveforwardcoverdetails', 'ForwardCoverdetails::saveforwardcoverdetails');
	// Currency Sold And Brought
	$routes->match(['post'], 'dependantcurrency', 'ForwardCoverdetails::dependantcurrency'); 
	 
	 //Forward Cancellation Details
	 $routes->get('forwardcancellationutilizationdetails', 'ForwardCancellation');
	 $routes->match(['post'], 'saveforwardcancellationdetails', 'ForwardCancellation::saveforwardcancellationdetails');
	 $routes->match(['post'], 'cancelationforwardamount', 'Cancelationforwardamount');
	 
	 //CURRENCY  
	 $routes->get('currencylist', 'Currency');
	 $routes->get('addNewCurrencyList', 'Currency::addNewCurrency');
	 $routes->match(['post'], 'savenewcurrency', 'Currency::savenewcurrency');
	 $routes->get('edit_currency/(:any)', 'Currency::edit_currency/$1');
	 $routes->get('delete_currency/(:any)', 'Currency::delete_currency/$1');
	 
	 
	 //Exposure
	  $routes->get('exposure_type_list', 'Exposure');
	  $routes->get('edit_exposure/(:any)', 'Exposure::edit_exposure/$1');
	  $routes->get('addNewExposureList', 'Exposure::addNewExposureList');
	  $routes->match(['post'], 'savenewexposure', 'Exposure::savenewexposure');
	  $routes->get('delete_exposure/(:any)', 'Exposure::delete_exposure/$1');
	  
	  
	  //Payment Receipt Details
	  $routes->get('paymentreceiptdetails', 'PaymentReceiptdetails');

	  
	  //Payment Receipt Details Dependable Dropdown
	  $routes->match(['post'], 'paymentreceiptdetailsdependant', 'PaymentReceiptdetailsdependant');
	  $routes->match(['post'], 'savepaymentreceiptdetails', 'PaymentReceiptdetails::savepaymentreceiptdetails');
	  
	  //New Dependable Dropdown
	   $routes->match(['post'], 'dependantdropdowns', 'PaymentReceiptdetailsdependant::dependantdropdowns');
	
	// Counter Party List
	 $routes->get('counter_party', 'Counterparty');
	 $routes->get('addNewCounterList', 'Counterparty::addNewCounterpartyList');
	 $routes->match(['post'], 'savenewcounterparty', 'Counterparty::savenewCounterparty');
	 $routes->get('edit_counterparty/(:any)', 'Counterparty::edit_counterparty/$1');
	 $routes->get('delete_counterparty/(:any)', 'Counterparty::delete_counterparty/$1');
	 
	 
	 // BANK MASTER 
	 $routes->get('banklist', 'Bank');
	 $routes->get('addNewBankList', 'Bank::addNewBankList');
	 $routes->match(['post'], 'savenewbank', 'Bank::savenewbank');
	 $routes->get('edit_bank/(:any)', 'Bank::edit_bank/$1');
	 $routes->get('delete_bank/(:any)', 'Bank::delete_bank/$1');
	 
	 //MTM Operating Risk
	 $routes->get('mtm_operating_risk', 'MtmOperatingrisk');
	 
	 //Exposure Summary
	 $routes->get('exposure_summary', 'Exposuresummary');
	 
	 // Helicopter View
	 $routes->get('helicopterview', 'Helicopterview');

});

// $routes->get("list-user", "Main::addCity", ["as" => "users"]); // Named route

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
