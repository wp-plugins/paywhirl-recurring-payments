<?php defined('ABSPATH') or die();


// Invoice Created webhook


$line_items = array();

// Include customer created webhook (in case user hasn't been created)
include PAYWHIRL_DIR.'webhooks/customer.created.php';

// Iterate through each line item
foreach($webhook->body->data->object->lines->data as $line_item) {

	// Add line item to line items array
	array_push($line_items, array(

		name => $line_item->plan->name,
		quantity => $line_item->quantity,
		amount => $line_item->plan->amount,
		currency => $line_item->plan->currency,

	));
}

// Create user's invoice
$user_invoice = $user->get_invoice($webhook->body->data->object->id);

// Update user's invoice
$user_invoice->update(array(

	line_items => $line_items,
	payment_status => 'not paid',
	fulfillment_status => 'pending',
	date => $webhook->body->data->object->date,
	total => $webhook->body->data->object->total,
	currency => $webhook->body->data->object->currency,
	subtotal => $webhook->body->data->object->subtotal,

));