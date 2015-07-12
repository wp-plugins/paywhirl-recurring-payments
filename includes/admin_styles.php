<?php defined('ABSPATH') or die();


// Admin Styles Include


// Define actions
add_action('admin_enqueue_scripts', 'paywhirl_admin_styles');


function paywhirl_admin_styles($page) {

	// Determine what the current page is
	switch($page) {

		// If the current page is the menu page
		case 'toplevel_page_paywhirl-menu':

			// Register admin styles
			wp_register_style('paywhirl-admin-styles', plugins_url('/paywhirl/css/admin-dashboard-page.css'), false, null);

			// Load admin styles
			wp_enqueue_style('paywhirl-admin-styles');

		break;

		// If the current page is the orders page
		case 'paywhirl_page_paywhirl-orders':

			// Register admin styles
			wp_register_style('paywhirl-admin-styles', plugins_url('/paywhirl/css/admin-orders-page.css'), false, null);

			// Load admin styles
			wp_enqueue_style('paywhirl-admin-styles');

		break;

		// If the current page is the subscribers page
		case 'paywhirl_page_paywhirl-subscribers':

			// Register admin styles
			wp_register_style('paywhirl-admin-styles', plugins_url('/paywhirl/css/admin-subscribers-page.css'), false, null);

			// Load admin styles
			wp_enqueue_style('paywhirl-admin-styles');

		break;

		// If the current page is the installation page
		case 'paywhirl_page_paywhirl-installation':

			// Register admin styles
			wp_register_style('paywhirl-admin-styles', plugins_url('/paywhirl/css/admin-installation-page.css'), false, null);

			// Load admin styles
			wp_enqueue_style('paywhirl-admin-styles');

		break;

	}
}