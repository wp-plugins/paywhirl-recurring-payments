<?php defined('ABSPATH') or die();


// Rewrite include


// Define actions
add_action('init', 'paywhirl_load_rewrite_rules');
register_activation_hook(PAYWHIRL_DIR.'paywhirl.php', 'paywhirl_activate_rewrite_rules');
register_deactivation_hook(PAYWHIRL_DIR.'paywhirl.php', 'paywhirl_remove_rewrite_rules');


function paywhirl_activate_rewrite_rules() {

	// Load the rewrite rules
	paywhirl_load_rewrite_rules();

	// Flush the rewrite rules
	flush_rewrite_rules();
}

function paywhirl_load_rewrite_rules() {

	// Load rewrite rules
	add_rewrite_rule('paywhirl-api/*', 'wp-content/plugins/paywhirl/api/gateway.php', 'top');
	add_rewrite_rule('paywhirl-webhook', 'wp-content/plugins/paywhirl/webhooks/gateway.php', 'top');
}

function paywhirl_remove_rewrite_rules() {
	global $wp_rewrite;

	// Remove rewrite rules
	unset($wp_rewrite->non_wp_rules['paywhirl-webhook']);

	// Flush the rewrite rules
	flush_rewrite_rules();
}