<?php defined('ABSPATH') or die();


// Admin Menu Include


// Define actions
add_action('admin_menu', 'paywhirl_menu');


function paywhirl_menu() {

	// Add options page to the top level menu
	add_menu_page('Dashboard', 'Paywhirl', 'manage_options', 'paywhirl-menu', 'paywhirl_admin_dashboard_page', plugins_url('paywhirl/images/menu-icon.png'));

	// Add subpages to the menu
	add_submenu_page('paywhirl-menu', 'Orders', 'Orders', 'manage_options', 'paywhirl-orders', 'paywhirl_admin_orders_page');
	add_submenu_page('paywhirl-menu', 'Subscribers', 'Subscribers', 'manage_options', 'paywhirl-subscribers', 'paywhirl_admin_subscribers_page');
	add_submenu_page('paywhirl-menu', 'Installation', 'Installation', 'manage_options', 'paywhirl-installation', 'paywhirl_admin_installation_page');
}