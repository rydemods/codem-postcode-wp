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
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MShop_Settings_Address' ) ) :

class MShop_Settings_Address {
	protected $id    = '';
	protected $label = '';
	public $hasmsg = '';
	
	public function __construct() {
		$this->id    = 'mshop_address';
		$this->label = __( 'Address', 'mshop_address' );
		$this->hasmsg = false;

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 100 );
	    if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '>=' ) ) {
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ), 100 );
		} else {
			add_action( 'woocommerce_settings_tabs_' . $this->id, array( $this, 'output' ), 100 );
		}
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;
		return $pages;
	}

	public function get_settings() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return apply_filters('mshop_wpml_get_setting', array(

		    array( 'title' => __( 'Address Option', 'mshop_address' ), 'type' => 'title', 'desc' => '', 'id' => 'address_option' ),
		    
		    array(
		        'title'     => __( '플러그인 활성화', 'mshop_address' ),
		        'id'        => 'mshop_address_enable',
		        'type'      => 'checkbox',
		        'desc'		=> '사용',
		    ), 	
		    
			array(
		        'title'     => __( '내정보 수정 페이지', 'mshop_address' ),
		        'id'         => 'mshop_address_edit_account_url',
		        'css'         => 'width:100%',
		        'default'    => '/my-account/edit-account/',
		        'type'         => 'text',
		        'desc' => '나의 계정 > 내정보 수정 페이지 경로를 입력해주세요. 기본값 : <span style="font-style: italic;background-color:#e6e6e6;">/my-account/edit-account/</span>'
		    ),
			array(
				'title'     => __( 'Custom CSS', 'mshop_address' ),
				'id'         => 'mshop_address_custom_css',
				'css'         => 'width: 100%;height:100px',
				'default'    => '',
				'type'         => 'textarea'
			),
			array(
				'title'     => __( '주소 검색 버튼 텍스트', 'mshop_ajax_login' ),
				'id'         => 'mshop_address_search_button_text',
				'desc'    => '<br>주소 입력 화면에서 주소 검색 버튼의 텍스트를 지정합니다.',
				'css'         => 'width: 50%',
				'default'    => '주소 검색',
				'type'         => 'text'
			),
			array(
				'title'     => __( '사용자 입력 허용', 'mshop_ajax_login' ),
				'id'         => 'mshop_address_user_can_write_address',
				'desc'    => '사용자가 주소를 직접 입력 할 수 있도록 합니다.',
				'css'         => 'width: 50%',
				'default'    => 'no',
				'type'         => 'checkbox'
			),

			array( 'type' => 'sectionend', 'id' => 'address_option' ),

			array( 'title' => __( 'Place Holder 문구 설정', 'mshop_address' ), 'type' => 'title', 'desc' => '', 'id' => 'address_placeholder_option' ),
			array(
				'title'     => __( '우편번호', 'mshop_ajax_login' ),
				'id'         => 'mshop_address_placeholder_postnum',
				'desc'    => '<br>우편번호 입력필드의 Placeholder 텍스트를 지정합니다.',
				'css'         => 'width: 50%',
				'default'    => '우편번호',
				'type'         => 'text'
			),
			array(
				'title'     => __( '기본주소', 'mshop_ajax_login' ),
				'id'         => 'mshop_address_placeholder_addr1',
				'desc'    => '<br>기본주소 입력필드의 Placeholder 텍스트를 지정합니다.',
				'css'         => 'width: 50%',
				'default'    => '기본주소',
				'type'         => 'text'
			),
			array(
				'title'     => __( '상세주소', 'mshop_ajax_login' ),
				'id'         => 'mshop_address_placeholder_addr2',
				'desc'    => '<br>상세주소 입력필드의 Placeholder 텍스트를 지정합니다.',
				'css'         => 'width: 50%',
				'default'    => '상세주소',
				'type'         => 'text'
			),

			array( 'type' => 'sectionend', 'id' => 'address_placeholder_option' ),
		));
	}
	
	/**
	 * Output the settings
	 */
public function output() {
		global $current_section;
		
		if( $this->check_key_valid() ) {
			$settings = $this->get_settings();
			woocommerce_admin_fields( $settings );
		} else {
			if ( $this->hasmsg ) {
				echo '<div id="message" class="error fade"><p><strong>' . __( '플러그인 인증 실패 : 입력값을 확인후 다시 시도해주세요.', 'mshop_address' ) . '</strong></p></div>';
			}
	
		?>

			<div class="inline error">
                <h3><strong><?php _e( '플러그인 인증 필요', 'mshop_address' ); ?></strong></h3>
                <p style="padding:0;margin:0;border:0;"><?php _e( '<a href="http://www.codemshop.com" target="_blank">http://www.codemshop.com</a>에 가입하신 후, [내 계정] 에서 발급되는 인증키를 등록하여 주세요.', 'mshop_address' ); ?></p>
                <p>
                    <form name="frm_activate" id="frm_activate" method="post" action="" enctype="multipart/form-data">
                        <label style="width: 120px;display: inline-block;"><?php _e( '회원 이메일', 'mshop_address' ); ?></label><input type="text" name="p_email" id="p_email" value=""><br/>
                        <label style="width: 120px;display: inline-block;"><?php _e( '인증키', 'mshop_address' ); ?></label><input type="text" name="p_activate_key" id="p_activate_key" value=""><br/>
                        <input type="hidden" name="p_siteurl" value="<?php echo home_url(); ?>"><br/>
                        <input type="hidden" name="p_product_code" value="mshop_address">
                        <?php wp_nonce_field('woocommerce-settings') ?>
                        <input type="submit" class="button-primary" value="<?php _e( '인증', 'mshop_address' ); ?>">
                    </form>
                </p>
            </div>
		<?php 
			exit();
		}
		
	}
	
    function check_key_valid(){
        $key = get_option('_codem_mshop_address_activate_key');
        if(empty($key)) {
            return false;
        } else {
       		$url 		= get_option('_codem_mshop_address_activate_url','0');
			$userid 	= get_option('_codem_mshop_address_activate_userid','0');
			$reqkey 	= get_option('_codem_mshop_address_activate_reqkey','0');
            $act_key 	= get_option('_codem_mshop_address_activate_key','0');
			$requrl 	= base64_decode("aHR0cDovL3d3dy5jb2RlbXNob3AuY29tL2FjdGl2YXRlLWNoZWNr");
	        $response 	= wp_remote_post( $requrl, array(
	            'method' => 'POST',
	            'timeout' => 45,
	            'redirection' => 5,
	            'httpversion' => '1.0',
	            'blocking' => true,
	            'headers' => array(),
	            'body' => array( 
	        		'action' => 'activate-check', 
	        		'pcode' => 'mshop_address', 
	        		'reqkey' => $reqkey, 
	        		'userid' => $userid, 
	        		'url' => home_url(), 
					),
	            )
	        );			
			
			$hash_data = $response['body'];
			if($hash_data == $act_key) {
				return true;	
			} else {
				return false;	
			}
        }       
    }

    function process_activate(){
        if(empty($_POST['p_email'])) { return false; }
        if(empty($_POST['p_activate_key'])) { return false; }
        if(empty($_POST['p_product_code'])) { return false; }
        if(empty($_POST['p_siteurl'])) { return false; }
		$url = base64_decode("aHR0cDovL3d3dy5jb2RlbXNob3AuY29tL2FjdGl2YXRlLXJlZ2lzdGVy");
        $response = wp_remote_post( $url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array( 
        		'action' => 'activate-register', 
        		'product-code' => $_POST['p_product_code'], 
        		'activate_key' => $_POST['p_activate_key'], 
        		'email' => $_POST['p_email'], 
        		'homeurl' => $_POST['p_siteurl'], 
				),
            )
        );
        
        if ( is_wp_error( $response ) ) {
           $error_message = $response->get_error_message();
		   return false;
        } else {
           	if( $this->remove_utf8_bom($response['body']) == 'fail') {
           		
                return false;
           	} else if ( $result != 'fail' && (strlen($response['body']) > 1) ){
           		update_option('_codem_'.$_POST['p_product_code'].'_activate_url', $_POST['p_siteurl'] );
				update_option('_codem_'.$_POST['p_product_code'].'_activate_userid', $_POST['p_email'] );
				update_option('_codem_'.$_POST['p_product_code'].'_activate_reqkey', $_POST['p_activate_key'] );
                update_option('_codem_'.$_POST['p_product_code'].'_activate_key', strval($response['body']) );
                return true;    
           	} else {
           		return false;
           	}
        }
    }
	
	public function remove_utf8_bom($text)
	{
	    $bom = pack('H*','EFBBBF');
	    $text = preg_replace("/^$bom/", '', $text);
	    return $text;
	}
	
	/**
	 * Save settings
	 */
	public function save() {
		if( !empty($_POST['p_email']) && !empty($_POST['p_activate_key']) ) {
			if( $this->process_activate() ) {
				$this->hasmsg = false;
			} else {
				$this->hasmsg = true;
			}	
		} else {
			$settings = $this->get_settings();
			woocommerce_update_options( $settings );
		}
	}
}

endif;

return new MShop_Settings_Address();
