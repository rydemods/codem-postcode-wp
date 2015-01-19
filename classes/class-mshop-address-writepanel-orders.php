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

if ( ! class_exists( 'MShop_Address_WritePanel_Orders' ) ) {
    
class MShop_Address_WritePanel_Orders {

    public function __construct() {
		add_action( 'woocommerce_checkout_update_order_meta',  array( &$this, 'mshop_checkout_update_order_meta'), 10, 2);
		add_filter( 'woocommerce_load_order_data',  array( &$this, 'mshop_load_order_data'));
		add_filter('woocommerce_admin_billing_fields',  array( &$this, 'mshop_admin_billing_fields'), 10, 1);
		add_filter('woocommerce_admin_shipping_fields',  array( &$this, 'mshop_admin_shipping_fields'), 10, 1);
		add_action( 'woocommerce_process_shop_order_meta',  array( &$this, 'mshop_process_shop_order_meta2'), 20, 2 );
		add_filter('woocommerce_found_customer_details',  array( &$this, 'mshop_found_customer_details'));
		add_filter('wsl_hook_process_login_alter_userdata',  array( &$this, 'mshop_hook_process_login_alter_userdata'), 10, 3);
		add_filter('woocommerce_order_formatted_billing_address',  array( &$this, 'woocommerce_order_formatted_billing_address'), 10, 1);
		add_filter('woocommerce_order_formatted_shipping_address',  array( &$this, 'woocommerce_order_formatted_shipping_address'), 10, 1);
		add_action('mshop_form_field_address',  array( &$this, 'echo_form_field_mshop_address'), 10, 2);
		add_action( 'woocommerce_checkout_process',  array( &$this, 'mshop_checkout_process') );
		
		add_action( 'admin_footer', array( &$this, 'admin_footer' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );		
    }
		
	public function admin_enqueue_scripts() {
		global $mshop_address;
		wp_register_script( 'ms-address', $mshop_address->plugin_url() . '/assets/js/mshop-address.js' );
		wp_register_script( 'ms-address-search', $mshop_address->plugin_url() . '/assets/js/mshop-address-search.js' );
		wp_register_script( 'jquery-magnific-popup', $mshop_address->plugin_url() . '/assets/js/jquery.magnific-popup.min.js?v=2.02', array( 'jquery' ), '2.1.1', true );
	    wp_enqueue_script( 'ms-address');
	    wp_enqueue_script( 'ms-address-search');
	    wp_enqueue_script( 'jquery-magnific-popup');

		wp_register_style( 'ms-address-search', $mshop_address->plugin_url() . '/assets/css/mshop-address-search.css' );
		wp_register_style( 'magnific-popup', $mshop_address->plugin_url() . '/assets/css/magnific-popup.css' );
		wp_enqueue_style( 'ms-address-search' );
		wp_enqueue_style( 'magnific-popup' );
    }

	public function admin_footer() {
		global $mshop_address;
	 	ob_start();

		if ( wp_is_mobile() ){
	        load_template( $mshop_address->plugin_path() . '/templates/mshop-address-search-mobile.php' );
		} else {
	        load_template( $mshop_address->plugin_path() . '/templates/mshop-address-search.php' );
		}

        echo ob_get_clean();		
	} 

	function mshop_checkout_update_order_meta( $order_id, $posted ) {
	    $billing_fields = array(
	                'mshop_billing_address-postnum', 
	                'mshop_billing_address-addr1', 
	                'mshop_billing_address-addr2',
	                );
	
	    $shipping_fields = array(
	                'mshop_billing_address-postnum' => 'mshop_shipping_address-postnum', 
	                'mshop_billing_address-addr1' => 'mshop_shipping_address-addr1', 
	                'mshop_billing_address-addr2' => 'mshop_shipping_address-addr2',
	                'shipping_email' => 'shipping_email',
	                'shipping_phone' => 'shipping_phone'
	                );
	               
	   	if($_POST['billing_country'] == 'KR'){ 
		    foreach($billing_fields as $key) {
		        update_post_meta( $order_id, '_' . $key, $_POST[$key]);
		    }
	    }
	
		if($_POST['shipping_country'] == 'KR'){    
		    foreach($shipping_fields as $key => $value) {
		    	if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) {
			        if ( $_POST['ship_to_different_address'] ) {
			        	update_post_meta( $order_id, '_' . $key, $_POST[$value]);
			        } else {
			        	update_post_meta( $order_id, '_' . $key, $_POST[$key]);
			        }
				} else {
			        if ( $_POST['shiptobilling'] ) {
			        	update_post_meta( $order_id, '_' . $key, $_POST[$value]);
			        } else {
			        	update_post_meta( $order_id, '_' . $key, $_POST[$key]);
			        }
				}
		    }
		}
		                    
	    $billing_postcode = $_POST['mshop_billing_address-postnum'];
	    $billing_address1 = $_POST['mshop_billing_address-addr1'];
	    $billing_address2 = $_POST['mshop_billing_address-addr2'];
	    $shipping_postcode = $_POST['mshop_shipping_address-postnum'];
	    $shipping_address1 = $_POST['mshop_shipping_address-addr1'];
	    $shipping_address2 = $_POST['mshop_shipping_address-addr2'];
	
		if($_POST['billing_country'] == 'KR'){ 
	    	update_post_meta( $order_id, '_billing_address_1', '(' . $billing_postcode . ') ' . $billing_address1 . ' ' . $billing_address2 );
		}
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) {
		    if ( !$_POST['ship_to_different_address'] ) {
		        $shipping_postcode = $_POST['mshop_billing_address-postnum'];
		        $shipping_address1 = $_POST['mshop_billing_address-addr1'];
		        $shipping_address2 = $_POST['mshop_billing_address-addr2'];
		    }
		} else {
		    if ( !$_POST['shiptobilling'] ) {
		        $shipping_postcode = $_POST['mshop_billing_address-postnum'];
		        $shipping_address1 = $_POST['mshop_billing_address-addr1'];
		        $shipping_address2 = $_POST['mshop_billing_address-addr2'];
		    }
		}
	
		if($_POST['shipping_country'] == 'KR'){    
	    	update_post_meta( $order_id, '_shipping_address_1', '(' . $shipping_postcode . ') ' . $shipping_address1 . ' ' . $shipping_address2 );
		}
	}
	
	
	function mshop_load_order_data($data) {
	    $data['shipping_email'] = '';
	    $data['shipping_phone'] = '';
	    return $data;
	}
	
	
	function mshop_admin_billing_fields($fields) {
		if(get_post_meta(get_the_ID(), '_billing_country', true) == 'KR'){
		    return array(
		    		'country' => $fields['country'],
		            'first_name' => array(
		                'label' => __( 'First Name', 'woocommerce' ),
		                'show'  => false
		                ),
		            'email' => array(
		                'label' => __( 'Email', 'woocommerce' ),
		                ),
		            'phone' => array(
		                'label' => __( 'Phone', 'woocommerce' ),
		                ),
		            );
		}else{
			return $fields;
		}            
	}
	
	
	function mshop_admin_shipping_fields($fields) {
		if(get_post_meta(get_the_ID(), '_shipping_country', true) == 'KR'){
		    return array(
		    		'country' => $fields['country'],
		            'first_name' => array(
		                'label' => __( 'First Name', 'woocommerce' ),
		                'show'  => false
		                ),
		            'email' => array(
		                'label' => __( 'Email', 'woocommerce' ),
		                ),
		            'phone' => array(
		                'label' => __( 'Phone', 'woocommerce' ),
		                ),
		            );
		}else{
			return $fields;
		}
	}
	
	function mshop_process_shop_order_meta2( $order_id, $posted ) {
	    $billing_fields = array(
	                    'mshop_billing_address-postnum', 
	                    'mshop_billing_address-addr1', 
	                    'mshop_billing_address-addr2'
	                    );
	    $shipping_fields = array(
	                    'mshop_shipping_address-postnum', 
	                    'mshop_shipping_address-addr1', 
	                    'mshop_shipping_address-addr2',
	                    );
	    $_billing_fields = array(
	                    '_billing_email',
	                    '_billing_phone'
	                    );                    
	    $_shipping_fields = array(
	                    '_shipping_email',
	                    '_shipping_phone',
	                    );                    
	
		if($_POST['_billing_country'] == 'KR'){ 
		    foreach($billing_fields as $key) {
		        update_post_meta( $order_id, '_' . $key, $_POST[ $key ] );
		    }
		    foreach($_billing_fields as $key) {
		        update_post_meta( $order_id, $key, $_POST[ $key ] );
		    }
			
		    $billing_postcode = $_POST['mshop_billing_address-postnum'];
		    $billing_address1 = $_POST['mshop_billing_address-addr1'];
		    $billing_address2 = $_POST['mshop_billing_address-addr2'];
		    update_post_meta( $order_id, '_billing_address_1', '(' . $billing_postcode . ') ' . $billing_address1 . ' ' . $billing_address2 );
	    }
		
		if($_POST['_shipping_country'] == 'KR'){ 
		    foreach($shipping_fields as $key) {
		        update_post_meta( $order_id, '_' . $key, $_POST[ $key ] );
		    }
		    foreach($_shipping_fields as $key) {
		        update_post_meta( $order_id, $key, $_POST[ $key ] );
		    }
		    $billing_postcode = $_POST['mshop_shipping_address-postnum'];
		    $billing_address1 = $_POST['mshop_shipping_address-addr1'];
		    $billing_address2 = $_POST['mshop_shipping_address-addr2'];
		    update_post_meta( $order_id, '_shipping_address_1', '(' . $billing_postcode . ') ' . $billing_address1 . ' ' . $billing_address2 );
	    }
	}
	
	
	function mshop_found_customer_details($customer_data) {
	    global $woocommerce;
	
	    $user_id = (int) trim(stripslashes($_POST['user_id']));
	    $type_to_load = esc_attr(trim(stripslashes($_POST['type_to_load'])));
	
		if($customer_data[$type_to_load . '_country'] == 'KR'){
		    $customer_data['mshop_' . $type_to_load . '_address_postnum'] = get_user_meta( $user_id, 'mshop_' . $type_to_load . '_address-postnum', true );
		    $customer_data['mshop_' . $type_to_load . '_address_addr1'] = get_user_meta( $user_id, 'mshop_' . $type_to_load . '_address-addr1', true );
		    $customer_data['mshop_' . $type_to_load . '_address_addr2'] = get_user_meta( $user_id, 'mshop_' . $type_to_load . '_address-addr2', true );
		}
		
	    return $customer_data;    
	}
	
	
	function mshop_hook_process_login_alter_userdata($userdata, $provider, $hybridauth_user_profile) {
	    $username = $userdata['last_name'] . $userdata['first_name'];
	    unset($userdata['first_name']);
	    $userdata['last_name'] = $username;
	    $userdata['display_name'] = $username;
	    return $userdata;
	}
	
	function woocommerce_order_formatted_billing_address($args) {
		if(get_post_meta(get_the_ID(), '_billing_country', true) == 'KR'){
		    $args['first_name'] = $args['last_name'] . $args['first_name']; 
		    $args['name'] = $args['first_name'];
		    $args['last_name'] = '';
		    $args['address_2'] = '';
		    $args['postcode'] = '';
		}
	    return $args;
	}
	
	function woocommerce_order_formatted_shipping_address($args) {
		if(get_post_meta(get_the_ID(), '_shipping_country', true) == 'KR'){
		    $args['first_name'] = $args['last_name'] . $args['first_name']; 
		    $args['name'] = $args['first_name'];
		    $args['last_name'] = '';
		    $args['address_2'] = '';
		    $args['postcode'] = '';
		}
	    return $args;
	}
	
	
	function echo_form_field_mshop_address($orderid, $value) {
	    global $woocommerce, $mshop_address;
	        
		if(empty($value['class']))
			$value['class'] = array();
				
	    wp_register_style('mshop_plugin', $mshop_address->plugin_url() . '/assets/css/codemstyle.css');
	    wp_enqueue_style('mshop_plugin');
	    wp_deregister_script('selectBox');
	        
	    $postnum  = get_post_meta( $orderid, '_' . esc_attr( $value['id'] ) . '-postnum', true );
	    $addr1  = get_post_meta( $orderid, '_' . esc_attr( $value['id'] ) . '-addr1', true );
	    $addr2  = get_post_meta( $orderid, '_' . esc_attr( $value['id'] ) . '-addr2', true );
	    $fields = '';
	
	    $fields .= '<p class="form-row form-row-wide ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value['id'] ) . '-postnum_field">';
	    $fields .= '    <input type="text" class="postnum" placeholder="우편번호" id="' . esc_attr( $value["id"] ) . '-postnum" name="' . esc_attr( $value['id'] ) . '-postnum" value="' . $postnum . '" style="width:80px" readonly>';
	    $fields .= '    <input href="#ms_addr_1" type="button" class="ms_addr_1 ms-open-popup-link" readonly="readonly" onfocus="this.blur();" value="주소 검색"></button>';
	    $fields .= '</p>';
	    $fields .= '<p class="form-row form-row-wide ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value['id'] ) . '-addr1_field">';
	    $fields .= '    <input type="text" class="addr1" placeholder="기본주소" id="' . esc_attr( $value["id"] ) . '-addr1" name="' . esc_attr( $value['id'] ) . '-addr1" value="' . $addr1 . '" style="width:100%" readonly>';
	    $fields .= '</p>';
	    $fields .= '<p class="form-row form-row-wide ' . esc_attr( implode( ' ', $value['class'] ) ) .'" id="' . esc_attr( $value['id'] ) . '-addr2_field">';
	    $fields .= '    <input type="text" class="addr2" placeholder="상세주소" id="' . esc_attr( $value["id"] ) . '-addr2" name="' . esc_attr( $value['id'] ) . '-addr2" value="' . $addr2 . '" style="width:100%" ><br class="clear" />';
	    $fields .= '</p>';
	    echo $fields; 
	}
	
	
	function mshop_checkout_process() {
	    global $woocommerce;
	    
		if($_POST['billing_country'] == 'KR'){
		    if($_POST['mshop_billing_address-addr1'] == "")         
		        $woocommerce->add_error( '<strong>청구지 기본주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
		    if($_POST['mshop_billing_address-addr2'] == "")         
		        $woocommerce->add_error( '<strong>청구지 상세주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
		
		}
		
		if($_POST['shipping_country'] == 'KR'){
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) {
			    if (isset($_POST['ship_to_different_address'])) {
			        if($_POST['mshop_shipping_address-addr1'] == "")         
			            $woocommerce->add_error( '<strong>배송지 기본주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
			        if($_POST['mshop_shipping_address-addr2'] == "")         
			            $woocommerce->add_error( '<strong>배송지 상세주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
			    }
			} else {
			    if (isset($_POST['shiptobilling'])) {
			        if($_POST['mshop_shipping_address-addr1'] == "")         
			            $woocommerce->add_error( '<strong>배송지 기본주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
			        if($_POST['mshop_shipping_address-addr2'] == "")         
			            $woocommerce->add_error( '<strong>배송지 상세주소</strong> ' . __( 'is a required field.', 'woocommerce' ) );
			    }
			}
		}
		
	    if(is_user_logged_in() && $_POST['billing_country'] == 'KR') {
	        $load_address = 'billing';
	        $postcode = $_POST['mshop_' . $load_address . '_address-postnum'];
	        $address1 = $_POST['mshop_' . $load_address . '_address-addr1'];
	        $address2 = $_POST['mshop_' . $load_address . '_address-addr2'];
	        $user_id = get_current_user_id();
	    
	        update_user_meta( $user_id, 'mshop_' . $load_address . '_address-postnum', $postcode );
	        update_user_meta( $user_id, 'mshop_' . $load_address . '_address-addr1', $address1 );
	        update_user_meta( $user_id, 'mshop_' . $load_address . '_address-addr2', $address2 );
	        
	        update_user_meta( $user_id, $load_address . '_address_1', '(' . $postcode . ') ' . $address1 . ' ' . $address2);
	    }
	}
		    
}
    
new MShop_Address_WritePanel_Orders();
}

