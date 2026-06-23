<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller']   = 'Visitor';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

$route['visitor_register']          = 'Visitor_auth/register_submit';
$route['visitor_login']             = 'Visitor_auth/login_submit';
$route['visitor_logout']            = 'Visitor_auth/logout';
$route['visitor_dashboard']         = 'Visitor_panel/dashboard';
$route['visitor_profile']           = 'Visitor_panel/profile';
$route['get_visitor_profile']       = 'Visitor_panel/getProfile';
$route['get_visitor_applications']  = 'Visitor_panel/getApplications';
$route['visitor_apply_form']        = 'Visitor/apply';
$route['visitor_apply']             = 'Visitor/submit';
$route['visitor_card/(:num)']       = 'Visitor_panel/card/$1';
