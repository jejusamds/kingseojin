<?php
/*****************************************************************************************************************************************************
	sendNITAPI  :  NIT 관련 전송
******************************************************************************************************************************************************
	api									action
	--------------------------------------------------------------------------------------------------------------------------------------------------
	message		: SMS/LMS/MMS			RegistIssue
	kakaoA		: 카카오 알림톡			RegistIssue
	kakaoF		: 카카오 친구톡			RegistIssue
	taxinvoice	: 전자세금계산서		RegistIssue		: 즉시발행
										CancelIssue		: 발행취소
										GetInfo			: 상태정보
	cashbill	: 현금영수증			RegistIssue		: 즉시발행
										CancelIssue		: 발행취소, 취소현금영수증 즉시발행 [RevokeRegistIssue])
										GetInfo			: 상태정보
	fax			: 팩스					RegistIssue
*****************************************************************************************************************************************************/
function sendNITAPI( $param ){

	// 디팩 client_code
	$client_code = '2024120007'; // 2023080001 (채정에 있던) , 2019040003 (여기 기존)
	$kko_profile_key = '';
	
	if(!$param['action']) $param['action'] = "RegistIssue";
	
	
	switch ( $param['api'] ) {


		//============================================================================================================================================
		//	SMS / LMS / MMS
		//============================================================================================================================================
		case "message":

			$data = array(
				'api'			=> $param['api'],
				'action'		=> $param['action'],
				'client_code'	=> $client_code,
				'reserveDT'		=> $param['reserveDT'] ? $param['reserveDT'] : null,	// 예약전송일시(yyyyMMddHHmmss) ex)20161108200000, null인경우 즉시전송
				'adsYN'			=> $param['adsYN'],										// 광고문자 전송여부 (true / false)
				'snd'			=> $param['snd'],										// 발신번호
				'sndnm'			=> $param['sndnm'],										// 발신자명
				'rcv'			=> $param['rcv'],										// 수신번호
				'rcvnm'			=> $param['rcvnm'],										// 수신자 성명
				'msg'			=> $param['msg'],										// 메시지 내용
				'sjt'			=> $param['sjt'],										// 메시지 제목
				'files'			=> $param['files']										// 첨부파일(최대 300KByte, JPEG 파일포맷 전송가능)
			);

			break;


		//============================================================================================================================================
		//	카카오 알림톡
		//============================================================================================================================================
		case "kakaoA":

			$data = array(
				'api'			=> $param['api'],
				'action'		=> $param['action'],
				'client_code'	=> $client_code,
				'profileKey'	=> $kko_profile_key,
				'templateCode'	=> $param['templateCode'],								// 템플릿 코드 - 템플릿 목록 조회 (ListATSTemplate API)의 반환항목 확인
				'sender'		=> $param['sender'],									// 팝빌에 사전 등록된 발신번호
				'content'		=> $param['content'],									// 알림톡 내용, 최대 1000자
				'altContent'	=> $param['altContent'],								// 대체문자 내용
				'altSendType'	=> $param['altSendType'],								// 대체문자 전송유형 (공백-미전송, A-대체문자내용 전송, C-알림톡내용 전송)
				'reserveDT'		=> $param['reserveDT'] ? $param['reserveDT'] : null,	// 예약전송일시 (yyyyMMddHHmmss) ex)20161108200000, null인경우 즉시전송
				'receivers'		=> $param['receivers'],									// 수신자 정보 [배열]
																							// - rcv : 수신번호
																							// - rcvnm : 수신자명
				'buttons'		=> $param['buttons'] ? $param['buttons'] : null			// 버튼정보를 수정하지 않고 템플릿 신청시 기재한 버튼내용을 전송하는 경우, null처리
			);

			break;


		//============================================================================================================================================
		//	카카오 친구톡
		//============================================================================================================================================
		case "kakaoF":

			$data = array(
				'api'			=> $param['api'],
				'action'		=> $param['action'],
				'client_code'	=> $client_code,
				'plusFriendID'	=> $param['plusFriendID'],								// 팝빌에 등록된 플러스친구 아이디, ListPlusFriend API - plusFriendID 확인 (ex. @팝빌)
				'sender'		=> $param['sender'],									// 팝빌에 사전 등록된 발신번호
				'content'		=> $param['content'],									// 알림톡 내용, 최대 1000자
				'altContent'	=> $param['altContent'],								// 대체문자 내용
				'altSendType'	=> $param['altSendType'],								// 대체문자 전송유형 (공백-미전송, A-대체문자내용 전송, C-알림톡내용 전송)
				'adsYN'			=> $param['adsYN'],										// 광고전송여부 (true / false)
				'reserveDT'		=> $param['reserveDT'] ? $param['reserveDT'] : null,	// 예약전송일시 (yyyyMMddHHmmss) ex)20161108200000, null인경우 즉시전송
				'receivers'		=> serialize($param['receivers']),						// 수신자 정보 [배열]
																							// - rcv : 수신번호
																							// - rcvnm : 수신자명
				'buttons'		=> serialize($param['buttons']),						// 버튼 최대 5개 [배열]
																							// - n : 버튼 표시명
																							// - t : 버튼 유형 (WL-웹링크, AL-앱링크, MD-메시지 전달, BK-봇키워드)
																							// - u1 : [앱링크] Android, [웹링크] Mobile
																							// - u2 : [앱링크] IOS, [웹링크] PC URL
				'files'			=> $param['files'],										// 친구톡 이미지
																							// - 전송 포맷 : JPG 파일(.jpg, jpeg)
																							// - 용량 제한 : 최대 500Byte
																							// - 이미지 가로/세로 비율 : 1.5 미만 (가로 500px 이상)
				'imageURL'		=> $param['imageURL']									// 첨부 이미지 링크 URL
			);

			break;


		//============================================================================================================================================
		//	전자세금계산서
		//============================================================================================================================================
		case "taxinvoice":

			//---------------------------------------------------------------------------------------------
			//	상태정보
			//---------------------------------------------------------------------------------------------
			if($param['action'] == "GetInfo"){
				
				$data = array(
					'api'			=> $param['api'],
					'action'		=> $param['action'],
					'client_code'	=> $client_code,
					'mgtKeyType'	=> $param['mgtKeyType'],	// 발행유형, ENumMgtKeyType::SELL:매출, ENumMgtKeyType::BUY:매입, ENumMgtKeyType::TRUSTEE:위수탁
					'mgtKey'		=> $param['mgtKey']			// 조회할 세금계산서 문서번호
				);
				
			}
			
			//---------------------------------------------------------------------------------------------
			//	즉시발행
			//---------------------------------------------------------------------------------------------
			else if($param['action'] == "RegistIssue"){

				/************************************************************
				*                     수정 세금계산서 기재정보
				* - 수정세금계산서 관련 정보는 연동매뉴얼 또는 개발가이드 링크 참조
				* - [참고] 수정세금계산서 작성방법 안내 - http://blog.linkhub.co.kr/650
				************************************************************/
				// 수정사유코드, 수정사유에 따라 1~6중 선택기재
				//$Taxinvoice->modifyCode = '';

				// 원본세금계산서 ItemKey 기재, 문서확인 (GetInfo API)의 응답결과(ItemKey 항목) 확인
				//$Taxinvoice->originalTaxinvoiceKey = '';

				/************************************************************
				*                       상세항목(품목) 정보
				************************************************************/
				/*
				$Taxinvoice_detailList = array();

				$Taxinvoice_detailList[0]['serialNum'] = '1';				      // [상세항목 배열이 있는 경우 필수] 일련번호 1~99까지 순차기재,
				$Taxinvoice_detailList[0]['purchaseDT'] = '20190226';	  // 거래일자
				$Taxinvoice_detailList[0]['itemName'] = '품목명1번';	  	// 품명
				$Taxinvoice_detailList[0]['spec'] = '';				      // 규격
				$Taxinvoice_detailList[0]['qty'] = '1';					        // 수량
				$Taxinvoice_detailList[0]['unitCost'] = '500';		    // 단가
				$Taxinvoice_detailList[0]['supplyCost'] = '500';		  // 공급가액
				$Taxinvoice_detailList[0]['tax'] = '50';				      // 세액
				$Taxinvoice_detailList[0]['remark'] = '';		    // 비고

				$Taxinvoice_detailList[1]['serialNum'] = '2';				      // [상세항목 배열이 있는 경우 필수] 일련번호 1~99까지 순차기재,
				$Taxinvoice_detailList[1]['purchaseDT'] = '20190226';	  // 거래일자
				$Taxinvoice_detailList[1]['itemName'] = '품목명2번';	  	// 품명
				$Taxinvoice_detailList[1]['spec'] = '';				      // 규격
				$Taxinvoice_detailList[1]['qty'] = '1';					        // 수량
				$Taxinvoice_detailList[1]['unitCost'] = '500';		    // 단가
				$Taxinvoice_detailList[1]['supplyCost'] = '500';		  // 공급가액
				$Taxinvoice_detailList[1]['tax'] = '50';				      // 세액
				$Taxinvoice_detailList[1]['remark'] = '';		    // 비고
				*/

				/************************************************************
				*                      추가담당자 정보
				* - 세금계산서 발행안내 메일을 수신받을 공급받는자 담당자가 다수인 경우
				* 추가 담당자 정보를 등록하여 발행안내메일을 다수에게 전송할 수 있습니다. (최대 5명)
				************************************************************/
				// $Taxinvoice->addContactList = array();

				// $Taxinvoice->addContactList[] = new TaxinvoiceAddContact();
				// $Taxinvoice->addContactList[0]->serialNum = 1;				        // 일련번호 1부터 순차기재
				// $Taxinvoice->addContactList[0]->email = 'test@test.com';	    // 이메일주소
				// $Taxinvoice->addContactList[0]->contactName	= '팝빌담당자';		// 담당자명

				// $Taxinvoice->addContactList[] = new TaxinvoiceAddContact();
				// $Taxinvoice->addContactList[1]->serialNum = 2;			        	// 일련번호 1부터 순차기재
				// $Taxinvoice->addContactList[1]->email = 'test@test.com';	    // 이메일주소
				// $Taxinvoice->addContactList[1]->contactName	= '링크허브';		  // 담당자명
				
				$data = array(
					'api'					=> $param['api'],
					'action'				=> $param['action'],
					'client_code'	=> $client_code,
					
					// 세금계산서 정보 ------------------------------------------
					'writeDate'				=> $param['writeDate'],				// [필수] 작성일자, 형식(yyyyMMdd) 예)20150101
					'issueType'				=> $param['issueType'],				// [필수] 발행형태, '정발행', '역발행', '위수탁' 중 기재
					'chargeDirection'		=> $param['chargeDirection'],		// [필수] 과금방향, - '정과금'(공급자 과금), '역과금'(공급받는자 과금) 중 기재, 역과금은 역발행시에만 가능.
					'purposeType'			=> $param['purposeType'],			// [필수] '영수', '청구' 중 기재
					'taxType'				=> $param['taxType'],				// [필수] 과세형태, '과세', '영세', '면세' 중 기재
					'issueTiming'			=> $param['issueTiming'],			// [필수] 발행시점, "직접발행", "승인시자동발행" 중 기재

					// 공급자 정보 ----------------------------------------------
					'invoicerMgtKey'		=> $param['invoicerMgtKey'],		// [필수] 공급자 문서관리번호, 최대 24자리 숫자, 영문, '-', '_' 조합으로 사업자별로 중복되지 않도록 구성
					'invoicerCorpNum'		=> $testCorpNum,					// [필수] 공급자 사업자번호
					'invoicerTaxRegID'		=> $param['invoicerTaxRegID'],		// 공급자 종사업장 식별번호, 4자리 숫자 문자열
					'invoicerCorpName'		=> $param['invoicerCorpName'],		// [필수] 공급자 상호
					'invoicerCEOName'		=> $param['invoicerCEOName'],		// [필수] 공급자 대표자성명
					'invoicerAddr'			=> $param['invoicerAddr'],			// 공급자 주소
					'invoicerBizClass'		=> $param['invoicerBizClass'],		// 공급자 종목
					'invoicerBizType'		=> $param['invoicerBizType'],		// 공급자 업태
					'invoicerContactName'	=> $param['invoicerContactName'],	// 공급자 담당자 성명
					'invoicerEmail'			=> $param['invoicerEmail'],			// 공급자 담당자 메일주소
					'invoicerTEL'			=> $param['invoicerTEL'],			// 공급자 담당자 연락처
					'invoicerHP'			=> $param['invoicerHP'],			// 공급자 휴대폰 번호
					'invoicerSMSSendYN'		=> $param['invoicerSMSSendYN'],		// 발행시 알림문자 전송여부 (정발행에서만 사용가능)

					// 공급받는자 정보 ------------------------------------------
					'invoiceeMgtKey'		=> $param['invoiceeMgtKey'],		// [역발행시 필수] 공급받는자 문서관리번호, 최대 24자리 숫자, 영문, '-', '_' 조합으로 사업자별로 중복되지 않도록 구성
					'invoiceeType'			=> $param['invoiceeType'],			// [필수] 공급받는자 구분, '사업자', '개인', '외국인' 중 기재
					'invoiceeCorpNum'		=> $param['invoiceeCorpNum'],		// [필수] 공급받는자 사업자번호
					'invoiceeTaxRegID'		=> $param['invoiceeTaxRegID'],		// 공급받는자 종사업장 식별번호, 4자리 숫자 문자열
					'invoiceeCorpName'		=> $param['invoiceeCorpName'],		// [필수] 공급자 상호
					'invoiceeCEOName'		=> $param['invoiceeCEOName'],		// [필수] 공급받는자 대표자성명
					'invoiceeAddr'			=> $param['invoiceeAddr'],			// 공급받는자 주소
					'invoiceeBizType'		=> $param['invoiceeBizType'],		// 공급받는자 업태
					'invoiceeBizClass'		=> $param['invoiceeBizClass'],		// 공급받는자 종목
					'invoiceeContactName1'	=> $param['invoiceeContactName1'],	// 공급받는자 담당자 성명
					'invoiceeEmail1'		=> $param['invoiceeEmail1'],		// 공급받는자 담당자 메일주소
					'invoiceeTEL1'			=> $param['invoiceeTEL1'],			// 공급받는자 담당자 연락처
					'invoiceeHP1'			=> $param['invoiceeHP1'],			// 공급받는자 담당자 휴대폰 번호

					// 세금계산서 기재정보 --------------------------------------
					'supplyCostTotal'		=> $param['supplyCostTotal'],		// [필수] 공급가액 합계
					'taxTotal'				=> $param['taxTotal'],				// [필수] 세액 합계
					'totalAmount'			=> $param['totalAmount'],			// [필수] 합계금액, (공급가액 합계 + 세액 합계)
					'serialNum'				=> $param['serialNum'],				// 기재상 '일련번호'항목
					'cash'					=> $param['cash'],					// 기재상 '현금'항목
					'chkBill'				=> $param['chkBill'],				// 기재상 '수표'항목
					'note'					=> $param['note'],					// 기재상 '어음'항목
					'credit'				=> $param['credit'],				// 기재상 '외상'항목
					'remark1'				=> $param['remark1'],				// 기재상 '비고' 항목
					'remark2'				=> $param['remark2'],				// 기재상 '비고' 항목
					'remark3'				=> $param['remark3'],				// 기재상 '비고' 항목
					'kwon'					=> $param['kwon'],					// 기재상 '권' 항목, 최대값 32767  /  미기재시 $Taxinvoice->kwon = 'null';
					'ho'					=> $param['ho'],					// 기재상 '호' 항목, 최대값 32767  /  미기재시 $Taxinvoice->ho = 'null';
					'businessLicenseYN'		=> $param['businessLicenseYN'],		// 사업자등록증 이미지파일 첨부여부
					'bankBookYN'			=> $param['bankBookYN'],			// 통장사본 이미지파일 첨부여부

					'detailList'			=> serialize($param['detailList']),	// 상세항목(품목) 정보 [배열], 99개까지 가능
																					// - serialNum	: [상세항목 배열이 있는 경우 필수] 일련번호 1~99까지 순차기재,
																					// - purchaseDT	: 거래일자
																					// - itemName	: 품명
																					// - spec		: 규격
																					// - qty		: 수량
																					// - unitCost	: 단가
																					// - supplyCost	: 공급가액
																					// - tax		: 세액
																					// - remark		: 비고

					'forceIssue'			=> $param['forceIssue'],			// 지연발행 강제여부
					'memo'					=> $param['memo'],					// 즉시발행 메모
					'emailSubject'			=> $param['emailSubject'],			// 안내메일 제목, 미기재시 기본제목으로 전송
					'writeSpecification'	=> $param['writeSpecification'],	// 거래명세서 동시작성 여부
					'dealInvoiceMgtKey'		=> $param['dealInvoiceMgtKey']		// 거래명세서 동시작성시 명세서 관리번호 - 최대 24자리 숫자, 영문, '-', '_' 조합으로 사업자별로 중복되지 않도록 구성
				);

			}
			
			//---------------------------------------------------------------------------------------------
			//	발행취소
			//---------------------------------------------------------------------------------------------
			else if($param['action'] == "CancelIssue"){

				$data = array(
					'api'			=> $param['api'],
					'action'		=> $param['action'],
					'client_code'	=> $client_code,
					'mgtKeyType'	=> $param['mgtKeyType'],	// 발행유형, ENumMgtKeyType::SELL:매출, ENumMgtKeyType::BUY:매입, ENumMgtKeyType::TRUSTEE:위수탁
					'mgtKey'		=> $param['mgtKey'],		// 문서번호
					'memo'			=> $param['memo']			// 메모
				);

			}

			break;


		//============================================================================================================================================
		//	현금영수증
		//============================================================================================================================================
		case "cashbill":

			//---------------------------------------------------------------------------------------------
			//	즉시발행
			//---------------------------------------------------------------------------------------------
			if($param['action'] == "RegistIssue"){
				
				// [필수] 거래처 식별번호, 거래유형에 따라 작성
				// 소득공제용 - 주민등록/휴대폰/카드번호 기재가능
				// 지출증빙용 - 사업자번호/주민등록/휴대폰/카드번호 기재가능
				if($param['auto_regist'] == "Y"){		// 자진발급
					$param['identityNum']	= "0100001234";
					$param['tradeUsage']	= '소득공제용';
				}
				else{
					$param['identityNum']	= $param['identityNum'];
					$param['tradeUsage']	= $param['tradeUsage'];
				}
				
				$data = array(
					'api'				=> $param['api'],
					'action'			=> $param['action'],
					'client_code'	=> $client_code,
					'auto_regist'		=> $param['auto_regist'],		// 자진발급
					'memo'				=> $param['memo'],				// 메모
					'mgtKey'			=> $param['mgtKey'],			// [필수] 현금영수증 문서관리번호, 사업자별로 중복없이 1~24자리 영문, 숫자, '-', '_' 조합으로 구성
					'tradeType'			=> $param['tradeType'],			// [필수] 거래유형, (승인거래, 취소거래) 중 기재
					'identityNum'		=> $param['identityNum'],
					'tradeUsage'		=> $param['tradeUsage'],
					'taxationType'		=> $param['taxationType'],		// [필수] 과세, 비과세 중 기재
					'supplyCost'		=> $param['supplyCost'],		// [필수] 공급가액, ','콤마 불가 숫자만 가능
					'tax'				=> $param['tax'],				// [필수] 세액, ','콤마 불가 숫자만 가능
					'serviceFee'		=> $param['serviceFee'],		// [필수] 봉사료, ','콤마 불가 숫자만 가능
					'totalAmount'		=> $param['totalAmount'],		// [필수] 거래금액, ','콤마 불가 숫자만 가능
					'franchiseCorpNum'	=> $testCorpNum,				// [필수] 발행자 사업자번호
					'franchiseCorpName'	=> $param['franchiseCorpName'],	// 발행자 상호
					'franchiseCEOName'	=> $param['franchiseCEOName'],	// 발행자 대표자 성명
					'franchiseAddr'		=> $param['franchiseAddr'],		// 발행자 주소
					'franchiseTEL'		=> $param['franchiseTEL'],		// 발행자 연락처
					'customerName'		=> $param['customerName'],		// 고객명
					'itemName'			=> $param['itemName'],			// 상품명
					'orderNumber'		=> $param['orderNumber'],		// 주문번호
					'email'				=> $param['email'],				// 고객 메일주소
					'hp'				=> $param['hp'],				// 고객 휴대폰 번호
					'smssendYN'			=> $param['smssendYN']			// 발행시 알림문자 전송여부
				);

			}
			
			//---------------------------------------------------------------------------------------------
			//	발행취소 / 취소현금영수증 즉시발행
			//---------------------------------------------------------------------------------------------
			else if($param['action'] == "CancelIssue"){
				
				$data = array(
					'api'			=> $param['api'],
					'action'		=> $param['action'],
					'client_code'	=> $client_code,
					'mgtKey'		=> $param['mgtKey']		// [필수] 현금영수증 문서관리번호, 사업자별로 중복없이 1~24자리 영문, 숫자, '-', '_' 조합으로 구성
				);

			}
			
			//---------------------------------------------------------------------------------------------
			//	상태 정보
			//---------------------------------------------------------------------------------------------
			else if($param['action'] == "GetInfo"){
				
				$data = array(
					'api'			=> $param['api'],
					'action'		=> $param['action'],
					'client_code'	=> $client_code,
					'mgtKey'		=> $param['mgtKey']		// [필수] 현금영수증 문서관리번호, 사업자별로 중복없이 1~24자리 영문, 숫자, '-', '_' 조합으로 구성
				);
				
			}

			break;


		//============================================================================================================================================
		//	팩스
		//============================================================================================================================================
		case "fax":

			$data = array(
				'api'			=> $param['api'],
				'action'		=> $param['action'],
				'client_code'	=> $client_code,
				'html_code'		=> $param['html_code'],									// 팩스내용 - PDF 변환용
				'reserveDT'		=> $param['reserveDT'] ? $param['reserveDT'] : null,	// 예약전송일시(yyyyMMddHHmmss) ex) 20151212230000, null인경우 즉시전송
				'Sender'		=> $param['Sender'],									// 팩스전송 발신번호
				'SenderName'	=> $param['SenderName'],								// 팩스전송 발신자명
				'title'			=> $param['title'],										// 팩스제목
				'Receivers'		=> serialize($param['Receivers'])						// 팩스 수신정보 최대 1000건 [배열]
																							// - rcv : 팩스 수신번호
																							// - rcvnm : 수신자명
			);

			break;

	}


	//============================================================================================================================================
	//	API 서버로 전송
	//============================================================================================================================================
	//	1. CURL 방식 -----------------------------------------------------------------------------------------------------------------------------
	if( $param['api_send'] == 'curl' ){
		$payload = json_encode($data);
		 
		// Prepare new cURL resource
		$ch = curl_init('http://api.design-factory.co.kr/NIT_curl.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		 
		// Set HTTP Header for POST request 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($payload))
		);
		 
		// Submit the POST request
		$result = curl_exec($ch);
		 
		// Close cURL session handle
		curl_close($ch);
	}
	//	2. SOCKET 방식 ---------------------------------------------------------------------------------------------------------------------------
	else{
		$url = 'http://api.design-factory.co.kr/NIT_socket.php';
		$type = 'POST';
		
		foreach ($data as $key => &$val) {
			if (is_array($val)) {
				$val = implode(',', $val);
			}
			$post_params[] = $key.'='.urlencode($val);
		}

		$post_string = implode('&', $post_params);

		$parts = parse_url($url);
		
		if ($parts['scheme'] == 'http') {
			$fp = fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 30);
		}
		else if ($parts['scheme'] == 'https') {
			$fp = fsockopen("ssl://" . $parts['host'], isset($parts['port'])?$parts['port']:443, $errno, $errstr, 30);
		}
		
		// Data goes in the path for a GET request  
		if('GET' == $type)
			$parts['path'] .= '?'.$post_string;

		$out  = "$type ".$parts['path']." HTTP/1.1\r\n";
		$out .= "Host: ".$parts['host']."\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: ".strlen($post_string)."\r\n";
		$out .= "Connection: Close\r\n\r\n";
		// Data goes in the request body for a POST request
		if ('POST' == $type && isset($post_string))
			$out .= $post_string;

		fwrite($fp, $out);
		fclose($fp);
	}

	//============================================================================================================================================
	//	결과값 리턴
	//============================================================================================================================================
	return $result;

}
?>