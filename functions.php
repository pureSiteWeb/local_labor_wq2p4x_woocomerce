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
         'class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
         'label'	=> 'Céges vásárlás',
    ), $checkout->get_value( 'personal_customer' ));
}


// add 'tax number' field
add_action( 'woocommerce_after_checkout_billing_form', 'tax_number_checkout_field' );

// input field
function tax_number_checkout_field( $checkout ) {
     
    echo '<div class="tax_number" id="tax_number_data" style="display: none;">';
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
        update_post_meta( $order_id, 'Vásárló adószáma', sanitize_text_field( $_POST['tax_number_field'] ) );
    }
}


/**
 * Process the checkout
 */

add_action('woocommerce_checkout_process', 'tax_number_checkout_field_process');

function tax_number_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( ! $_POST['tax_number_field'] )
        wc_add_notice( __( 'Céges vásárlás esetén adószám kitöltése kötelező' ), 'error' );
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'tax_number_checkout_field_display_admin_order_meta', 10, 1 );

function tax_number_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('tax_number_field').':</strong> ' . get_post_meta( $order->id, 'tax_number_field', true ) . '</p>';
}

<script>
<console class="log"></console>
</script>

?>