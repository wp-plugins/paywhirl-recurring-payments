<?php defined('ABSPATH') or die();


// Customer Created webhook


// Create a new user
$user = new paywhirl_user($webhook->subscriber->email);

// Update user's data
$user->update(array(

	zip => $webhook->subscriber->zip,
	city => $webhook->subscriber->city,
	state => $webhook->subscriber->state,
	phone => $webhook->subscriber->phone,
	paywhirl_id => $webhook->subscriber->id,
	country => $webhook->subscriber->country,
	address_1 => $webhook->subscriber->address_1,
	address_2 => $webhook->subscriber->address_2,
	last_name => $webhook->subscriber->last_name,
	stripe_id => $webhook->subscriber->stripe_id,
	first_name => $webhook->subscriber->first_name,

));