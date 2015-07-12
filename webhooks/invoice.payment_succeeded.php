<?php defined('ABSPATH') or die();


// Invoice Payment Succeeded webhook


$line_items = array();

// Include invoice created webhook (in case user or invoice hasn't been created)
include PAYWHIRL_DIR.'webhooks/invoice.created.php';

// Update user's invoice
$user_invoice->update(array(

	payment_status => 'paid',
	fulfillment_status => 'pending',

));