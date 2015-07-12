<?php


// Webhooks Gateway


// Load Wordpress
require(implode('/', array_slice(explode('/', __DIR__), 0, count(explode('/', __DIR__)) - 4)).'/wp-load.php');

// Define the paywhirl directory
define('PAYWHIRL_DIR', plugin_dir_path(__FILE__));

// Load the libraries
include PAYWHIRL_DIR.'libraries/load.php';

// Get webhook
$webhook = json_decode(file_get_contents('php://input'));

// If webhook was received
if($webhook != '') {

	// Clear webhook file
	devtools::clear_file(PAYWHIRL_DIR.'last-webhook.json');

	// Load webhook into webhook file
	devtools::write_file(PAYWHIRL_DIR.'last-webhook.json', $webhook);

// If webhook was not received
} else {

	// Load the last webhook
	$webhook =  json_decode(file_get_contents(PAYWHIRL_DIR.'last-webhook.json'));
}

// Define webhook file
$webhook_file = PAYWHIRL_DIR.'webhooks/'.$webhook->body->type.'.php';

// If webhook file exists
if(file_exists($webhook_file)) {

	// Load webhook file
	include $webhook_file;
}