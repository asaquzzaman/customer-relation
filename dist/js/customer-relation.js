/*!
 * customer-relation.js
 *
 * Trigger all javascript functionality
 */

(function( $ ) {
	var Customer = {
		/**
		 * Initial hook
		 */
		init: function() {
			$('.customer-customer-form-wrap').on('submit','#customer-form', this.submit);
			$('.customer-customer-form-wrap').on('click','.cancel-error', this.hideMessage);
		},

		/**
		 * Hide all kind of warning message.
		 */
		hideMessage: function(el) {
			el.preventDefault();

			var wrap = $('.customer-display-error');

			wrap.removeClass('error-notice')
				.removeClass('success-notice')
				.find('p')
				.html('');

			wrap.find('.cancel-error')
				.addClass('display-none');
		},

		/**
		 * Process the customer relation form data.
		 */
		submit: function(el) {
			el.preventDefault();

			// Set form value
			var form = $('#customer-form'),
				formData = {
					name: form.find('input[name="name"]'),
					phone: form.find('input[name="phone"]'),
					email: form.find('input[name="email"]'),
					budget: form.find('input[name="budget"]'),
					message: form.find('textarea[name="message"]'),
					dateTime: form.find('input[name="date_time"]')
				}

			// Form data validation
			if ( ! Customer.validate( form, formData ) ) {
				return false;
			}
			
			// Ajax request for processing form data.
			$.ajax({
			    type: "POST",
			    url: Customer_Vars.ajaxurl,
			    data: {
			        'action': 'client_form',
			        'security': Customer_Vars.nonce,
			        'name': formData.name.val(),
			        'message': formData.message.val(),
			        'phone': formData.phone.val(),
			        'email': formData.email.val(),
			        'budget': formData.budget.val(),
			        'date_time': formData.dateTime.val()
			    },

			    success: function( res ) {
			        Customer.displayError( Customer_Vars.message.success, 'success-notice' );

					form.find('input[name="name"]').val(''),
					form.find('input[name="phone"]').val(''),
					form.find('input[name="email"]').val(''),
					form.find('input[name="budget"]').val(''),
					form.find('input[name="message"]').val(''),
					form.find('input[name="date_time"]').val('')
			    },

			    error: function( res ) {
			    	alert(res.responseJSON.data.error);
			    }
			});
		},

		/**
		 * Validate customer relation data
		 */
		validate: function( form, formData ) {
			$('.form-control').removeClass('error-field');

			// Set form value
			var name = form.find('input[name="name"]').val(),
				nameLength    = form.find('input[name="name"]').attr('maxlength'),
				phone         = form.find('input[name="phone"]').val(),
				phoneLength   = form.find('input[name="name"]').attr('maxlength'),
				email         = form.find('input[name="email"]').val(),
				emailLength   = form.find('input[name="name"]').attr('maxlength'),
				budget        = form.find('input[name="budget"]').val(),
				budgetLength  = form.find('input[name="name"]').attr('maxlength'),
				message       = form.find('textarea[name="message"]').val(),
				messageLength = form.find('input[name="name"]').attr('maxlength'),
				dateTime      = form.find('input[name="date_time"]');
			
			// Name field is required
			if( name.length <= 0 ) {
				Customer.displayError( Customer_Vars.message.empty_name );
				Customer.highlightErroField('name');
				return false;
			}

			// Validate name length.
			if( name.length > parseInt( nameLength ) ) {
				Customer.displayError( Customer_Vars.message.name_lenght );
				Customer.highlightErroField('name');
				return false;
			}

			// Validate phone number.
			if( phone.length && phone.length > parseInt( phoneLength ) && ! Customer.isNumber(phone) ) {
				Customer.displayError( Customer_Vars.message.phone );
				Customer.highlightErroField('phone');
				return false;
			}

			// Validate email address.
			if ( email.length && email.length > parseInt( emailLength ) && ! Customer.isEmail(email) ) {
			    Customer.displayError( Customer_Vars.message.email );
			    Customer.highlightErroField('email');
				return false;
			}

			// Validate budget field.
			if ( budget.length && budget.length > parseInt( budgetLength ) && ! Customer.isNumeric(budget) ) {
				Customer.displayError( Customer_Vars.message.budget );
				Customer.highlightErroField('budget');
				return false;
			}

			return true;
		},

		/**
		 * Hilight customer relation form field, when its contain wrong data.
		 */
		highlightErroField: function( fieldName ) {
			$('input[name='+fieldName+']').addClass('error-field');
		},

		/**
		 * Display all kind of error message
		 */
		displayError: function ( message, classs ) {
			var wrap = $('.customer-display-error');
				handelar = classs || 'error-notice';
			
			wrap.addClass(handelar)
				.find('p')
				.html(message);

			wrap.find('.cancel-error')
				.removeClass('display-none');

			$('body, html').animate( { scrollTop: $('.customer-customer-form-wrap').offset().top-100}, 'slow' );
		},

		/**
		 * Email validation
		 */
		isEmail: function(email) {
			return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test( email );
		},

		/**
		 * Number validation
		 */
		isNumber: function(n) {
		    return n.match(/^[0-9]+$/);
		},

		/**
		 * Numeric number validation
		 */
		isNumeric: function(n) {
		    return !isNaN(parseFloat(n)) && isFinite(n);
		}
	}

	Customer.init();

})( jQuery );