<?php

/**
 * Add the select filed to the checkout page
 */

add_filter( 'woocommerce_checkout_fields' , 'select_invoice' );
  
function select_invoice( $fields ) {
    $fields['billing']['select_tax_number'] = array(
        'type'          => 'select',
        'required'	    => true,
		'class'         => array('form-row-wide'),
		'label'         => 'Vásárló',
		'options'	=> array(
			'maganszemely'	=> 'Magánszemély',
			'vallalkozas'	=> 'Vállalkozás'
			)
        );

        return $fields;
}

/**
 * Add the tax number fields to the checkout page
 */

 // adoszam
add_filter( 'woocommerce_checkout_fields' , 'new_adoszam_checkout_field' );
  
function new_adoszam_checkout_field( $fields ) {
    $fields['billing']['adoszam_field'] = array(
        'type'      => 'number',
        'label'     => __('Adószám', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true,
        'required'	=> true,
    );
    
    return $fields;

}

// adoazonosito jel
add_filter( 'woocommerce_checkout_fields' , 'new_adoazonosito_checkout_field' );

function new_adoazonosito_checkout_field( $fields ) {
    $fields['billing']['adoazonosito_field'] = array(
        'type'      => 'number',
        'label'     => __('Adóazonosító jel', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true,
        'required'	=> true,
        'display'   => none
    );
    
    return $fields;

}

add_action( 'woocommerce_after_checkout_form', 'selected_tax_checkout_field', 9999 );
  
function selected_tax_checkout_field() {
   wc_enqueue_js( "
      jQuery('select#select_tax_number').change(function(){
         if (jQuery(this).val() == 'maganszemely') {
            jQuery('#adoszam_field_field').hide();
            jQuery('#adoszam_field').val('0');
            
            jQuery('#adoazonosito_field').val(''); 
            jQuery('#adoazonosito_field_field').show();

         } else {
            jQuery('#adoazonosito_field_field').hide();
            jQuery('#adoazonosito_field').val('0'); 

            jQuery('#adoszam_field').val('');
            jQuery('#adoszam_field_field').show();
         }
      }).change();
   ");
}


/**
 * Update the order meta with field value
 */

add_action( 'woocommerce_checkout_update_order_meta', 'tax_number_checkout_field_update_order_meta', 10, 2);

function tax_number_checkout_field_update_order_meta( $order_id, $posted ) {
    if ( isset( $posted['adoszam_field'] ) ) {
        update_post_meta( $order_id, '_adoszam_field', sanitize_text_field( $posted['adoszam_field'] ) );
    }
    if ( isset( $posted['adoazonosito_field'] ) ) {
        update_post_meta( $order_id, '_adoazonosito_field', sanitize_text_field( $posted['adoazonosito_field'] ) );
    }
}

/**
 * Display data to User on the 'Thank You Page'
 */

function display_order_data_to_user( $order_id ){  // display order received (_)
    echo $posted['adoazonosito_field'];

    if(  $posted['adoazonosito_field'] == 0){
        echo 'Adószám: ' . get_post_meta( $order_id, '_adoszam_field', true ) . '<br>';
    }
    else{
        echo 'Adóazonsító: ' . get_post_meta( $order_id, '_adoazonosito_field', true );
    }
    
    
}

add_action( 'woocommerce_thankyou', 'display_order_data_to_user', 20 );


/**
 * Display data value on the 'order edit' page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'display_tax_number_edit_order_page', 10, 1 );

function display_tax_number_edit_order_page($order){ // order details
    echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_adoszam_field', true ) . '</p>';
    echo '<p><strong>'.__('Adóazonsító').':</strong> ' . get_post_meta( $order->get_id(), '_adoazonosito_field', true ) . '</p>';
}

?>