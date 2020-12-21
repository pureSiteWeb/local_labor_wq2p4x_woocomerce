<?php 
add_action( 'woocommerce_after_checkout_billing_form', 'new_tax_number_checkout_chechbox' );

function new_tax_number_checkout_chechbox( $checkout ){
	woocommerce_form_field( 'select_customer', array(
		'type'          => 'select',
		'required'		=> true,
		'class'         => array('form-row'),
		//'label_class'	=> 'label',
		'label'         => 'Vásárlás típusa',
		'options'	=> array(
			''		=> 'Kérem válasszon',
			'maganszemely'	=> 'Magánszemély',
			'vallalkozas'	=> 'Vállalkozás'
			)
		), $checkout->get_value( 'select_customer' ) ); 
}

add_action( 'woocommerce_after_checkout_billing_form', 'new_tax_number_checkout_field' );

function new_tax_number_checkout_field( $checkout ){
	woocommerce_form_field( 'tax_number_field', array(
		'type'          => 'text',
		'required'		=> true,
		'class'         => array('form-row-wide'),
		'label'         => 'Adószám',
		'clear'     	=> true,
		), $checkout->get_value( 'tax_number_field' ) ); 
}

// hide if not checked
add_action( 'woocommerce_after_checkout_form', 'tax_number_hide', 9999 );
  
function tax_number_hide() {
    
  wc_enqueue_js( "
      jQuery('select#select_customer').change(function(){
		  if (jQuery(this).val() == 'vallalkozas') {
			  jQuery('#tax_number_field_field').fadeIn();
			  jQuery('#tax_number_field').val('');
		  }
		  else{
			  jQuery('#tax_number_field_field').hide();
			  jQuery('#tax_number_field').val('-');			  
		  }
		  
      }).change();
  ");
       
}

// required if checked
add_action('woocommerce_checkout_process', 'check_if_selected');
 
function check_if_selected() {

    if ( empty( $_POST['select_customer']) )
		wc_add_notice( 'Kérem válasszon, hogy magánszemélyként vagy vállalkozóként vásárol', 'error' );
	
	if ( !empty( $_POST['select_customer']) && empty( $_POST['tax_number_field']) )
		wc_add_notice( 'Céges vásárlás esetén adószám megadása kötelező', 'error' ); 
}

// save fields to order meta
add_action( 'woocommerce_checkout_update_order_meta', 'misha_save_what_we_added' );
// save field values
function misha_save_what_we_added( $order_id ){
	update_post_meta( $order_id, 'tax_number_field', sanitize_text_field( $_POST['tax_number_field']));
}

/**
 * Update the order meta with field value
 */
 
add_action( 'woocommerce_checkout_update_order_meta', 'tax_number_checkout_field_update_order_meta' );

function tax_number_checkout_field_update_order_meta( $order_id ) {
	update_post_meta( $order_id, '_tax_number_field', sanitize_text_field( $_POST['tax_number_field'] ) );
}

// Display data to User on the 'Thank You Page'
function display_order_data_to_user( $order_id ){
	if(  get_post_meta( $order_id, '_tax_number_field', true ) != '-'){
		echo 'Adószám: ' . get_post_meta( $order_id, '_tax_number_field', true ); //  display order received (_)
	}
}

add_action( 'woocommerce_thankyou', 'display_order_data_to_user', 20 );


/**
 * Display data value on the 'order edit' page
 */
 
add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_tax_number_edit_order_page');

function display_tax_number_edit_order_page($order){
    echo '<p><strong>'.__('Adószám').':</strong> ' . get_post_meta( $order->get_id(), '_tax_number_field', true ) . '</p>'; // order details
}


/**
 * Add a custom field (in an order) to the emails
 */
add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );

function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
    $fields['tax_number_field'] = array(
        'label' => __( 'Adószám' ),
        'value' => get_post_meta( $order->id, 'tax_number_field', true ),
    );
    return $fields;
}

?>
