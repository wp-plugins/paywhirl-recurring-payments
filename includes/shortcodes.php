<?php defined('ABSPATH') or die();


// Shortcodes include


// Define actions
add_shortcode('paywhirl', 'paywhirl_shortcodes');


function paywhirl_shortcodes($attributes) {

	// If key is defined
	if(array_key_exists('key', $attributes)) {

		// Load the paywhirl widget javascript
		echo '<script src="https://paywhirl.com/js/widget.js"></script>';

		// Load widget iframe passing key
		echo '<iframe style="width:100%; height:auto; border:0; overflow:hidden;" id="paywhirl_frame" scrolling="no" src="https://paywhirl.com/widget?api_key='.$attributes->key.'"></iframe>';

	// If key is not defined
	} else {

		// Print error message
		echo 'Please provide an API Key. You can retrieve your API Key by visiting <a href="https://paywhirl.com/my-account" target="blank">https://paywhirl.com/my-account</a>.<br><br>Once you have your key, use your place your shortcode in wordpress like this: [paywhirl key=1234567890]';
	}
}