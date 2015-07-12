<?php defined('ABSPATH') or die();


// Library Loader


spl_autoload_register(function ($class) {

	// Define libraries directory
	$dir = PAYWHIRL_DIR.'libraries/';

	// If a requested library exists
	if(file_exists($dir.$class.'.php')) {

		 // Load the library
		require $dir.$class.'.php';
	}
});