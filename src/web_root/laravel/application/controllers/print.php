<?php

use Laravel\Session;
use Masters\Companies;
use Masters\BasicInfo;

/**
 * PDF帳票発行用コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 211 $
 * $Date: 2013-10-12 00:19:12 +0900 (2013/10/12 (土)) $
 */
class Print_Controller extends Base_Controller {
	
	
	private function _auth_error($companies) {
		// 権限の制御
		$user = Session::get(KEY_USER);
		
		$codes = array();
		foreach ( $companies as $company ) {
			$codes[] = $company->company_code;
		}
		
		// 理事権限、または引数に指定された会社のユーザーのみ
		// 許可する
		if ( $user->auth_type != AUTH_MANAGER
		  && $user->auth_type != AUTH_SYSADMIN
		  && !in_array($user->company->company_code, $codes) ) {
			Session::put(KEY_WARN, TRUE);
			Session::put(KEY_MSG_CODE, "MZ004");
			return TRUE;
		}
		return FALSE;
	}
	
	
	private function _auth_error_with_shipping($companies) {
		// 権限の制御
		$user = Session::get(KEY_USER);
		
		$codes = array();
		foreach ( $companies as $company ) {
			$codes[] = $company->company_code;
		}
		
		// 理事権限、または引数に指定された会社のユーザーのみ
		// 許可する
		if ( $user->auth_type != AUTH_MANAGER
		  && $user->auth_type != AUTH_SYSADMIN
		  && $user->auth_type != AUTH_SHIPPING
		  && !in_array($user->company->company_code, $codes) ) {
			Session::put(KEY_WARN, TRUE);
			Session::put(KEY_MSG_CODE, "MZ004");
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	 * 請求書印刷を行ないます。
	 *
	 * @param int $bill_no 請求書No.
	 */
	public function action_bill($bill_no) {
		
		$bill = Bills::get($bill_no);
		$company = Companies::get($bill->bill_company);
		
		// 権限チェック
		if ( $this->_auth_error(array($company)) ) {
			return Redirect::to_action('home@index');
		}
		
		$pdf = new Pdf_Bill();
		//		$pdf->setDebug(true);
	
		/* ヘッダ */
		$pdf->setDear("{$company->company_name} 御中");
		$pdf->setDate($bill->bill_date);
		$pdf->setNo($bill->bill_no);
	
		// 合計金額欄
		$pdf->setBillingAmount($bill->total);
	
		// 明細行作成
		$details = array();
		foreach ( $bill->meisai as $meisai ) {
			$details[] = array(
					$meisai->meisai_date,
					$meisai->bill_name,
					$meisai->quantity,
					$meisai->price,
					$meisai->sub_total,
					'', // 摘要
			);
		}
		$pdf->setDetails($details);
	
		// フッタ
		$pdf->setSubTotal($bill->total - $bill->tax);
		$pdf->setTax($bill->tax);
		$pdf->setTotal($bill->total);
	
	
		$name = 'Bill_' . pad5($bill_no) . '.pdf';
		return Response::make($pdf->output($name), 200, array('Content-Type' => 'application/pdf'));
	}
	
	
	/**
	 * 領収書印刷を行ないます。
	 *
	 * @param int $bill_no 請求書No.
	 */
	public function action_receipt($bill_no) {
	
		$bill = Bills::get($bill_no);
		$company = Companies::get($bill->bill_company);
	
		// 権限チェック
		if ( $this->_auth_error(array($company)) ) {
			return Redirect::to_action('home@index');
		}
		
		$pdf = new Pdf_Receipt();
		//		$pdf->setDebug(true);
	
		/* ヘッダ */
		$pdf->setDear("{$company->company_name} 御中");
		$pdf->setDate($bill->payment_date); // 日付は入金日
		$pdf->setNo($bill->bill_no);
	
		$pdf->setIndentedLead(1, $company->address);
		$pdf->setIndentedLead(2, "{$company->tanto} 様");
		$pdf->setIndentedLead(3, $company->email);
		$pdf->setIndentedLead(4, $company->tel);
		$pdf->setIndentedLead(5, $company->fax);
	
		// 明細行作成
		$details = array();
		foreach ( $bill->meisai as $meisai ) {
			$details[] = array(
					$meisai->bill_name,
					$meisai->quantity,
					$meisai->price,
					$meisai->sub_total,
			);
		}
		$pdf->setDetails($details);
	
		// フッタ
		$pdf->setSubTotal($bill->total - $bill->tax);
		$pdf->setTax($bill->tax);
		$pdf->setTotal($bill->total);
	
	
		$name = 'Receipt_' . pad5($bill_no) . '.pdf';
		return Response::make($pdf->output($name), 200, array('Content-Type' => 'application/pdf'));
	}
	
	
	/**
	 * 納品書印刷を行ないます。
	 *
	 * @param int $order_no 注文番号
	 */
	public function action_slip($order_no) {
	
		$order = Orders::get($order_no);
		$company = Companies::get($order->order_company);
	
		// 権限チェック
		if ( $this->_auth_error_with_shipping(array($company)) ) {
			return Redirect::to_action('home@index');
		}
		
		// 納品書はパーツ受注に対して発行されるので、
		// 対応する受注情報が無ければエラー
		if ( !$order ) {
			throw new Exception("存在しない注文No.が指定されました。");
		}
		
		
		$pdf = new Pdf_Slip();
		//		$pdf->setDebug(true);
	
		/* ヘッダ */
		$pdf->setDear("{$company->company_name} 御中");
		$pdf->setDate($order->shipping_date); // 日付は出荷日
		$pdf->setNo($order->order_no);
	
		$pdf->setIndentedLead(0, $order->arrival_date); // 納入希望日
		$pdf->setIndentedLead(1, $order->shipping_company);
		$pdf->setIndentedLead(2, $order->shipping_name);
		$pdf->setIndentedLead(3, $order->shipping_address);
		if ( $order->shipping_person ) {
			$pdf->setIndentedLead(4, "{$order->shipping_person} 様");
		}
		$pdf->setIndentedLead(5, $order->email);
		$pdf->setLastLeadLeftValue($order->tel);
		$pdf->setLastLeadRightValue($order->fax);
	
		// 明細行作成
		$details = array();
		foreach ( $order->meisai as $meisai ) {
			$details[] = array(
					LABEL_PARTS . " {$meisai->item_size} " . getLabelBy('weldings', $meisai->item_type),
					$meisai->quantity,
					$meisai->item_sprice,
					$meisai->item_sprice * $meisai->quantity,
			);
		}
		// 運賃
		$details[] = array(
				LABEL_SHIPPING,
				0,
				$order->shipping_fee,
				$order->shipping_fee,
		);
		
		
		$pdf->setDetails($details);
		
		// フッタ
		$subtotal = $order->subtotal + $order->shipping_fee;
		$pdf->setSubTotal($subtotal);
		$tax = intval(floor($subtotal * $order->rate / 100));
		$pdf->setTax($tax);
		$pdf->setTotal($subtotal + $tax);
	
	
		$name = 'Slip_' . pad5($order_no) . '.pdf';
		return Response::make($pdf->output($name), 200, array('Content-Type' => 'application/pdf'));
	}
	
	
	/**
	 * 受注伝票兼出荷指示書の印刷を行ないます。
	 *
	 * @param int $order_no 注文番号
	 */
	public function action_inst($order_no) {
	
		$order = Orders::get($order_no);
		$company = Companies::get($order->order_company);
	
		// 権限チェック
		if ( $this->_auth_error_with_shipping(array($company)) ) {
			return Redirect::to_action('home@index');
		}
		
		// 納品書はパーツ受注に対して発行されるので、
		// 対応する受注情報が無ければエラー
		if ( !$order ) {
			throw new Exception("存在しない注文No.が指定されました。");
		}
		
		
		$pdf = new Pdf_Inst();
		//		$pdf->setDebug(true);
	
		/* ヘッダ */
		$pdf->setDear("{$company->company_name} 御中");
		$pdf->setDate($order->shipping_date); // 日付は出荷日
		$pdf->setNo($order->order_no);
	
		$pdf->setIndentedLead(0, $order->arrival_date); // 納入希望日
		$pdf->setIndentedLead(1, $order->shipping_company);
		$pdf->setIndentedLead(2, $order->shipping_name);
		$pdf->setIndentedLead(3, $order->shipping_address);
		if ( $order->shipping_person ) {
			$pdf->setIndentedLead(4, "{$order->shipping_person} 様");
		}
		$pdf->setIndentedLead(5, $order->email);
		$pdf->setLastLeadLeftValue($order->tel);
		$pdf->setLastLeadRightValue($order->fax);
	
		// 明細行作成
		$details = array();
		foreach ( $order->meisai as $meisai ) {
			$details[] = array(
					LABEL_PARTS . " {$meisai->item_size} " . getLabelBy('weldings', $meisai->item_type),
					$meisai->quantity,
					$meisai->item_sprice,
					$meisai->item_sprice * $meisai->quantity,
			);
		}
		// 運賃
		$details[] = array(
				LABEL_SHIPPING,
				0,
				$order->shipping_fee,
				$order->shipping_fee,
		);
		
		
		$pdf->setDetails($details);
		
		// フッタ
		$subtotal = $order->subtotal + $order->shipping_fee;
		$pdf->setSubTotal($subtotal);
		$tax = intval(floor($subtotal * $order->rate / 100));
		$pdf->setTax($tax);
		$pdf->setTotal($subtotal + $tax);
	
	
		$name = 'Slip_' . pad5($order_no) . '.pdf';
		return Response::make($pdf->output($name), 200, array('Content-Type' => 'application/pdf'));
	}
	
	
	/**
	 * 施工実績件数表を印刷します。
	 */
	public function action_cr() {
		
		// ログインユーザー
		$user = Session::get(KEY_USER);
		
		// 権限チェック
		// ※会社別制限を設けないので、引数にログインユーザーの会社を渡してチェックを回避
		// ※ログインユーザーの権限のみチェックする
		if ( $this->_auth_error(array($user->company)) ) {
			return Redirect::to_action('home@index');
		}
		// POSTパラメータで集計期間を取得
		$input = Input::all();
		
		// 物件ステータスが「完了」のものが実績表の集計対象
		$input['status'] = CONSTRUCT_COMPLETE;
		
		// 検索
		$result = Constructions::search($input);
		
		// 実績件数表の生成
		$pdf = new Pdf_Cr($result);
		$start = pad4($input['e_s_yyyy']) .'-'. pad2($input['e_s_mm']) .'-'. pad2($input['e_s_dd']);
		$end   = pad4($input['e_e_yyyy']) .'-'. pad2($input['e_e_mm']) .'-'. pad2($input['e_e_dd']);
		$pdf->setSpan($start, $end);
		
		// 実績件数表の出力
		$name = 'ConstructionResult.pdf';
		return Response::make($pdf->output($name), 200, array('Content-Type' => 'application/pdf'));
	}
}
