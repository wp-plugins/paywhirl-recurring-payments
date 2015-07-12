

// Subscribers javascript


var payWhirlComponent = {

	extend: function(object) {
		var self = this;

		// Return extend function
		return function(options) {
			var component = {};

			// Iterate through each element in object
			for(var element in object) {

				// If object has element
				if (object.hasOwnProperty(element)) {

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

var paywhirlSubscribersComponent = payWhirlComponent.extend({

	defaultProps: function() {
		var self = this;

		// Get and split url
		var url = window.location.href.split('/');

		// Remove old end of url
		url = url.slice(0, url.length - 2)

		// Add new end to url and join back together
		url.push('paywhirl-api/');

		// Join the url back together
		url = url.join('/');

		// return props
		return  {

			url: url,
			subscribers: [],
			sort: {

				by: 'id',
				ascending: true,

			},

		};
	},

	initialize: function(options) {
		var self = this;

		// Get subscribers
		self.getSubscribers();
	},

	getSubscribers: function() {
		var self = this;

		// Build ajax request
		var request = new XMLHttpRequest();

		// Open request
		request.open('GET', self.props.url+'subscribers', true);

		// Define onload response
		request.onload = function() {

			// If loaded successfully
			if(request.status >= 200 && request.status < 400) {

				// Load subscribers into props
				self.props.subscribers = JSON.parse(request.responseText);

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
		document.querySelector('.subscribers-table').innerHTML = self.buildSubscriberTable();
	},

	buildSubscriberTable: function() {
		var self = this;

		// Get subscribers
		var subscribers = self.props.subscribers;

		// Sort the subscribers
		subscribers.sort(function compare(a, b) {

			// Determine what the current sort by is
			switch(self.props.sort.by) {
				
				// If sorted by id
				case 'id':

					// Sort by id
					var value =  a.id - b.id;

				break;

				// If sorted by last name
				case 'last name':

					// Sort by last name
				    var value = a.last_name > b.last_name ? 1 : -1;

				break;

				// If sorted by first name
				case 'first name':

					// Sort by first name
				    var value = a.first_name > b.first_name ? 1 : -1;

				break;

				// If sorted by email
				case 'email':

					// Sort by email
				    var value = a.email > b.email ? 1 : -1;

				break;

			}

			// Return value based on whether sort is set to ascending or descending
			return self.props.sort.ascending ? value : -value;
		});

		// Build and return subscribers table
		return (
			'<div class="header-row">'+
				'<div class="cell a2" onClick="paywhirlSubscribers.columnClick(\'id\')">'+
					'ID'+
				'</div>'+
				'<div class="cell a7 b0" onClick="paywhirlSubscribers.columnClick(\'last name\')">'+
					'Name'+
				'</div>'+
				'<div class="a0 b11">'+
					'<div class="cell a12" onClick="paywhirlSubscribers.columnClick(\'last name\')">'+
						'Last Name'+
					'</div>'+
					'<div class="cell a12" onClick="paywhirlSubscribers.columnClick(\'first name\')">'+
						'First Name'+
					'</div>'+
				'</div>'+
				'<div class="cell a15 b11" onClick="paywhirlSubscribers.columnClick(\'email\')">'+
					'Email'+
				'</div>'+
			'</div>'+
			'<div class="flex">'+
				'<div class="overflow">'+
					subscribers.map(function(subscriber) {
						return(
							'<div class="divider"></div>'+
							'<div class="row" onClick="paywhirlSubscribers.rowClick('+subscriber.id+')">'+
								'<div class="cell a2">'+
									subscriber.id+
								'</div>'+
								'<div class="a7 b11">'+
									'<div class="cell a24 b12">'+
										subscriber.last_name+
									'</div>'+
									'<div class="cell a24 b12">'+
										subscriber.first_name+
									'</div>'+									
								'</div>'+
								'<div class="cell a15 b11">'+
									subscriber.email+
								'</div>'+
							'</div>'
						);
					}).join('')+
				'</div>'+
			'</div>'
		);
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

		// Iterate through each subscriber
		for(var i = 0; i < self.props.subscribers.length; i++) {

		    // If subscriber is the clicked row
		    if(self.props.subscribers[i].id == row) {

				// Open subscriber details dialog
				new paywhirlSubscriberDetailsDialog({

					subscriber: self.props.subscribers[i],

				});
		    }
		}
	},

});

var paywhirlSubscriberDetailsDialog = payWhirlComponent.extend({

	defaultProps: function() {
		return {

			subscriber: null,

		};
	},

	initialize: function(options) {
		var self = this;

		// Load subscriber into props
		self.props.subscriber = options.subscriber;

		// Open a new dialog
		new paywhirlDialog({

			title: 'Subscriber Details',
			classes: 'subscriber-details-dialog',

		});

		// Render
		self.render();
	},

	render: function() {
		var self = this;

		// Build and load subscriber details into dialog's content
		document.querySelector('.subscriber-details-dialog').querySelector('.content').innerHTML = self.buildSubscriberDetails();
	},

	buildSubscriberDetails: function() {
		var self = this;

		// Build and return subscriber details
		return (
			'<div class="flex-container">'+
				'<div>'+
					'<div class="paywhirl-link">'+
						'<a href="http://www.paywhirl.com/subscriber/'+self.props.subscriber.paywhirl_id+'" target="blank">'+
							'Goto Paywhirl Subscriber Page'+
						'</a>'+
					'</div>'+
					'<div class="name details">'+
						self.props.subscriber.first_name+' '+self.props.subscriber.last_name+
					'</div>'+
				'</div>'+
				'<div class="flex">'+
					'<div class="overflow">'+
						'<div class="grey-box">'+
							'<div class="label">'+
								'Email'+
							'</div>'+
							'<div class="email details">'+
								'<a href="mailto:'+self.props.subscriber.email+'">'+
									self.props.subscriber.email+
								'</a>'+
							'</div>'+
						'</div>'+
						'<div class="grey-box">'+
							'<div class="label">'+
								'Address'+
							'</div>'+
							'<div class="address details">'+
								'<div>'+
									self.props.subscriber.address_1+
								'</div>'+
								'<div>'+
									self.props.subscriber.address_2+
								'</div>'+
								'<div>'+
									self.props.subscriber.city+', '+self.props.subscriber.state+' '+self.props.subscriber.zip+' '+self.props.subscriber.country+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="grey-box">'+
							'<div class="label">'+
								'Phone'+
							'</div>'+
							'<div class="phone details">'+
								self.props.subscriber.phone+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>'
		);
	},

});

// When the dom is loaded
document.addEventListener('DOMContentLoaded', function(event) {

	paywhirlSubscribers = new paywhirlSubscribersComponent();
});