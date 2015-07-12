<?php defined('ABSPATH') or die();


// Paywhirl User Library


class paywhirl_user {

	function __construct($user) {

		// If obtaining user by email
		if(!is_numeric($user)) {

			// Get user id by email
			$user_id = email_exists($user);

			// If user is registered
			if($user_id) {

				// Set user as user
				$user = $user_id;

			// If user is not registered
			} else {

				// Register user
				$user = wp_insert_user(array(
		
					user_email => $user,
					user_login  =>  $user,
		
				));
			}
		}

		// Get user's data and meta data
		$data = get_userdata($user);
		$meta_data = get_user_meta($user);

		// Load user's attributes into object
		$this->id = $data->ID;
		$this->zip = $meta_data['zip'][0];
		$this->city = $meta_data['city'][0];
		$this->state = $meta_data['state'][0];
		$this->email = $data->data->user_email;
		$this->phone = $meta_data['phone'][0];
		$this->country = $meta_data['country'][0];
		$this->last_name = $meta_data['last_name'][0];
		$this->address_1 = $meta_data['address_1'][0];
		$this->address_2 = $meta_data['address_2'][0];
		$this->stripe_id = $meta_data['stripe_id'][0];
		$this->first_name = $meta_data['first_name'][0];
		$this->display_name = $data->data->display_name;
		$this->registered = $data->data->user_registered;
		$this->paywhirl_id = $meta_data['paywhirl_id'][0];
	}

	function update($updates) {

		// Iterate through each update
		foreach($updates as $update => $value) {

			// Load update into object
			$this->$update = $value;
		}

		// Update user's data
		wp_update_user(array(

			'ID' => $this->id,
			'user_email' => $this->email,
			'display_name' => $this->display_name,
			'user_registered' => $this->registered,

		));

		// Update user's meta data
		update_user_meta($this->id, 'zip', $this->zip);
		update_user_meta($this->id, 'city', $this->city);
		update_user_meta($this->id, 'state', $this->state);
		update_user_meta($this->id, 'phone', $this->phone);
		update_user_meta($this->id, 'country', $this->country);
		update_user_meta($this->id, 'last_name', $this->last_name);
		update_user_meta($this->id, 'address_1', $this->address_1);
		update_user_meta($this->id, 'address_2', $this->address_2);
		update_user_meta($this->id, 'stripe_id', $this->stripe_id);
		update_user_meta($this->id, 'first_name', $this->first_name);
		update_user_meta($this->id, 'paywhirl_id', $this->paywhirl_id);
	}

	function get_invoice($invoice) {

		// Get and return the user's invoice
		return new paywhirl_user_invoice($this->id, $invoice);
	}

	static function getUsers() {
		global $wpdb;
		$results = [];

		// Get users from wordpresss
		$users = get_users(array(

			role => 'subscriber',

		));

		// Iterate through each user in users
		foreach($users as $user) {

			// Get user's meta data
			$meta_data = get_user_meta($user->ID);

			// Load user into results
			array_push($results, array(

				id => $user->ID,
				zip => $meta_data['zip'][0],
				city => $meta_data['city'][0],
				state => $meta_data['state'][0],
				email => $user->data->user_email,
				phone => $meta_data['phone'][0],
				country => $meta_data['country'][0],
				last_name => $meta_data['last_name'][0],
				address_1 => $meta_data['address_1'][0],
				address_2 => $meta_data['address_2'][0],
				stripe_id => $meta_data['stripe_id'][0],
				first_name => $meta_data['first_name'][0],
				display_name => $user->data->display_name,
				registered => $user->data->user_registered,
				paywhirl_id => $meta_data['paywhirl_id'][0],

			));
		}

		// Return results
		return $results;
	}

}