<?php

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function – $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
     $fields['billing']['billing_anumber'] = array(
        'label'     => __('Adószám', 'woocommerce'),
    'placeholder'   => _x('Adószám', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}


/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_thankyou', 'my_custom_view_order', 20 );

function my_custom_view_order($order){
    //echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_billing_anumber', true ) . '</p>';
    echo "LOL";
}

/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_billing_anumber', true ) . '</p>';
}



?>