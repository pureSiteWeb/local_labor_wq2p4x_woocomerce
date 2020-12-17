<?php

/**
 * Add the checkbox & tax number fields to the checkout
 */

add_filter( 'woocommerce_checkout_fields' , 'new_tax_number_checkout_field' );
  
function new_tax_number_checkout_field( $fields ) {
    $fields['billing']['checkbox_personak_customer'] = array(
        'type'      => 'checkbox',
        'label'     => __('Céges vásárlás', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true
    );
    $fields['billing']['tax_number_field'] = array(
        'label'     => __('Adószám', 'woocommerce'),
        'class'     => array('form-row-wide'),
        'clear'     => true,
        // 'required'  => true
    );
    
    return $fields;

}

// hide if not checked
add_action( 'woocommerce_after_checkout_form', 'tax_number_hide', 9999 );
  
function tax_number_hide() {
    
  wc_enqueue_js( "
      jQuery('input#checkbox_personak_customer').change(function(){
           
         if (! this.checked) {
            // HIDE IF NOT CHECKED
            jQuery('#tax_number_field_field').fadeOut();
            jQuery('#tax_number_field_field input').val('');         
         } else {
            // SHOW IF CHECKED
            jQuery('#tax_number_field_field').fadeIn();
         }
           
      }).change();
  ");
       
}

add_action('woocommerce_checkout_process', 'check_if_selected');
 
function check_if_selected() {

    if ( !empty( $_POST['checkbox_personak_customer'] && empty( $_POST['tax_number_field']) ))
		wc_add_notice( 'Céges vásárlás esetén adószám megadása kötelező', 'error' ); 
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