<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthUsers');
$routes->setDefaultMethod('index');

$routes->get('/', 'AuthUsers::index');
$routes->post('register', 'AuthUsers::create');
$routes->put('/(:num)', 'AuthUsers::update/$1');
$routes->delete('/(:num)', 'AuthUsers::delete/$1');
$routes->post('login', 'Login::login');
$routes->get('me', 'Me::authUser');

// SLIDER
$routes->get('sliders', 'Slider::index');

// USER information
$routes->post('healthform', 'UserInfo::index');
$routes->get('getalluserinfo', 'UserInfo::getalluserinfo');

// BANNER
$routes->get('banners', 'Bannerinfo::index');


// SERVICES
$routes->get('services', 'Services::index');
$routes->get('serviceinfo/(:num)', 'Services::showservice/$1');
$routes->get('bookconsultation/(:num)', 'Services::bookconsultation/$1');
$routes->get('subscriptioninfo/(:num)', 'Subscription::index/$1');


//Watsapp Login 
$routes->post('watslogin', 'Login::watsapplogin');


// SCHEDULE APPOINTMENT
$routes->post('scheduleappointment', 'Scheduleappointment::index');


// MEAL PLANS
$routes->get('mealplans', 'Mealplans::index');


// COMMENTS
$routes->post('savecomments', 'Usercomments::index');
$routes->get('viewpost/(:num)', 'Usercomments::show/$1');

// READ FILE
$routes->get('getstarted', 'Fileread::index');

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------



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




if(file_exists(ROOTPATH.'admin')){
	$modulesPath = ROOTPATH.'admin/';

	$modules = scandir($modulesPath);

  
	foreach($modules as $module){
		if($module === '.' || $module === '..'|| $module === '.env' ) continue;
		if(is_dir($modulesPath) . '/' . $module){
			$routesPath = $modulesPath . $module . '/Config/Routes.php';
            // echo '<pre>';
            // print_r($routesPath);
            // exit;
			if(file_exists($routesPath)){
				require($routesPath);
			}
			else{
				continue;
			}
		}
	}
}