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
/*$route['default_controller'] = 'welcome';
$route['404_override'] = '';*/
$route['translate_uri_dashes'] = FALSE;

$route['booking/bookings'] = 'booking/bookings';
$route['booking/booking_edit'] = 'booking/booking_add_form';
$route['booking/booking_add_form'] = 'booking/booking_add_form';
$route['booking/booking_add'] = 'booking/booking_add';
$route['booking/bookings_show'] = 'booking/bookings_show';

$route['base/hotelsadd_job'] = 'base/hotelsadd_job';
$route['base/hotelsadd'] = 'base/hotelsadd';
$route['base/hotelsmaintain'] = 'base/hotelsmaintain';
$route['base/show_busy'] = 'base/show_busy';
$route['base/show_busy2'] = 'base/show_busy2';

$route['authoz/auth_test'] = 'authoz/auth_test';

$route['authoz/usersmaintain'] = 'authoz/usersmaintain';
$route['authoz/user_add'] = 'authoz/user_add';
$route['authoz/user_add_job'] = 'authoz/user_add_job';

$route['authoz/scopesmaintain'] = 'authoz/scopesmaintain';
$route['authoz/scope_edit'] = 'authoz/scope_edit';
$route['authoz/scope_edit_job'] = 'authoz/scope_edit_job';

$route['authoz/rolesmaintain'] = 'authoz/rolesmaintain';
$route['authoz/role_add'] = 'authoz/role_add';
$route['authoz/role_add_job'] = 'authoz/role_add_job';

$route['authoz/(:any)'] = 'authoz/authz/$1';
/*$route['authoz/authz'] = 'authoz/authz';*/
$route['authoz'] = 'authoz';
$route['(:any)'] = 'Base/basefun/$1';
$route['default_controller'] = 'base/basefun';
