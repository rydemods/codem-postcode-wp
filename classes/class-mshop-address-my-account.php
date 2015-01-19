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
 
add_action('woocommerce_customer_save_address', 'mshop_customer_save_address', 10, 2);

function mshop_customer_save_address($user_id, $load_address) {
    if ( $user_id && $load_address == 'billing') {
        update_user_meta( $user_id, 'first_name', $_POST['billing_first_name']);
        update_user_meta( $user_id, 'last_name', '');
        update_user_meta( $user_id, 'nickname', $_POST['billing_first_name']);
    }
                
    update_customer_address($user_id, $load_address);
}

add_filter('woocommerce_form_field_mshop_address', 'mshop_form_field_mshop_address' , 10 , 3);

function mshop_form_field_mshop_address($key, $arg, $value) {
    return generate_form_field_mshop_address(get_current_user_id(), $value);
}

function mshop_edit_account_infomation($user_id){
	global $current_user;
	get_currentuserinfo();
	
	//Mshop User 문구를 lastname 으로 저장한 사용자라면, lastname을 비워준다.
	if( $current_user->user_lastname == 'MSHOP_USER' ) {
		update_user_meta($user_id, 'last_name', '', 'MSHOP_USER');	
	}
}
add_action('woocommerce_save_account_details', 'mshop_edit_account_infomation', 15, 1);


add_action( 'wp_footer', 'mshop_address_wp_footer' );

function mshop_address_wp_footer() {
	$url = parse_url( home_url() );
	$request = str_replace( $url['path'], '', $_SERVER['REQUEST_URI'] );

	if ( $request == get_option("mshop_address_edit_account_url", '/my-account/edit-account/') ){
		?>
		<script ype='text/javascript'>
			jQuery('form input#account_last_name').val('-');
			jQuery('form input#account_last_name').closest('p').css('display', 'none');
		</script>
	<?php
	}
}