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
function get_data($url, $param)
{
	$ch = curl_init();
	$timeout = 15;
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if(!function_exists('curl_init')) {
	echo 'PHP5 CURL Extension Required!';
	exit();
}
$url_params = array( 
	'addr_type'		=> isset($_REQUEST['addr_type']) 	? ltrim($_REQUEST['addr_type']) : '',
	'addr_mode'		=> isset($_REQUEST['addr_mode']) 	? ltrim($_REQUEST['addr_mode']) : '',
	'road_sido'		=> isset($_REQUEST['road_sido']) 	? ltrim(urldecode($_REQUEST['road_sido'])) : '',
	'road_sigungu'	=> isset($_REQUEST['road_sigungu']) ? ltrim(urldecode($_REQUEST['road_sigungu'])) : '',
	'road_name'		=> isset($_REQUEST['road_name']) 	? ltrim(urldecode($_REQUEST['road_name'])) : '',
	'road_bdnum'	=> isset($_REQUEST['road_bdnum']) 	? ltrim(urldecode($_REQUEST['road_bdnum'])) : '',
	'post_sido' 	=> isset($_REQUEST['post_sido']) 	? ltrim(urldecode($_REQUEST['post_sido'])) : '',
	'post_sigungu' 	=> isset($_REQUEST['post_sigungu']) ? ltrim(urldecode($_REQUEST['post_sigungu'])) : '',
	'post_hjd' 		=> isset($_REQUEST['post_hjd']) 	? ltrim(urldecode($_REQUEST['post_hjd'])) : '',
	'post_um' 		=> isset($_REQUEST['post_um']) 		? ltrim(urldecode($_REQUEST['post_um'])) : '',
	'post_umd'		=> isset($_REQUEST['post_umd']) 	? ltrim(urldecode($_REQUEST['post_umd'])) : '',
	'post_ri' 		=> isset($_REQUEST['post_ri']) 		? ltrim(urldecode($_REQUEST['post_ri'])) : '',
	'post_jibun'	=> isset($_REQUEST['post_jibun']) 	? ltrim($_REQUEST['post_jibun']) : '',
);
$url_params = array_filter($url_params);
$request_param = http_build_query($url_params);
echo get_data('http://api.codemshop.com/addr_search_api.php', $request_param);
?>