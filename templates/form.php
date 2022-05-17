<div class="customer-customer-form-wrap">

	<div class="customer-display-error">
		<p></p>
		<a href="#" class="cancel-error display-none">
			<span class="dashicons dashicons-no-alt"></span>
		</a>
	</div>

	<form action="" id="customer-form">
		<div class="form-group">
			<label><?php echo esc_attr( $attributes['name'] ); ?><em>*</em></label>
			<input 
				type="text" 
				name="name" 
				value=""  
				maxlength="<?php echo esc_attr( $attributes['name_length'] ); ?>"
				class="form-control"
				placeholder="<?php printf( __( 'Your full name (Character length %d)', 'customer' ), esc_attr( $attributes['name_length'] ) ); ?>" 
			/>
			<small class="form-text text-muted"><?php _e( 'Maximum length 30 character', 'customer' ); ?></small>
		</div>

		<div class="form-group">
			<label><?php echo esc_attr( $attributes['phone_number'] ); ?></label>
			<input 
				name="phone" 
				pattern="[0-9]+" 
				type="number" 
				oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
				min="0" 
				maxlength="<?php echo esc_attr( $attributes['phone_number_length'] ); ?>"
				list="defaultTels" 
				class="form-control"
				placeholder="<?php printf( __( 'Your phone number (Character length %d)', 'customer' ), esc_attr( $attributes['phone_number_length'] ) ); ?>" 
			/>
			<small class="form-text text-muted"><?php _e( 'Please insert only number (0-9)', 'customer' ); ?></small>
			<datalist id="defaultTels">
				<option value="8801716644910">
				<option value="8801736748915">
				<option value="8801716839496">
				<option value="8801788349585">
			</datalist>
		</div>

		<div class="form-group">
			<label><?php echo esc_attr( $attributes['email'] ); ?></label>
			<input 
				type="email" 
				name="email" 
				value="" 
				class="form-control"
				maxlength="<?php echo esc_attr( $attributes['email_length'] ); ?>"
				placeholder="<?php printf( __( 'Your email address (Character length %d)', 'customer' ), esc_attr( $attributes['email_length'] ) ); ?>" 
			/>
		</div>

		<div class="form-group">
			<label><?php echo esc_attr( $attributes['budget'] ); ?></label>
			<input 
				type="number" 
				step=any
				name="budget" 
				maxlength="<?php echo esc_attr( $attributes['budget_length'] ); ?>"
				min="1"
				value="" 
				class="form-control"
				placeholder="<?php printf( __( 'Your desired budget (Character length %d)', 'customer' ), esc_attr( $attributes['budget_length'] ) ); ?>" 
			/>
			<small class="form-text text-muted"><?php _e( 'Please type only number (digits and decimal point)', 'customer' ); ?></small>
		</div>

		<div class="form-group">
			<label><?php echo esc_attr( $attributes['message'] ); ?></label>
			<textarea 
				name="message" 
				class="form-control"
				maxlength="<?php echo esc_attr( $attributes['message_length'] ); ?>"
				placeholder="<?php printf( __( 'Details (Character length %d)', 'customer' ), esc_attr( $attributes['message_length'] ) ); ?>" 
				rows="<?php echo esc_attr( $attributes['rows'] ); ?>" 
				cols="<?php echo esc_attr( $attributes['cols'] ); ?>"></textarea>
		</div>

		<input 
			type="hidden" 
			name="date_time" 
			value="<?php echo esc_attr( customer_get_current_date_time_from_api() ); ?>"  
		/>

		<div class="form-group submit">
			<input type="submit" value="Submit" />
		</div>
	</form>
</div>
