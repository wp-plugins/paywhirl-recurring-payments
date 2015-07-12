<?php defined('ABSPATH') or die();


// Admin Pages Include


function paywhirl_admin_dashboard_page() {

	// If the user cannot manage options
	if(!current_user_can('manage_options')) {

		// Stop loading page and display error
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// Load admin dashboard page
	include(PAYWHIRL_DIR.'pages/admin-dashboard.php');
}

function paywhirl_admin_orders_page() {

	// If the user cannot manage options
	if(!current_user_can('manage_options')) {

		// Stop loading page and display error
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// Load admin orders page
	include(PAYWHIRL_DIR.'pages/admin-orders.php');
}

function paywhirl_admin_subscribers_page() {

	// If the user cannot manage options
	if(!current_user_can('manage_options')) {

		// Stop loading page and display error
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// Load admin orders page
	include(PAYWHIRL_DIR.'pages/admin-subscribers.php');
}

function paywhirl_admin_installation_page() {

	// If the user cannot manage options
	if(!current_user_can('manage_options')) {

		// Stop loading page and display error
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// Load admin orders page
	include(PAYWHIRL_DIR.'pages/admin-installation.php');
}