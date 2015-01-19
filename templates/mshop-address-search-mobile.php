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
?>
<div id="ms_addr_1" class="edit-account ms-addr-search-popup mfp-hide">
	<!-- start -->
	<div class="code_address_pop_wrap">
		<div class="code_address_pop">
			<div class="code_address_wrap">
				
				<div class="code_address_header">
					<p>주소검색</p>
					<p><button title="%title%" class="mfp-close"><i class="mfp-close-icn">&times;</i></button></p>
				</div>
				
				<div class="code_address_body">
					<div class="code_address_textarea">
						<ul class="code_address_tab">
							<li style="width:50%;">
								<a class="code_tab1 on" style="cursor: pointer">지번 주소검색</a>
							</li>
							<li style="width:50%;">
								<a class="code_tab2 off" style="cursor: pointer">도로명 주소검색</a>
							</li>
						</ul>
						
						<div class="code_step01">
							<div class="code_step_textbox_01">
								<table class="code_search_textbox">
									<tbody>
										<tr>
											<td class="code_city">시도</td>
											<td class="code_city_choice">
												<div id="code_city_choice_option_wrap" class="code_city_choice_option_wrap">
    												<select class="code_city_choice_option" id="mshop_post_sido" name="mshop_post_sido">
    													<option value="선택">선택</option>
    													<option value="강원도">강원도</option>
    													<option value="경기도">경기도</option>
    													<option value="경상남도">경상남도</option>
    													<option value="경상북도">경상북도</option>
    													<option value="광주광역시">광주광역시</option>
    													<option value="대구광역시">대구광역시</option>
    													<option value="대전광역시">대전광역시</option>
    													<option value="부산광역시">부산광역시</option>
    													<option value="서울특별시">서울특별시</option>
    													<option value="세종특별자치시">세종특별자치시</option>
    													<option value="울산광역시">울산광역시</option>
    													<option value="인천광역시">인천광역시</option>
    													<option value="전라남도">전라남도</option>
    													<option value="전라북도">전라북도</option>
    													<option value="제주특별자치도">제주특별자치도</option>
    													<option value="충청남도">충청남도</option>
    													<option value="충청북도">충청북도</option>
    												</select>	
                                                </div>
											</td>
										</tr>
										<tr>
											<td class="code_ad_di">시군구</td>
											<td class="code_ad_di_choice">
											    <div id="code_ad_di_choice_option_wrap" class="code_ad_di_choice_option_wrap">
    												<select class="code_ad_di_choice_option" id="mshop_post_sigungu" name="mshop_post_sigungu">
    													<option value="선택">선택</option>
    												</select>														
												</div>
											</td>
										</tr>
										<tr>
											<td class="code_sword">상세주소</td>
											<td class="code_sword_choice" style="text-align: center">
											    <div id="code_sword_choice_option_wrap" class="code_sword_choice_option_wrap" style="margin-bottom: 5px !important;">
    												<select class="code_sword_choice_option" id="mshop_post_umd" name="mshop_post_umd">
    													<option value="선택">선택</option>
    												</select>
												</div>	
												<input type="text" style="margin-bottom: 10px !important;" id="mshop_post_jibun" name="mshop_post_jibun" class="code_box" class="code_sword_box" value="" placeholder="지번">
												<a style="padding: 7px 40px;" class="code_search_btn" name="mshop_post_search_button" id="mshop_post_search_button">검색</a>
											</td>
										</tr>										
									</tbody>
								</table>
							</div>
						
							<div class="code_notice_01">
								<div class="code_notice_box">
									<p>검색방법: 시/도 및 시/군/구 선택 후 동(읍/면) + 지번 입력(선택)<br/>
									예) 역삼동 123 → ‘서울시’ ‘강남구’ 선택 후 역삼동 + 123</p>
								</div>
								<ul>
									<li>지번 주소가 검색되지 않는 경우는 행정안전부 도로명주소
									안내시스템 (http://www.juso.go.kr) 에서 확인하시기 바랍니다.
									</li>
								</ul>
							</div>
						
							<div class="code_search_list_01">
								<h3>아래의 주소 중에서 해당하는 주소를 선택해 주세요.</h3>
								<div class="code_search_result_list_01">
									<table class="code_search_list_textbox">
										<thead>
											<tr>
												<th class="code_line">우편번호</th>
												<th class="code_line_center">주소</th>
												<th class="code_line_end">선택</th>
											</tr>
										</thead>
										<tbody class="code_search_list_result">
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="code_step02">
							<div class="code_step_textbox_02">
								<table class="code_search_textbox">
									<tbody>
										<tr>
											<td class="code_city">시도</td>
											<td class="code_city_choice">
											    <div id="" class="code_city_choice_option_wrap">
    												<select class="code_city_choice_option" id="mshop_road_sido" name="mshop_road_sido">
    													<option value="선택">선택</option>
    													<option value="강원도">강원도</option>
    													<option value="경기도">경기도</option>
    													<option value="경상남도">경상남도</option>
    													<option value="경상북도">경상북도</option>
    													<option value="광주광역시">광주광역시</option>
    													<option value="대구광역시">대구광역시</option>
    													<option value="대전광역시">대전광역시</option>
    													<option value="부산광역시">부산광역시</option>
    													<option value="서울특별시">서울특별시</option>
    													<option value="세종특별자치시">세종특별자치시</option>
    													<option value="울산광역시">울산광역시</option>
    													<option value="인천광역시">인천광역시</option>
    													<option value="전라남도">전라남도</option>
    													<option value="전라북도">전라북도</option>
    													<option value="제주특별자치도">제주특별자치도</option>
    													<option value="충청남도">충청남도</option>
    													<option value="충청북도">충청북도</option>
    												</select>
												</div>														
											</td>
										</tr>
										<tr>
											<td class="code_ad_di">시군구</td>
											<td class="code_ad_di_choice">
											    <div id="code_ad_di_choice_option_wrap" class="code_ad_di_choice_option_wrap">
    												<select class="code_ad_di_choice_option" id="mshop_road_sigungu" name="mshop_road_sigungu">
    													<option value="선택">선택</option>
    												</select>
												</div>														
											</td>
										</tr>
										<tr>
											<td class="code_sword">상세주소</td>
											<td class="code_sword_choice" style="text-align: center">										
												<input type="text" style="margin-bottom: 5px !important;" name="mshop_road_name" id="mshop_road_name" class="code_box" class="code_sword_box" value="" placeholder="도로명" >
												<input type="text" style="margin-bottom: 10px !important;" name="mshop_road_bdnum" id="mshop_road_bdnum" class="code_box" class="code_sword_box" value="" placeholder="건물번호">
												<a style="padding: 7px 40px;" class="code_search_btn" name="mshop_road_search_button" id="mshop_road_search_button">검색</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						
							<div class="code_notice_02">
								<div class="code_notice_box">
									<p>검색방법: 시/도 및 시/군/구 선택 후 도로명 + 건물번호 입력(선택)<br />
									예) 테헤란로 123 → ‘서울시’  ‘강남구’  선택 후 테헤란로 + 123</p>
								</div>
								<ul>
									<li>지번 주소가 검색되지 않는 경우는 행정안전부 도로명주소
									안내시스템 (http://www.juso.go.kr) 에서 확인하시기 바랍니다.
									</li>
								</ul>
							</div>
						
							<div class="code_search_list_02">
								<h3>아래의 주소 중에서 해당하는 주소를 선택해 주세요.</h3>
								<div class="code_search_result_list_02">	
									<table class="code_search_list_textbox">
										<thead>
											<tr>
												<th class="code_line">우편번호</th>
												<th class="code_line_center">주소</th>
												<th class="code_line_end">선택</th>
											</tr>
										</thead>
										<tbody class="code_search_list_result">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<input type="hidden" class="search_result_postnum" value="">
		<input type="hidden" class="search_result_addr" value="">

	</div>
	<!-- end -->
</div>
