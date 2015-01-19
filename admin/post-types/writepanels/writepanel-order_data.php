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
 
add_action( 'woocommerce_admin_order_data_after_billing_address', 'mshop_admin_order_data_after_billing_address' );

function mshop_admin_order_data_after_billing_address($order) {
	if(get_post_meta(get_the_ID(), '_billing_country', true) == 'KR'){
	    $address = array(
	            'id' => 'mshop_billing_address',
	            'label' => __( 'Address', 'mshop_address' ),
	            'type' => 'mshop_address'
	        );
	    $hidden = array(
	            'label' => __( 'Phone', 'mshop_address' ),
	            'type' => 'hidden',
	            'show' => false                
	        );
	    echo '<div class="edit_address">';
	    do_action( "mshop_form_field_address", $order->id, $address );
	    woocommerce_wp_hidden_input( $address );
	    echo '</div>';
	}
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'mshop_admin_order_data_after_shipping_address' );

function mshop_admin_order_data_after_shipping_address($order) {
	if(get_post_meta(get_the_ID(), '_shipping_country', true) == 'KR'){
	    $address = array(
	            'id' => 'mshop_shipping_address',
	            'label' => __( 'Address', 'mshop_address' ),
	            'type' => 'mshop_address'
	            );
	    $hidden = array(
	            'label' => __( 'Phone', 'mshop_address' ),
	            'type' => 'hidden',
	            'show' => false                
	        );
	    echo '<div class="edit_address">';
	    do_action( "mshop_form_field_address", $order->id, $address );
	    woocommerce_wp_hidden_input( $address );
	    echo '</div>';
    }
}

add_action( 'woocommerce_admin_order_totals_after_shipping', 'mshop_admin_order_totals_after_shipping', 10 );

function mshop_admin_order_totals_after_shipping($postid) {
    $html = '<script type="text/javascript">';
    $html .= '$ = jQuery.noConflict();';  
    $html .= '$("document").ready(function(){';
    $html .= '    rebindLoadCustomerAddress();';
    $html .= '});';
    $html .= '</script>';
    echo $html;
}
