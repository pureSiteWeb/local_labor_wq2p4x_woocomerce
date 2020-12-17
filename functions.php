<?php

/**
 * Add the select filed to the checkout page
 */

// select
add_action( 'woocommerce_after_checkout_billing_form', 'misha_select_field' );

function misha_select_field( $checkout ){

	woocommerce_form_field( 'contactmethod', array(
		'type'          => 'select', // text, textarea, select, radio, checkbox, password, about custom validation a little later
		'required'	=> true, // actually this parameter just adds "*" to the field
		'class'         => array('misha-field', 'form-row-wide'), // array only, read more about classes and styling in the previous step
		'label'         => 'Preferred contact method',
		'label_class'   => 'misha-label', // sometimes you need to customize labels, both string and arrays are supported
		'options'	=> array( // options for <select> or <input type="radio" />
			'phone'	=> 'phone', // 'value'=>'Name'
			'email'	=> 'email'
			)
		), $checkout->get_value( 'contactmethod' ) ); 
}

/**
 * Add the tax number fields to the checkout page
 */

 // adoszam
add_filter( 'woocommerce_checkout_fields' , 'new_adoszam_checkout_field' );
  
function new_adoszam_checkout_field( $fields ) {
    $fields['billing']['adoszam_field'] = array(
        'label'     => __('Adószám', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true,
        'required'  => true,
    );
    
    return $fields;

}

//adoazonosito
add_filter( 'woocommerce_checkout_fields' , 'new_adoazonosito_checkout_field' );

function new_adoazonosito_checkout_field( $fields ) {
    $fields['billing']['adoazonosito_field'] = array(
        'label'     => __('Adó azonosító jel', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true,
        'required'  => true,
    );
    
    return $fields;

}

add_action( 'woocommerce_after_checkout_form', 'bbloomer_conditionally_hide_show_checkout_field', 9999 );
  
function bbloomer_conditionally_hide_show_checkout_field() {
   wc_enqueue_js( "
      jQuery('select#contactmethod').change(function(){
         if (jQuery(this).val() == 'phone') {
            jQuery('#adoszam_field_field').show();
            jQuery('#adoazonosito_field_field').fadeOut();
         } else {
            jQuery('#adoazonosito_field_field').show();
            jQuery('#adoszam_field_field').hide();
         }
      }).keyup();
   ");
}

/**
 * Update the order meta with field value
 */

add_action( 'woocommerce_checkout_update_order_meta', 'tax_number_checkout_field_update_order_meta', 10, 2);

function tax_number_checkout_field_update_order_meta( $order_id, $posted ) {
    if ( isset( $posted['tax_number_field'] ) ) {
        update_post_meta( $order_id, '_tax_number_field', sanitize_text_field( $posted['tax_number_field'] ) );
    }
}


// Display data to User on the 'Thank You Page'
function display_order_data_to_user( $order_id ){
    echo 'Adószám: ' . get_post_meta( $order_id, '_tax_number_field', true ); //  display order received (_)
}

add_action( 'woocommerce_thankyou', 'display_order_data_to_user', 20 );


/**
 * Display data value on the 'order edit' page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'display_tax_number_edit_order_page', 10, 1 );

function display_tax_number_edit_order_page($order){
    echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_tax_number_field', true ) . '</p>'; // order details
}

?>