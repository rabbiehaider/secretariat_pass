<?php
defined('BASEPATH') or exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('database', 'session', 'form_validation', 'cart');

$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'file', 'form', 'myhelper', 'my_helper');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('Gate_model', 'Report_model', 'User_model', 'Visitor_model');
