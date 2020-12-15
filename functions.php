<?php

// source: https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/

/**
 * Add tax number field in the 'Checkout' page
 */

add_filter( 'woocommerce_checkout_fields' , 'tax_number_field_checkout' );

// Our hooked in function – $fields is passed via the filter!
function tax_number_field_checkout( $fields ) {
     $fields['billing']['tax_number'] = array(
          'label'     => __('Adószám', 'woocommerce'),
          'required'  => true,
          'class'     => array('form-row-wide'),
          'clear'     => true
          // 'priority'  => 20
     );

     return $fields;
}

?>