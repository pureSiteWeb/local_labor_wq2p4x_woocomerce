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
        'required'  => true
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

/**
 * Update the order meta with field value
 */

add_action( 'woocommerce_checkout_update_order_meta', 'tax_number_checkout_field_update_order_meta' );

function tax_number_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['tax_number_field'] ) ) {
        update_post_meta( $order_id, 'Vásárló adószáma', sanitize_text_field( $_POST['tax_number_field'] ) );
    }
}


//--


/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_tax_number_field', true ) . '</p>';
}

?>