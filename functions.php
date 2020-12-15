<?php

/**
 * Add the checkbox & tax number fields to the checkout
 */

// add 'checkbox' field
add_action( 'woocommerce_after_checkout_billing_form', 'personal_customer_checkbox' );

// checkbox field
function personal_customer_checkbox( $checkout){
     woocommerce_form_field( 'personal_customer' , array(
          'type'    => 'checkbox',
          'class'   => array('form-row-wide'),
          'label'	=> 'Céges vásárlás',
     ), $checkout->get_value( 'personal_customer' ));
}


// add 'tax number' field
add_action( 'woocommerce_after_checkout_billing_form', 'tax_number_checkout_field' );

// input field
function tax_number_checkout_field( $checkout ) {
     
     echo '<div>';
     woocommerce_form_field( 'tax_number_field', array(
          'type'    => 'text',
          'required'     => true,
          'class'   => array('form-row-wide'),
          'label'   => __('Adószám'),
     ), $checkout->get_value( 'tax_number_field' ));
     echo '</div>';

}

/**
 * Update the order meta with field value
 */

add_action( 'woocommerce_checkout_update_order_meta', 'tax_number_checkout_field_update_order_meta' );

function tax_number_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['tax_number_field'] ) ) {
        update_post_meta( $order_id, 'tax_number_field', sanitize_text_field( $_POST['tax_number_field'] ) );
    }
}

?>