<?php defined('ABSPATH') or die();


// Orders API


// If the logged in user can manage options
if(current_user_can('manage_options')) {

	// Determine the request type
	switch($_SERVER['REQUEST_METHOD']) {

		// If request was a get
		case 'GET':

			// Set timezone to EST
			date_default_timezone_set('America/New_York');

			// Get all user invoices
			$user_invoices = paywhirl_user_invoice::getInvoices();

			// Iterate through each user invoice
			foreach($user_invoices as $user_invoice) {

				// Load user object into user invoice
				$user_invoice->user = new paywhirl_user($user_invoice->user);

				// If user invoice is from today
				if(date('M j Y', $user_invoice->date) == date('M j Y', time())) {

					// Add formatted date text for today
					$user_invoice->date_text = 'Today at '.date('g:ia T', $user_invoice->date);

				// If user invoice is from yesterday
				} else if(date('M j Y', $user_invoice->date) == date('M j Y', time() - 24*60*60)) {

					// Add formatted date text for yesterday
					$user_invoice->date_text = 'Yesterday at '.date('g:ia T', $user_invoice->date);

				// If user invoice is neither from yesterday or today
				} else {

					// Add formatted date text
					$user_invoice->date_text = date('M j, g:ia T', $user_invoice->date);
				}

				// Determine the currency
				switch ($user_invoice->currency) {

					// If currency is US dollars
					case 'usd':

						// Set currency symbol as US dollars symbol
						$user_invoice->currency_symbol = '$';

					break;

				}

				// Iterate through each line item
				foreach($user_invoice->line_items as $line_item) {

					// Determine the currency
					switch ($line_item->currency) {

						// If currency is US dollars
						case 'usd':

							// Set currency symbol as US dollars symbol
							$line_item->currency_symbol = '$';

						break;

					}
				}
			}

			// JSON encode and print user invoices
			echo json_encode($user_invoices);

		break;

		// If request was a put
		case 'PUT':

			// Get updates
			$updates = json_decode(file_get_contents('php://input'));

			// Remove user from updates
			$updates->user = $updates->user->id;

			// Get user invoice
			$user_invoice = new paywhirl_user_invoice($updates->user, $updates->user_invoice);

			// Update user invoice
			$user_invoice->update($updates);

		break;

	}
}