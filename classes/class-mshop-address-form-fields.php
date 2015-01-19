<?php
/*
 * [ 우커머스 버전 지원 안내 ]
 * 워드프레스 버전 : WordPress 4.0
 * 우커머스 버전 : WooCommerce 2.2.x
 * 
 * [ 코드엠 플러그인 라이센스 규정 ]
 * (주)코드엠에서 개발된 워드프레스  플러그인을 사용하시는 분들에게는 다음 사항에 대한 동의가 있는 것으로 간주합니다.
 * 1. 코드엠에서 개발한 워드프레스 우커머스용 바로구매 플러그인의 저작권은 (주)코드엠에게 있습니다.
 * 2. 플러그인은 사용권을 구매하는 것이며, 프로그램 저작권에 대한 구매가 아닙니다.
 * 3. 플러그인을 구입하여 다수의 사이트에 복사하여 사용할 수 없으며, 1개의 라이센스는 1개의 사이트에만 사용할 수 있습니다. 이를 위반 시 지적 재산권에 대한 손해 배상 의무를 갖습니다.
 * 4. 플러그인은 구입 후 1년간 업데이트를 지원합니다.
 * 5. 플러그인은 워드프레스, 테마, 플러그인과의 호환성에 대한 책임이 없습니다.
 * 6. 플러그인 설치 후 버전에 관련한 운용 및 관리의 책임은 사이트 당사자에게 있습니다.
 * 7. 다운로드한 플러그인은 환불되지 않습니다.
 */

add_filter('woocommerce_formatted_address_replacements', 'mshop_formatted_address_replacements', 10, 1);

function mshop_formatted_address_replacements($args) {
	$args['{first_name}'] = $args['{last_name}'] . $args['{first_name}'];
	$args['{name}'] = $args['{first_name}'];
	$args['{last_name}'] = '';
	$args['{address_2}'] = '';
	$args['{postcode}'] = '';
	$args['{city}'] = '';
	return $args;
}

add_action( 'woocommerce_before_my_account', 'mshop_woocommerce_before_my_account', 10 );

function mshop_migration_address( $load_address ) {
	if( 'yes' != get_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_migration', true ) ){

		update_user_meta(get_current_user_id(), 'mshop_' . $load_address . '_migration', 'yes' );

		if( get_user_meta( get_current_user_id(), $load_address . '_first_name_kr', true ) == '' ){
			$username = get_user_meta( get_current_user_id(), $load_address . '_last_name', true ) . get_user_meta( get_current_user_id(), $load_address . '_first_name', true );
			update_user_meta( get_current_user_id(), $load_address . '_first_name_kr', $username );
		}
		if( get_user_meta( get_current_user_id(), $load_address . '_email_kr', true ) == '' ){
			$email = get_user_meta( get_current_user_id(), $load_address . '_email', true );
			update_user_meta( get_current_user_id(), $load_address . '_email_kr', $email );
		}
		if( get_user_meta( get_current_user_id(), $load_address . '_phone_kr', true ) == '' ){
			$phone = get_user_meta( get_current_user_id(), $load_address . '_phone', true );
			update_user_meta( get_current_user_id(), $load_address . '_phone_kr', $phone );
		}
		if( get_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-postnum', true ) == '' ){
			$postcode = get_user_meta( get_current_user_id(), $load_address . '_postcode', true );
			update_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-postnum', $postcode );
		}
		if( get_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-addr1', true ) == '' ){
			$address1 = get_user_meta( get_current_user_id(), $load_address . '_city', true ) . get_user_meta( get_current_user_id(), $load_address . '_address_2', true );
			update_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-addr1', $address1 );
		}
		if( get_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-addr2', true ) == '' ){
			$address2 = get_user_meta( get_current_user_id(), $load_address . '_address_1', true );
			update_user_meta( get_current_user_id(), 'mshop_' . $load_address . '_address-addr2', $address2 );

			update_user_meta( get_current_user_id(), $load_address . '_address_1', '(' . $postcode . ') ' . $address1 . ' ' . $address2);
		}
	}
}
function mshop_woocommerce_before_my_account(){
	mshop_migration_address( 'billing' );
	mshop_migration_address( 'shipping' );
}

add_filter('woocommerce_billing_fields', 'mshop_billing_fields', 999, 2);
add_filter('woocommerce_shipping_fields', 'mshop_shipping_fields', 999, 2);

function mshop_billing_fields($address, $country) {
	if( ( $_POST['action'] == 'edit_address' || $_REQUEST['action'] == 'woocommerce_checkout' ) && $country == "KR"){
		foreach($address as $key => $value){
			$address[$key]['required'] = false;
		}
	};

	mshop_woocommerce_before_my_account();

	$address['billing_first_name_kr'] = $address['billing_first_name'];
	$address['billing_first_name_kr']['clear'] = true;
	$address['billing_first_name_kr']['class'] = array( 'form-row-first mshop_addr_title mshop-enable-kr' );
	$address['billing_first_name_kr']['required'] = true;

	$address['mshop_address'] = array(
		'id' => 'mshop_billing_address',
		'type' => 'mshop_address',
		'title' => __( 'Address', 'mshop-address' ),
		'class' => array('validate-required mshop-enable-kr')
	);

	$address['billing_email_kr'] = $address['billing_email'];
	$address['billing_email_kr']['class'] = array( 'form-row-first mshop_addr_title mshop-enable-kr' );
	$address['billing_email_kr']['required'] = true;

	$address['billing_phone_kr'] = $address['billing_phone'];
	$address['billing_phone_kr']['clear'] = true;
	$address['billing_phone_kr']['class'] = array( 'form-row-last mshop_addr_title mshop-enable-kr' );
	$address['billing_phone_kr']['required'] = true;

	return $address;
}

function mshop_shipping_fields($address, $country) {
	if( ( $_POST['action'] == 'edit_address' || $_REQUEST['action'] == 'woocommerce_checkout' ) && $country == "KR"){
		foreach($address as $key => $value){
			$address[$key]['required'] = false;
		}
	};

	mshop_woocommerce_before_my_account();

	$address['shipping_first_name_kr'] = $address['shipping_first_name'];
	$address['shipping_first_name_kr']['clear'] = true;
	$address['shipping_first_name_kr']['class'] = array( 'form-row-first mshop_addr_title mshop-enable-kr' );
	$address['shipping_first_name_kr']['required'] = true;

	$address['mshop_address'] = array(
		'id' => 'mshop_shipping_address',
		'type' => 'mshop_address',
		'title' => __( 'Address', 'mshop-address' ),
		'class' => array('validate-required mshop-enable-kr')
	);
	$address['shipping_email'] = array(
		'label'         => __( 'Email Address', 'woocommerce' ),
		'type'          => 'text',
		'required'      => true,
		'class'         => array( 'form-row-first mshop_addr_title mshop-enable-kr' ),
		'validate'      => array( 'email' ),
	);
	$address['shipping_phone'] = array(
		'label'         => __( 'Phone', 'woocommerce' ),
		'type'          => 'text',
		'required'      => true,
		'class'         => array( 'form-row-last mshop_addr_title mshop-enable-kr' ),
		'clear'         => true
	);

	return $address;
}


function generate_form_field_mshop_address($userid, $value) {
	global $woocommerce, $mshop_address;

	$allow_custom = ('yes' == get_option( 'mshop_address_user_can_write_address', 'no' ) );

	if(empty($value['class']))
		$value['class'] = array();

	wp_register_style('mshop_plugin', $mshop_address->plugin_url() . '/assets/css/codemstyle.css');
	wp_enqueue_style('mshop_plugin');

	wp_deregister_script('selectBox');

	$postnum  = get_user_meta( $userid, esc_attr( $value['id'] ) . '-postnum', true );
	$addr1  = get_user_meta( $userid, esc_attr( $value['id'] ) . '-addr1', true );
	$addr2  = get_user_meta( $userid, esc_attr( $value['id'] ) . '-addr2', true );

	$fields .= '<br class="clear" />';
	$fields .= '<p class="form-row form-row-wide ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value["id"] ) . '-postnum_field">';
	$fields .= '    <input type="text" class="input-text postnum" placeholder="' . get_option('mshop_address_placeholder_postnum', '우편번호') . '" id="' . esc_attr( $value["id"] ) . '-postnum" name="' . esc_attr( $value['id'] ) . '-postnum" value="' . $postnum . '" style="width:80px" ' . ($allow_custom ? '' : 'readonly') . '>';
	$fields .= '    <input href="#ms_addr_1" type="button" class="ms_addr_1 ms-open-popup-link" readonly="readonly" onfocus="this.blur();" value="' . get_option('mshop_address_search_button_text', '주소 검색') . '"></button>';
	$fields .= '</p>';
	$fields .= '<p class="form-row form-row-first ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value["id"] ) . '-addr1_field">';
	$fields .= '        <input type="text" class="input-text regular-text addr1" placeholder="' . get_option('mshop_address_placeholder_addr1', '기본주소') . '" id="' . esc_attr( $value["id"] ) . '-addr1" name="' . esc_attr( $value['id'] ) . '-addr1" value="' . $addr1 . '" ' . ($allow_custom ? '' : 'readonly') . '>';
	$fields .= '</p>';
	$fields .= '<p class="form-row form-row-last ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value["id"] ) . '-addr2_field">';
	$fields .= '        <input type="text" class="input-text regular-text addr2" placeholder="' . get_option('mshop_address_placeholder_addr2', '상세주소') . '" id="' . esc_attr( $value["id"] ) . '-addr2" name="' . esc_attr( $value['id'] ) . '-addr2" value="' . $addr2 . '" ><br class="clear" />';
	$fields .= '</p>';

	return $fields;
}

function update_customer_address($user_id, $load_address) {
	$load_address = ($load_address == 'billing') ? $load_address : 'shipping';

	if($_POST[$load_address . '_country'] == 'KR'){
		$postcode = $_POST['mshop_' . $load_address . '_address-postnum'];
		$address1 = $_POST['mshop_' . $load_address . '_address-addr1'];
		$address2 = $_POST['mshop_' . $load_address . '_address-addr2'];

		update_user_meta( $user_id, 'mshop_' . $load_address . '_address-postnum', $postcode );
		update_user_meta( $user_id, 'mshop_' . $load_address . '_address-addr1', $address1 );
		update_user_meta( $user_id, 'mshop_' . $load_address . '_address-addr2', $address2 );

		update_user_meta( $user_id, $load_address . '_address_1', '(' . $postcode . ') ' . $address1 . ' ' . $address2);

		if($load_address == 'billing'){
			update_user_meta( $user_id, 'billing_first_name', $_POST['billing_first_name_kr'] );
			update_user_meta( $user_id, 'billing_last_name', '' );
			update_user_meta( $user_id, 'billing_email', $_POST['billing_email_kr'] );
			update_user_meta( $user_id, 'billing_phone', $_POST['billing_phone_kr'] );

			update_user_meta( $user_id, 'billing_first_name_kr', $_POST['billing_first_name_kr'] );
			update_user_meta( $user_id, 'billing_email_kr', $_POST['billing_email_kr'] );
			update_user_meta( $user_id, 'billing_phone_kr', $_POST['billing_phone_kr'] );
		}else{
			update_user_meta( $user_id, 'shipping_first_name', $_POST['shipping_first_name_kr'] );
			update_user_meta( $user_id, 'shipping_last_name', '' );
			update_user_meta( $user_id, 'shipping_email', $_POST['shipping_email'] );
			update_user_meta( $user_id, 'shipping_phone', $_POST['shipping_phone'] );

			update_user_meta( $user_id, 'shipping_first_name_kr', $_POST['shipping_first_name_kr'] );
		}
	}
}

add_filter( 'woocommerce_process_checkout_field_billing_first_name', 'mshop_woocommerce_process_checkout_field_billing_first_name' );
add_filter( 'woocommerce_process_checkout_field_billing_email', 'mshop_woocommerce_process_checkout_field_billing_email' );
add_filter( 'woocommerce_process_checkout_field_billing_phone', 'mshop_woocommerce_process_checkout_field_billing_phone' );
add_filter( 'woocommerce_process_checkout_field_shipping_first_name', 'mshop_woocommerce_process_checkout_field_shipping_first_name' );

function mshop_woocommerce_process_checkout_field_billing_first_name($value){
	if( $_POST['billing_country'] != 'KR' || empty($_POST['billing_first_name_kr']) ){
		return $value;
	}else{
		return $_POST['billing_first_name_kr'];
	}
}

function mshop_woocommerce_process_checkout_field_billing_email($value){
	if( $_POST['billing_country'] != 'KR' || empty($_POST['billing_email_kr']) ){
		return $value;
	}else{
		return $_POST['billing_email_kr'];
	}
}

function mshop_woocommerce_process_checkout_field_billing_phone($value){
	if( $_POST['billing_country'] != 'KR' || empty($_POST['billing_phone_kr']) ){
		return $value;
	}else{
		return $_POST['billing_phone_kr'];
	}
}

function mshop_woocommerce_process_checkout_field_shipping_first_name($value){
	if( $_POST['shipping_country'] != 'KR' || empty($_POST['shipping_first_name_kr']) ){
		return $value;
	}else{
		return $_POST['shipping_first_name_kr'];
	}
}

add_action( 'wp_head', 'mshop_address_wp_head' );

function mshop_address_wp_head() {
	echo '<style type="text/css">' . get_option( 'mshop_address_custom_css') . '</style>';
}

