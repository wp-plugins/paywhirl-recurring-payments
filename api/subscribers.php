<?php defined('ABSPATH') or die();


// Subscribers API


// If the logged in user can manage options
if(current_user_can('manage_options')) {

	// Determine the request type
	switch($_SERVER['REQUEST_METHOD']) {

		// If request was a get
		case 'GET':

			// Get all users
			$users = paywhirl_user::getUsers();

			// JSON encode and print users
			echo json_encode($users);

		break;

	}
}