<?php defined('ABSPATH') or die();


// Database Include


// Define actions
register_activation_hook(PAYWHIRL_DIR.'paywhirl.php', 'paywhirl_create_databases');
register_deactivation_hook(PAYWHIRL_DIR.'paywhirl.php', 'paywhirl_drop_databases');


// Define Paywhirl Databases
$GLOBALS['paywhirl_databases'] = array(

	(object) array(

		key => 'id',
		name => 'paywhirl_user_invoices', 
		fields => array(

			'id int(6) NOT NULL AUTO_INCREMENT',
			'user_invoice varchar(255)',
			'user int(6)',
			'attributes TEXT',

		),

	),

);


function paywhirl_create_databases() {
	global $wpdb;

	// Iterate through each paywhirl database
	foreach($GLOBALS['paywhirl_databases'] as $database) {

		// Define table name
		$table = $wpdb->prefix.$database->name;

		// Define fields
		$fields = implode(', ', $database->fields);

		// Define sql statement
		$sql = "CREATE TABLE ".$table." (".$fields.", UNIQUE KEY (".$database->key."))";

		// Load wordpress upgrade
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');

		// Run sql statement
		dbDelta($sql);
	}
}

function paywhirl_drop_databases() {
	global $wpdb;

	// Iterate through each paywhirl database
	foreach($GLOBALS['paywhirl_databases'] as $database) {

		// Define table name
		$table = $wpdb->prefix.$database->name;

		// Drop table
		$wpdb->query("DROP TABLE IF EXISTS $table");
	}
}