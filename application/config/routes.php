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
$route['visitor_update_photo']      = 'Visitor_panel/updatePhoto';
$route['get_visitor_applications']  = 'Visitor_panel/getApplications';
$route['visitor_apply_form']        = 'Visitor/apply';
$route['visitor_apply']             = 'Visitor/submit';
$route['visitor_card/(:num)']       = 'Visitor_panel/card/$1';
$route['get_admin_dashboard']       = 'Admin/getDashboard';
$route['get_applications']          = 'Admin/getApplications';
$route['approve_application']       = 'Admin/approveApplication';
$route['reject_application']        = 'Admin/rejectApplication';
$route['admin/users']               = 'Admin/users';
$route['get_visitor_users']         = 'Admin/getUsers';
$route['approve_visitor_user']      = 'Admin/approveUser';
$route['reject_visitor_user']       = 'Admin/rejectUser';
$route['admin/scanner']             = 'Admin/scanner';
$route['admin/scanner_details']     = 'Admin/scanner_details';
$route['verify_gate_pass']          = 'Gate/verify';
$route['get_report']                = 'Report/getReport';
$route['report/applications']       = 'Report/applications';
$route['get_application_report']    = 'Report/getApplicationReport';
