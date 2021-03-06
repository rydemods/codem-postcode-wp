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

if ( ! class_exists( 'MShop_Actions' ) ) :

/**
 * WC_Admin_Profile Class
 */
class MShop_Actions {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
	}

	/**
	 * Get Address Fields for the edit user pages.
	 *
	 * @return array Fields to display which are filtered through woocommerce_customer_meta_fields before being returned
	 */
	public function remove_class_actions($tag, $class, $function) {
		global $wp_filter;
			
		if(array_key_exists($tag, $wp_filter)) {	
			foreach($wp_filter[$tag] as $filters) {
				foreach($filters as $filter) {
					if(!is_null($filter['function']) &&  is_array($filter['function'])) {
						remove_action( $tag, array($filter['function'][0], $function) );
						remove_action( $tag, array($filter['function'][0], $function) );
					}
				}
			}
		}
	}
}

endif;
