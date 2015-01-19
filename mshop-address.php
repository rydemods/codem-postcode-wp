<?php
/*
Plugin Name: MShop Korean Address
Plugin URI: 
Description: 대한민국의 우편번호, 도로명 주소 체계 및 지번 체계(구 주소형태) 기능을 지원합니다.
Version: 2.2.6
Author: CodeMShop
Author URI: www.codemshop.com
License: Commercial License
*/

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

if ( ! class_exists( 'MShop_Address' ) ) {
    
class MShop_Address {

    protected $slug;

    /**
     * @var string
     */
    public $version = '2.2.6';

    /**
     * @var string
     */
    public $plugin_url;

    /**
     * @var string
     */
    public $plugin_path;
	
    /**
     * @var MShop_Actions
     */
	public $mshop_actions;

    protected $update_checker;

    private $_body_classes = array();
    
    /**
     * MShop Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        // Define version constant
        define( 'MSHOP_ADDRESS_VERSION', $this->version );
        $this->slug = 'mshop-address-ex';

        $this->init_update();
        $this->load_plugin_textdomain();
		
        // Hooks
        add_action( 'init', array( $this, 'init' ), 0 );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation_process' ) );
		add_action( 'wp_footer', array( &$this, 'footer' ) );
		
        // Loaded action
        do_action( 'mshop_address_loaded' );
    }
	
    function deactivation_process() {
        delete_option( '_codem_mshop_address_activate_url' );
        delete_option( '_codem_mshop_address_activate_userid' );
        delete_option( '_codem_mshop_address_activate_reqkey' );
        delete_option( '_codem_mshop_address_activate_key' );
    }	
    
    function init_update() {
        require 'admin/update/plugin-updates/plugin-update-checker.php';
        $this->update_checker = PucFactory::buildUpdateChecker(
            'http://update.codemshop.com/' . $this->slug . '/' . $this->slug . '.json',
            __FILE__,
            $this->slug
        );
    }

    public function plugin_url() {
        if ( $this->plugin_url ) 
            return $this->plugin_url;
        
        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }


    public function plugin_path() {
        if ( $this->plugin_path ) 
            return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
    
    function includes() {
        if ( is_admin() )
            $this->admin_includes();

        if ( defined('DOING_AJAX') )
            $this->ajax_includes();

        if ( ! is_admin() || defined('DOING_AJAX') )
            $this->frontend_includes();
        
		if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
			include_once("classes/class-mshop-address-actions.php");
			include_once("classes/class-mshop-address-form-fields.php");
	        wp_register_script( 'ms-write-panel', $this->plugin_url() . '/assets/js/admin/write-panel.js');
	        wp_enqueue_script( 'ms-write-panel' );
		}
    }

    public function admin_includes() {
		include_once("admin/mshop-address-admin-init.php");
		include_once('admin/settings/class-mshop-settings-address.php');
		
		if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
			include_once("classes/class-mshop-address-admin-profile.php");
			include_once("classes/class-mshop-address-writepanel-orders.php");
		}
    }

    public function ajax_includes() {
    	if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
    		include_once("classes/class-mshop-address-writepanel-orders.php");
		}
    }


    public function frontend_includes() {
    	if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
			include_once("classes/class-mshop-address-my-account.php");
		}
    }
    
    public function frontend_scripts() {
    	if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
			wp_register_script( 'ms-address', $this->plugin_url() . '/assets/js/mshop-address.js' );
			wp_register_script( 'ms-address-search', $this->plugin_url() . '/assets/js/mshop-address-search.js' );

			wp_localize_script( 'ms-address-search', '_mshop_address_search_settings', array(
				'ajaxurl' 		=> admin_url('admin-ajax.php'),
				'plugin_url' 	=> $this->plugin_url(),
				'api_url'		=> $this->plugin_url() . '/assets/tool/mshop_addr_search_api.php',
			) );			
			
			wp_register_script( 'jquery-magnific-popup', $this->plugin_url() . '/assets/js/jquery.magnific-popup.min.js?v=2.02', array( 'jquery' ), '2.1.1', true );
		    wp_enqueue_script( 'ms-address');
		    wp_enqueue_script( 'ms-address-search');
		    wp_enqueue_script( 'jquery-magnific-popup');

			wp_register_style( 'ms-address-search', $this->plugin_url() . '/assets/css/mshop-address-search.css' );
			wp_register_style( 'magnific-popup', $this->plugin_url() . '/assets/css/magnific-popup.css' );
			wp_enqueue_style( 'ms-address-search' );
			wp_enqueue_style( 'magnific-popup' );
		}
    }
    
    public function init() {
        do_action( 'before_mshop_address_init' );
         
        if ( ! is_admin() || defined('DOING_AJAX') ) {

        }

        $this->includes();
		
		if ( get_option('mshop_address_enable', 'no' ) == 'yes' ) {
			$this->mshop_actions = new MShop_Actions();
		}
        
        add_action('wp_enqueue_scripts', array( $this, 'frontend_scripts' ) , 999);
		
        do_action( 'mshop_address_init' );
    }
        
    public function add_body_class( $class ) {
        $this->_body_classes[] = sanitize_html_class( strtolower($class) );
    }
    
    public function output_body_class( $classes ) {
        return $classes;
    }

	public function footer() {
	 	ob_start();
		
		if ( wp_is_mobile() ){
	        load_template( $this->plugin_path() . '/templates/mshop-address-search-mobile.php' );
		} else {
	        load_template( $this->plugin_path() . '/templates/mshop-address-search.php' );
		}

        echo ob_get_clean();		
	} 
        /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present
     *
     * @access public
     * @return void
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'mshop_address', false, dirname( plugin_basename(__FILE__) ) . "/languages/" );
    }
        
}    

}

$mshop_address = new MShop_Address();