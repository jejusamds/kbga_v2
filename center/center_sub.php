<?php

    $category = $_GET['category'];
    $year = !empty($_GET['year']) ? $_GET['year'] : date('Y');

    // 분류 맵핑 (한글)
    $category_map = [
        'makeup' => [
            'ko' => '메이크업',
            'en' => 'MAKE-UP'
        ],
        'nail' => [
            'ko' => '네일',
            'en' => 'NAIL'
        ],
        'hair' => [
            'ko' => '헤어',
            'en' => 'HAIR'
        ],
        'skin' => [
            'ko' => '피부',
            'en' => 'SKIN'
        ],
        'half' => [
            'ko' => '반영구',
            'en' => 'SEMI-PERMANENT'
        ],
        'foreign' => [
            'ko' => '해외인증',
            'en' => 'OVERSEAS CERTIFICATION '
        ],
        'teacher' => [
            'ko' => '강사인증',
            'en' => 'INSTRUCTOR CERTIFICATION'
        ]
    ];

	$Menu = "03";
    switch ($category) {
        case 'makeup':
            $sMenu = "03-2";
            $ssMenu = "03-2-1";
            $ssMenu_slide = "0";
            break;
        case 'nail':
            $sMenu = "03-3";
            $ssMenu = "03-3-1";
            $ssMenu_slide = "0";
            break;
        case 'hair':
            $sMenu = "03-4";
            $ssMenu = "03-4-1";
            $ssMenu_slide = "0";
            break;
        case 'skin':
            $sMenu = "03-5";
            $ssMenu = "03-5-1";
            $ssMenu_slide = "0";
            break;
        case 'half':
            $sMenu = "03-6";
            $ssMenu = "03-6-1";
            $ssMenu_slide = "0";
            break;
        case 'foreign':
            $sMenu = "03-7";
            $ssMenu = "03-7-1";
            $ssMenu_slide = "0";
            break;
        case 'teacher':
            $sMenu = "03-8";
            $ssMenu = "03-8-1";
            $ssMenu_slide = "0";
            break;
        default:
            $sMenu = "03-1";
            $ssMenu = "03-1-1";
            $ssMenu_slide = "0";
            break;
    }

    $valid_categories = ['makeup', 'nail', 'hair', 'skin', 'half', 'foreign', 'teacher'];
    if (!isset($category) || !in_array($category, $valid_categories)) {
        echo "<script>alert('잘못된 접근입니다.'); location.href='/';</script>";
        exit;
    }

	include $_SERVER['DOCUMENT_ROOT'].'/include/header.html'; 

    /*
    반응형
    w_con 은 PC용
    m_con 은 모바일용
    */
?>

	<div id="container">
		<div id="sub_con" class="center_sub02">
			<?php
				include $_SERVER['DOCUMENT_ROOT'].'/include/sub_banner.html'; 
			?>

			<div class="contents_con">
				
				<div class="notice_list_con">
					<div class="ts_con">
						<div class="title_con">
							<div class="text01_con">
								<span>
                                    <?=$category_map[$category]['en']?> PRIVATE QUALIFICATION
								</span>
							</div>

							<div class="text02_con">
								<span>
                                    <?=$category_map[$category]['ko']?> 자격분야
								</span>
							</div>
						</div>

						<div class="search_con">
							<form action="" method="" autocomplete="off">
								<div class="input_con">
									<table cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td align="left" class="select_td">
													<select name="" class="select">
														<option value=""><?=$year?></option>
													</select>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</form>
						</div>
					</div>

					<div class="exam_notice_con">
						<div class="title_con">
							<span>
								시행일정 <br class="w_br" /><?=$year?>
							</span>
						</div>

						<div class="list_con">
							<div class="title_con w_con">
								<table cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td align="center" class="round_td">
												<span>
													회차
												</span>
											</td>
											<td align="center" class="division_td">
												<span>
													구분
												</span>
											</td>
											<td align="center" class="receipt_td">
												<span>
													접수기간
												</span>
											</td>
											<td align="center" class="exam_td">
												<span>
													시험일
												</span>
											</td>
											<td align="center" class="announcement_td">
												<span>
													합격자발표
												</span>
											</td>
											<td align="center" class="license_td">
												<span>
													자격증 신청
												</span>
											</td>
											<td align="center" class="btn_td">
												<span>
													응시접수
												</span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class="list_con">
								<ul>
									<li>
										<div class="w_con">
											<table cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td align="center" class="round_td">
															<span>
																1
															</span>
														</td>
														<td align="center" class="list_td">
															<ul>
																<li>
																	<table cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td align="center" class="division_td">
																					<span>
																						필기
																					</span>
																				</td>
																				<td align="center" class="receipt_td">
																					<span>
																						2025.03.04~10
																					</span>
																				</td>
																				<td align="center" class="exam_td">
																					<span>
																						2025.03.15
																					</span>
																				</td>
																				<td align="center" class="announcement_td">
																					<span>
																						2025.03.28
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</li>
																<li>
																	<table cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td align="center" class="division_td">
																					<span>
																						실기
																					</span>
																				</td>
																				<td align="center" class="receipt_td">
																					<span>
																						2025.03.10~14
																					</span>
																				</td>
																				<td align="center" class="exam_td">
																					<span>
																						2025.03.22
																					</span>
																				</td>
																				<td align="center" class="announcement_td">
																					<span>
																						2025.03.28
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</li>
															</ul>
														</td>
														<td align="center" class="license_td">
															<span>
																2025.04.01~11 <br />
																(순차발송)
															</span>
														</td>
														<td align="center" class="btn_td">
															<a href="/center/center_sub02_4_apply.html" class="a_btn">
																접수하기
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="m_con">
											<div class="round_con">
												<span>
													1회차
												</span>
											</div>

											<div class="nav">
												<ul>
													<li>
														<a href="javascript:void(0);" class="on" val="info01">
															필기
														</a>
													</li>
													<li>
														<a href="javascript:void(0);" val="info02">
															실기
														</a>
													</li>
												</ul>
											</div>

											<div class="contents_con">
												<div class="contents_div info01">
													<div class="text_con">
														<ul>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					접수기간
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.04~10
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					시험일
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.15
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					합격자발표
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.28
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					자격증 신청
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.01~11 (순차발송)
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
														</ul>
													</div>

													<div class="btn_con">
														<a href="/center/center_sub02_4_apply.html" class="a_btn">
															접수하기
														</a>
													</div>
												</div>

												<div class="contents_div info02">
													<div class="text_con">
														<ul>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					접수기간
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.10~14
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					시험일
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.22
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					합격자발표
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.03.28
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					자격증 신청
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.01~11 (순차발송)
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
														</ul>
													</div>

													<div class="btn_con">
														<a href="/center/center_sub02_4_apply.html" class="a_btn">
															접수하기
														</a>
													</div>
												</div>
											</div>
										</div>
									</li>
									<li>
										<div class="w_con">
											<table cellpadding="0" cellspacing="0">
												<tbody>
													<tr>
														<td align="center" class="round_td">
															<span>
																2
															</span>
														</td>
														<td align="center" class="list_td">
															<ul>
																<li>
																	<table cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td align="center" class="division_td">
																					<span>
																						필기
																					</span>
																				</td>
																				<td align="center" class="receipt_td">
																					<span>
																						2025.04.07~11
																					</span>
																				</td>
																				<td align="center" class="exam_td">
																					<span>
																						2025.04.19
																					</span>
																				</td>
																				<td align="center" class="announcement_td">
																					<span>
																						2025.04.30
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</li>
																<li>
																	<table cellpadding="0" cellspacing="0">
																		<tbody>
																			<tr>
																				<td align="center" class="division_td">
																					<span>
																						실기
																					</span>
																				</td>
																				<td align="center" class="receipt_td">
																					<span>
																						2025.04.14~18
																					</span>
																				</td>
																				<td align="center" class="exam_td">
																					<span>
																						2025.04.26
																					</span>
																				</td>
																				<td align="center" class="announcement_td">
																					<span>
																						2025.04.30
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</li>
															</ul>
														</td>
														<td align="center" class="license_td">
															<span>
																2025.05.01~16 <br />
																(순차발송)
															</span>
														</td>
														<td align="center" class="btn_td">
															<a href="/center/center_sub02_4_apply.html" class="a_btn">
																접수하기
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<div class="m_con">
											<div class="round_con">
												<span>
													2회차
												</span>
											</div>

											<div class="nav">
												<ul>
													<li>
														<a href="javascript:void(0);" class="on" val="info01">
															필기
														</a>
													</li>
													<li>
														<a href="javascript:void(0);" val="info02">
															실기
														</a>
													</li>
												</ul>
											</div>

											<div class="contents_con">
												<div class="contents_div info01">
													<div class="text_con">
														<ul>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					접수기간
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.07~11
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					시험일
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.19
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					합격자발표
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.30
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					자격증 신청
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.05.01~16 (순차발송)
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
														</ul>
													</div>

													<div class="btn_con">
														<a href="/center/center_sub02_4_apply.html" class="a_btn">
															접수하기
														</a>
													</div>
												</div>

												<div class="contents_div info02">
													<div class="text_con">
														<ul>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					접수기간
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.14~18
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					시험일
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.26
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					합격자발표
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.04.30
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
															<li>
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td align="left" class="title_td">
																				<span>
																					자격증 신청
																				</span>
																			</td>
																			<td align="left" class="info_td">
																				<span>
																					2025.05.01~16 (순차발송)
																				</span>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</li>
														</ul>
													</div>

													<div class="btn_con">
														<a href="/center/center_sub02_4_apply.html" class="a_btn">
															접수하기
														</a>
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<script type="text/javascript" language="javascript">
		// 모바일 리스트 필기/실기 클릭 시
		$(document).on("click",".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a",function(){
			$(this).closest(".nav").find("a").removeClass("on");
			$(this).addClass("on");
			$(this).closest(".m_con").find(".contents_con .contents_div").hide();
			$(this).closest(".m_con").find(".contents_con .contents_div."+$(this).attr("val")).show();
		});

		// 초기 화면 크기 저장
		var center_sub02_initialWidth = window.innerWidth;
		var center_sub02_isFirstLoad = true;

		// 리사이즈 예외처리
		var center_sub02_resizeTimer;

		// 화면 리사이징
		$(window).resize(function(){
			// 현재 window 너비
			var currentWidth = window.innerWidth;
			
			// 너비가 변경되지 않은 경우(스크롤, 상태바 동작) 무시
			if(currentWidth === center_sub02_initialWidth && !center_sub02_isFirstLoad) {
				return;
			}
			
			clearTimeout(center_sub02_resizeTimer);
			
			// 시험일정 게시판 리스트 예외처리
			$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a").removeClass("on");
			$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li:first-child a").addClass("on");
			$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div").css("display","none");
			$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div:first-child").css("display","block");

			// 화면 너비
			if (window.innerWidth > 1024) {
				
			}else{
				
			}

			center_sub02_resizeTimer = setTimeout(function() {
				// 실제 리사이징이 발생한 경우에만 처리
				if(currentWidth !== center_sub02_initialWidth || center_sub02_isFirstLoad) {
					// 시험일정 게시판 리스트 예외처리
					$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li a").removeClass("on");
					$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .nav > ul > li:first-child a").addClass("on");
					$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div").css("display","none");
					$(".exam_notice_con > .list_con > .list_con > ul > li > .m_con > .contents_con .contents_div:first-child").css("display","block");

					// 화면 너비
					if (window.innerWidth > 1024) {
						
					}else{

					}

					center_sub02_initialWidth = currentWidth;
					center_sub02_isFirstLoad = false;
				}
			}, 500);
		});
	</script>

<?php
	include $_SERVER['DOCUMENT_ROOT'].'/include/footer.html'; 
?>	