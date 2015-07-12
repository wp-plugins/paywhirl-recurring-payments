<?php defined('ABSPATH') or die();


// Admin Scripts Include


// Define actions
add_action('admin_enqueue_scripts', 'paywhirl_admin_scripts' );


function paywhirl_admin_scripts($page) {

	// Determine what the current page is
	switch($page) {

		// If the current page is the options page
		case 'paywhirl_page_paywhirl-orders':

			// Register admin options loader script
			wp_register_script('paywhirl-admin-orders-loader', plugins_url('/paywhirl/js/orders.js'), false, null, true);

			// Load admin orders loader script
			wp_enqueue_script('paywhirl-admin-orders-loader');

		break;

		// If the current page is the subscribers page
		case 'paywhirl_page_paywhirl-subscribers':

			// Register admin subscribers loader script
			wp_register_script('paywhirl-admin-subscribers-loader', plugins_url('/paywhirl/js/subscribers.js'), false, null, true);

			// Load admin subscribers loader script
			wp_enqueue_script('paywhirl-admin-subscribers-loader');

		break;

	}
}