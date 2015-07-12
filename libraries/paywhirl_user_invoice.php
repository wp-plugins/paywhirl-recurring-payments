<?php defined('ABSPATH') or die();


// Paywhirl User Invoice Library


class paywhirl_user_invoice {

	function __construct($user, $user_invoice) {
		global $wpdb;

		// Define user invoices table
		$user_invoices = $wpdb->prefix.'paywhirl_user_invoices';

		// Get results from user invoices table
		$results = $wpdb->get_row("SELECT * FROM $user_invoices WHERE user_invoice = '$user_invoice'");

		// If no results was not found
		if($results == '') {

			// Insert user_invoice into user invoices table
			$wpdb->insert($user_invoices, array(

				user => $user,
				attributes => '[]',
				user_invoice => $user_invoice,

			));

			// Get results from user invoices table
			$results = $wpdb->get_row("SELECT * FROM $user_invoices WHERE user_invoice = '$user_invoice'");
		}

		// Load user results into object
		$this->id = $results->id;
		$this->user = $results->user;
		$this->user_invoice = $results->user_invoice;

		// Iterate through each attribute
		foreach(json_decode($results->attributes) as $attribute => $value) {

			// Load attribute into object
			$this->$attribute = $value;
		}
	}

	function update($updates) {
		global $wpdb;

		// Iterate through each update
		foreach($updates as $update => $value) {

			// Load update into object
			$this->$update = $value;
		}

		// Update user invoice attributes
		$wpdb->update($wpdb->prefix.'paywhirl_user_invoices', array(

			attributes => json_encode(array(

				date => $this->date,
				total => $this->total,
				subtotal => $this->subtotal,
				currency => $this->currency,
				line_items => $this->line_items,
				payment_status => $this->payment_status,
				fulfillment_status => $this->fulfillment_status,

			)),

		), array(

			id => $this->id,

		));
	}

	static function getInvoices() {
		global $wpdb;

		// Define user invoices table
		$user_invoices = $wpdb->prefix.'paywhirl_user_invoices';

		// Get results from user invoices table
		$results =  $wpdb->get_results("SELECT * FROM $user_invoices");

		// Iterate through each result
		foreach($results as $result) {

			// Iterate through each attribute
			foreach(json_decode($result->attributes) as $attribute => $value) {

				// Load attribute into object
				$result->$attribute = $value;
			}

			// Remove attributes
			unset($result->attributes);
		}

		// Return results
		return $results;
	}

}