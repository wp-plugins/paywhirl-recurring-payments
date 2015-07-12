<?php


// API Gateway


// Load Wordpress
require(implode('/', array_slice(explode('/', __DIR__), 0, count(explode('/', __DIR__)) - 4)).'/wp-load.php');

// Define the paywhirl directory
define('PAYWHIRL_DIR', plugin_dir_path(__FILE__));

// Load the libraries
include PAYWHIRL_DIR.'libraries/load.php';

// Get the api from the url
$api = array_pop(explode('/', $_SERVER['REQUEST_URI']));

// Define api file
$api_file = PAYWHIRL_DIR.'api/'.$api.'.php';

// If api file exists
if(file_exists($api_file)) {

	// Load api file
	include $api_file;
}