

// Orders javascript


var payWhirlComponent = {

	extend: function(object) {
		var self = this;

		// Return extend function
		return function(options) {
			var component = {};

			// Iterate through each element in object
			for(var element in object) {

				// If object has element
				if(object.hasOwnProperty(element)) {

					// Load element into component
					component[element] = object[element];
				}
			}

			// If default props exists
			if(component.defaultProps) {

				// Set default props as props
				component.props = component.defaultProps();
			}

			// If initialize exists
			if(component.initialize) {

				// Run initialize passing options
				component.initialize(options);
			}

			// Return component
			return component;
		};
	},

};

var paywhirlDialog = payWhirlComponent.extend({

	initialize: function(options) {
		var self = this;

		// Define props
		self.props = {

			title: options.title ? options.title : '',
			content: options.content ? options.content : '',
			classes: options.classes ? options.classes : null,

		};

		// Build overlay
		self.buildOverlay();

		// Build dialog passing options
		self.buildDialog();
	},

	buildOverlay: function() {
		var self = this;

		// Create an overlay
		var overlay = document.createElement('div');

		// Add overaly class to overlay
		overlay.classList.add('paywhirl-dialog-overlay');

		// Load overlay into body
		document.querySelector('.paywhirl-page').appendChild(overlay);

		// Attach listeners to overlay
		document.querySelector('.paywhirl-dialog-overlay').onclick = self.close;
	},

	buildDialog: function() {
		var self = this;

		// Define dialog contents
		var dialogContents =
			'<div class="flex-container =">'+
				'<div class="header">'+
					'<div class="title">'+
						self.props.title+
					'</div>'+
					'<div class="close-button"></div>'+
				'</div>'+
				'<div class="divider"></div>'+
				'<div class="flex">'+
					'<div class="content">'+
						self.props.content+
					'</div>'+
				'</div>'+
			'</div>';

		// Create a dialog
		var dialog = document.createElement('div');

		// Add dialog class to dialog
		dialog.classList.add('paywhirl-dialog');

		// If dialog has additional classes
		if(self.props.classes) {

			// Add additional classes to dialog
			dialog.classList.add(self.props.classes);
		}

		// Load dialog into body
		document.querySelector('.paywhirl-page').appendChild(dialog);

		// Load dialog contents into dialog
		document.querySelector('.paywhirl-dialog').innerHTML = dialogContents;

		// Attach listeners to close button
		document.querySelector('.paywhirl-dialog').querySelector('.close-button').onclick = self.close;
	},

	close: function() {

		// Remove dialog and overlay
		document.querySelector('.paywhirl-dialog').remove();
		document.querySelector('.paywhirl-dialog-overlay').remove();
	},

});

var paywhirlOrdersComponent = payWhirlComponent.extend({

	defaultProps: function() {
		var self = this;

		// Get and split url
		var url = window.location.href.split('/');

		// Remove old end of url
		url = url.slice(0, url.length - 2);

		// Add new end to url and join back together
		url.push('paywhirl-api/');

		// Join the url back together
		url = url.join('/');

		// return props
		return  {

			url: url,
			orders: [],
			currentPage: 1,
			sort: {

				by: 'order',
				ascending: true,

			},

		};
	},

	initialize: function(options) {
		var self = this;

		// Get orders
		self.getOrders();
	},

	getOrders: function() {
		var self = this;

		// Build ajax request
		var request = new XMLHttpRequest();

		// Open request
		request.open('GET', self.props.url+'orders', true);

		// Define onload response
		request.onload = function() {

			// If loaded successfully
			if(request.status >= 200 && request.status < 400) {

				// Load orders into props
				self.props.orders = JSON.parse(request.responseText);

				// Render
				self.render();
			}
		};

		// Send ajax request
		request.send();
	},

	render: function() {
		var self = this;

		// Build and load table into orders table
		document.querySelector('.page-selector').innerHTML = self.buildPageSelector();

		// Build and load table into orders table
		document.querySelector('.orders-table').innerHTML = self.buildOrderTable();

		// Get page buttons
		var pageButtons = document.querySelector('.page-selector').querySelectorAll('.page-button');

		// Iterate through each pagebutton in pagebuttons
		for(i = 0; i < pageButtons.length; ++i) {

			// When page button has been clicked
			pageButtons[i].onclick = function() {

				// Trigger page button click passing event
				self.pageButtonClick(event)
			};
		}
	},

	buildPageSelector: function() {
		var self = this;
		var pages = [];

		// Iterate through the number of pages
		for(page = 1; page <= Math.ceil(self.props.orders.length / 20); page++) { 

			// Add page to pages
			pages.push(page);
		}

		// If there is more than one page
		if(pages.length > 1) {
			
			// Return pages
			return pages.map(function(page) {

				// If page is the current page
				if(page == self.props.currentPage) {

					// Return an active page
					return (
						'<div class="active page-button">'+
							page+
						'</div>'
					);

				// If page is not the current page
				} else {

					// Return a page
					return (
						'<div class="page-button">'+
							page+
						'</div>'
					);

				}

			}).join('');
		
		// If there is only one page
		} else {

			// Return an empty string
			return '';
		}
	},

	buildOrderTable: function() {
		var self = this;

		// Get orders
		var orders = self.props.orders;

		// Sort the orders
		orders.sort(function compare(a, b) {

			// Determine what the current sort by is
			switch(self.props.sort.by) {
				
				// If sorted by order
				case 'order':

					// Sort by id
					var value =  a.id - b.id;

				break;

				// If sorted by date
				case 'date':

					// Sort by date
					var value =  a.date - b.date;

				break;

				// If sorted by customer
				case 'customer':

					// Sort by name
				    var value = a.user.first_name > b.user.first_name ? 1 : -1;

				break;

				// If sorted by payment status
				case 'payment status':

					// Sort by payment status
				    var value = a.payment_status > b.payment_status ? 1 : -1;

				break;

				// If sorted by fulfillment status
				case 'fulfillment status':

					// Sort by fulfillment status
				    var value = a.fulfillment_status > b.fulfillment_status ? 1 : -1;

				break;

				// If sorted by total
				case 'total':

					// Sort by total
					var value =  a.total - b.total;

				break;

			}

			// Return value based on whether sort is set to ascending or descending
			return self.props.sort.ascending ? value : -value;
		});

		// Get the section of orders for the current page
		orders = orders.slice(20 * (self.props.currentPage - 1), 20 * (self.props.currentPage - 1) + 20);

		// Build and return orders table
		return (
			'<div class="header-row">'+
				'<div class="cell a3 b2" onClick="paywhirlOrders.columnClick(\'order\')">'+
					'Order'+
				'</div>'+
				'<div class="cell a10 b7 c0" onClick="paywhirlOrders.columnClick(\'payment status\')">'+
					'Date / Customer'+
				'</div>'+
				'<div class="a0 c12 d14">'+
					'<div class="cell c12" onClick="paywhirlOrders.columnClick(\'date\')">'+
						'Date'+
					'</div>'+
					'<div class="cell c12" onClick="paywhirlOrders.columnClick(\'customer\')">'+
						'Customer'+
					'</div>'+
				'</div>'+
				'<div class="cell a8 b0" onClick="paywhirlOrders.columnClick(\'payment status\')">'+
					'Status'+
				'</div>'+
				'<div class="a0 b13 c8 d6">'+
					'<div class="cell b12" onClick="paywhirlOrders.columnClick(\'payment status\')">'+
						'Payment Status'+
					'</div>'+
					'<div class="cell b12" onClick="paywhirlOrders.columnClick(\'fulfillment status\')">'+
						'Fulfillment Status'+
					'</div>'+
				'</div>'+
				'<div class="cell a3 b2" onClick="paywhirlOrders.columnClick(\'total\')">'+
					'Total'+
				'</div>'+
			'</div>'+
			'<div class="flex">'+
				'<div class="overflow">'+
					orders.map(function(order) {
						return(
							'<div class="divider"></div>'+
							'<div class="row" onClick="paywhirlOrders.rowClick('+order.id+')">'+
								'<div class="cell a3 b2">'+
									order.id+
								'</div>'+
								'<div class="a10 b7 c12 d14">'+
									'<div class="cell a24 c12">'+
										order.date_text+
									'</div>'+
									'<div class="cell a24 c12">'+
										order.user.first_name+' '+order.user.last_name+
									'</div>'+
								'</div>'+
								'<div class="a8 b13 c8 d6">'+
									'<div class="cell a24 b12">'+
										self.buildPaymentStatus(order.payment_status)+
									'</div>'+
									'<div class="cell a24 b12">'+
										self.buildFulfillmentStatus(order.fulfillment_status)+
									'</div>'+
								'</div>'+
								'<div class="cell a3 b2">'+
									order.currency_symbol+' '+order.total+
								'</div>'+
							'</div>'
						);
					}).join('')+
				'</div>'+
			'</div>'
		);
	},

	buildPaymentStatus: function(status) {
		var self = this;

		// Determine what the status is
		switch(status) {

			// If status is paid
			case 'paid':
			
				return (
					'<div class="green-button paywhirl-button">'+
						'Paid'+
					'</div>'
				);
			
			break;

			// If status is not paid
			case 'not paid':
			
				return (
					'<div class="red-button paywhirl-button">'+
						'Not Paid'+
					'</div>'
				);
			
			break;

		}
	},

	buildFulfillmentStatus: function(status) {
		var self = this;

		// If the status is a custom status
		if(status.substr(0, 6) == 'custom') {
			
			// Return the custom status button
			return (
				'<div class="fulfillment-status-button grey-button paywhirl-button">'+
					status.substr(7)+
				'</div>'
			);
		}

		// Determine what the status is
		switch(status) {

			// If status is pending
			case 'pending':
			
				return (
					'<div class="yellow-button paywhirl-button">'+
						'Pending'+
					'</div>'
				);
			
			break;

			// If status is shipped
			case 'shipped':
			
				return (
					'<div class="green-button paywhirl-button">'+
						'Shipped'+
					'</div>'
				);
			
			break;

			// If status is on hold
			case 'on hold':
			
				return (
					'<div class="red-button paywhirl-button">'+
						'On Hold'+
					'</div>'
				);
			
			break;

		}
	},

	pageButtonClick: function(event) {
		var self = this;
		
		// Get the selected page
		var selectedPage = event.target.textContent;
		
		// If the selected page is not the current page
		if(selectedPage != self.props.currentPage) {
			
			// Set the selected page as the current page
			self.props.currentPage = selectedPage;

			// Render
			self.render();
		}
	},

	columnClick: function(column) {
		var self = this;

		// If the sort by hasn't changed
		if(self.props.sort.by == column) {

			// Toggle sort ascending
			self.props.sort.ascending = !self.props.sort.ascending;

		// If the sort has changed
		} else {

			// Set new sort
			self.props.sort = {

				by: column,
				ascending: true,

			};
		}

		// Render
		self.render();
	},

	rowClick: function(row) {
		var self = this;

		// Iterate through each order
		for(var i = 0; i < self.props.orders.length; i++) {

		    // If order is the clicked row
		    if(self.props.orders[i].id == row) {

				// Open order details dialog
				new paywhirlOrderDetailsDialog({

					order: self.props.orders[i],

				});
		    }
		}
	},

	saveOrder: function(order) {
		var self = this;

		// Build ajax request
		var request = new XMLHttpRequest();

		// Open request
		request.open('PUT', self.props.url+'orders', true);

		// Send ajax request
		request.send(JSON.stringify(order));
	},

});

var paywhirlOrderDetailsDialog = payWhirlComponent.extend({

	defaultProps: function() {
		return {

			order: null,

		};
	},

	initialize: function(options) {
		var self = this;

		// Load order into props
		self.props.order = options.order;

		// Open a new dialog
		new paywhirlDialog({

			classes: 'order-details-dialog',
			title: 'Order '+self.props.order.id+' Details',

		});

		// Render
		self.render();
	},

	render: function() {
		var self = this;

		// Build and load order details into dialog's content
		document.querySelector('.order-details-dialog').querySelector('.content').innerHTML = self.buildOrderDetails();

		// Attach listeners to fulfillment status buttons
		document.querySelector('.paywhirl-dialog').querySelector('.fulfillment-status-button').onclick = self.fulfillmentStatusButtonClick.bind(null, self);
	},

	buildOrderDetails: function() {
		var self = this;

		// Build and return order details
		return (
			'<div class="flex-container">'+
				'<div>'+
					'<div class="a24 c10-omega">'+
						'<div class="a12">'+
							self.buildFulfillmentStatus(self.props.order.fulfillment_status)+
						'</div>'+
						'<div class="a12">'+
							self.buildPaymentStatus(self.props.order.payment_status)+
						'</div>'+
						'<div class="order-date">'+
							self.props.order.date_text+
						'</div>'+
					'</div>'+
				'</div>'+
				'<div class="flex">'+
					'<div class="flex-container overflow">'+
						'<div>'+
							'<div class="user-name">'+
								self.props.order.user.first_name+' '+self.props.order.user.last_name+
							'</div>'+
							'<div class="grey-box">'+
								'<div class="label">'+
									'Email'+
								'</div>'+
								'<div class="email details">'+
									'<a href="mailto:'+self.props.order.user.email+'">'+
										self.props.order.user.email+
									'</a>'+
								'</div>'+
							'</div>'+
							'<div class="grey-box">'+
								'<div class="label">'+
									'Address'+
								'</div>'+
								'<div class="address details">'+
									'<div>'+
										self.props.order.user.address_1+
									'</div>'+
									'<div>'+
										self.props.order.user.address_2+
									'</div>'+
									'<div>'+
										self.props.order.user.city+', '+self.props.order.user.state+' '+self.props.order.user.zip+' '+self.props.order.user.country+
									'</div>'+
								'</div>'+
							'</div>'+
							'<div class="grey-box">'+
								'<div class="label">'+
									'Phone'+
								'</div>'+
								'<div class="phone details">'+
									self.props.order.user.phone+
								'</div>'+
							'</div>'+
							'<div class="grey-box">'+
								'<div class="header-row">'+
									'<div class="a14 b15 c16">'+
										'Items'+
									'</div>'+
									'<div class="a5 b5">'+
										'Quantity'+
									'</div>'+
									'<div class="a5 b4 c3">'+
										'Amount'+
									'</div>'+
								'</div>'+
								'<div class="divider"></div>'+
								self.props.order.line_items.map(function(line_item) {
									return (
										'<div class="row">'+
											'<div class="a14 b15 c16">'+
												line_item.name+
											'</div>'+
											'<div class="a5">'+
												line_item.quantity+
											'</div>'+
											'<div class="a5 b4 c3">'+
												line_item.currency_symbol+' '+line_item.amount+
											'</div>'+
										'</div>'
									);
								}).join('')+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
				'<div>'+
					'<div class="divider"></div>'+
					'<div class="row">'+
						'<div class="a5-omega b4-omega c3-omega">'+
							self.props.order.currency_symbol+' '+self.props.order.subtotal+
						'</div>'+
						'<div class="a5-omega b5-omega">'+
							'Subtotal'+
						'</div>'+
					'</div>'+
					'<div class="row">'+
						'<div class="a5-omega b4-omega c3-omega">'+
							self.props.order.currency_symbol+' '+self.props.order.total+
						'</div>'+
						'<div class="a5-omega b5-omega">'+
							'Total'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'	
		);
	},

	buildPaymentStatus: function() {
		var self = this;

		// Determine what the status is
		switch(self.props.order.payment_status) {

			// If status is paid
			case 'paid':

				return (
					'<div class="green-button paywhirl-button">'+
						'Paid'+
					'</div>'
				);

			break;

			// If status is not paid
			case 'not paid':

				return (
					'<div class="red-button paywhirl-button">'+
						'Not Paid'+
					'</div>'
				);

			break;

		}
	},

	buildFulfillmentStatus: function() {
		var self = this;

		// If the status is a custom status
		if(self.props.order.fulfillment_status.substr(0, 6) == 'custom') {
			
			// Return the custom status button
			return (
				'<div class="fulfillment-status-button grey-button paywhirl-button">'+
					self.props.order.fulfillment_status.substr(7)+
				'</div>'
			);
		}

		// Determine what the status is
		switch(self.props.order.fulfillment_status) {

			// If status is pending
			case 'pending':

				// Return the pending status button
				return (
					'<div class="fulfillment-status-button yellow-button paywhirl-button">'+
						'Pending'+
					'</div>'
				);

			break;

			// If status is shipped
			case 'shipped':

				// Return the shipped status button
				return (
					'<div class="fulfillment-status-button green-button paywhirl-button">'+
						'Shipped'+
					'</div>'
				);

			break;

			// If status is on hold
			case 'on hold':

				// Return the on hold status button
				return (
					'<div class="fulfillment-status-button red-button paywhirl-button">'+
						'On Hold'+
					'</div>'
				);

			break;

		}
	},

	fulfillmentStatusButtonClick: function(self) {

		// If the status is a custom status
		if(self.props.order.fulfillment_status.substr(0, 6) == 'custom') {
			
			// Set status as pending
			self.props.order.fulfillment_status = 'pending';
		}

		// Determine what the status is
		switch(self.props.order.fulfillment_status) {

			// If status is pending
			case 'pending':

				// Set status as shipped
				self.props.order.fulfillment_status = 'shipped';

			break;

			// If status is shipped
			case 'shipped':

				// Set status as on hold
				self.props.order.fulfillment_status = 'on hold';

			break;

			// If status is on hold
			case 'on hold':

				// Prompt user for custom status
				var status = prompt('Enter a custom status');

				// If there is a custom status
				if(status != null) {

					// Set status as pending
					self.props.order.fulfillment_status = 'custom:'+status;

				// If there isn't a custom status
				} else {

					// Set status as pending
					self.props.order.fulfillment_status = 'pending';
				}

			break;

		}

		// Render self
		self.render();

		// Render orders component
		paywhirlOrders.render();

		// Save order
		paywhirlOrders.saveOrder(self.props.order);
	},

});

// When the dom is loaded
document.addEventListener('DOMContentLoaded', function(event) {

	paywhirlOrders = new paywhirlOrdersComponent();
});