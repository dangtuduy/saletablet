<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'signin';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['user/logout'] = 'signin/logout';
$route['user/login'] = 'signin/index';

$route['admin'] = 'account/usermng';
$route['admin/user'] = 'account/usermng';
$route['admin/useredit'] = 'account/usermng';
$route['user/profile'] = 'account/profile';
$route['user'] = 'account/index';
$route['admin/apisync'] = 'system/index';
$route['admin/(:any)'] = 'system/$1';
/*
$route['admin/syncitem'] = 'system/syncitem';
$route['admin/synccustomer'] = 'system/synccustomer';
$route['admin/syncorder'] = 'system/syncsalesext';
$route['admin/syncfocitem'] = 'system/syncfocitem';
$route['admin/syncitembrand'] = 'system/syncitembrand';
$route['admin/presync'] = 'system/presync';
*/
$route['salesgt/reports/revenue-by-month'] = 'sales/reports/3';
$route['salesgt/reports/revenue-one-customer'] = 'sales/reports/2';
$route['salesgt/reports/revenue-all-customer'] = 'sales/reports/4';
$route['salesgt/reports/revenue-now'] = 'sales/reports/1';
$route['salesgt/(:any)'] = 'sales/$1';
$route['salesgt/(:any)/(:any)'] = 'sales/$1/$2';
$route['salesgt/(:any)/(:any)/(:any)'] = 'sales/$1/$2/$3';
