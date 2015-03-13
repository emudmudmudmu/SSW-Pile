<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;
use Logics\OrderHelper;
use Masters\Parts;
use Masters\LicenseFees;
use Masters\Ctax;
use Logics\BillHelper;
use Masters\BasicInfo;

/**
 * 請求情報関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 195 $
 * $Date: 2013-10-08 17:40:57 +0900 (2013/10/08 (火)) $
 */
class Office_Bill_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 検索画面を表示します。
	 */
	public function action_index() {
		
		// 会社一覧
		$companies = Companies::getAll();
		

		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/bill.js', 'common');

		return View::make('office.request_search', $data);
	}


	/**
	 * 請求検索を行ないます。
	 */
	public function action_search() {
		// 入力値
		$input = Input::get();
	
		// 会社一覧
		$companies = Companies::getAll();
	
		// 検索
		$result = Bills::search($input);
		
		// 一括請求可能かどうか
		$combined = array();
		$target   = array();
		foreach ( $result as $bill ) {
			$combined[] = $bill->company_name;
			if ( $bill->result_type != RESULT_TYPE_BILL ) {
				$target[] = "{$bill->result_type}:{$bill->key}";
			}
		}
	
		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
			'result'       => $result,
			'combined'     => ( count( array_unique($combined) ) == 1 ? TRUE : FALSE ),
			'target'       => implode(',', $target),
		);
		
		// 入力値展開
		$data += $input;
		
		// ページ固有JS
		Asset::add('page', 'js/pages/office/bill.js', 'common');
	
		return View::make('office.request_search_result', $data);
	}
	
	
	private function _create_bill_from_order ($key) {
		// 検索
		$order = Orders::get($key);
		
		// 請求先
		$company = Companies::get($order->order_company);
		
		// 請求年月
		$date = date_create_from_format('Y-m-d H:i:s', $order->order_date);
		
		/* 請求金額 */
		$total = $order->subtotal + $order->shipping_fee;
		$tax   = intval(floor($total * $order->rate / 100));
		
		$result = array(
			'company_name' => $company->company_name,
			'bill_no'      => '',
			'bill_nen'     => date_format($date, 'Y'),
			'bill_tuki'    => date_format($date, 'n'),
			'bill_date'    => '',
			'total'        => $total + $tax,
			'meisai_type'  => RESULT_TYPE_ORDER,
			'meisai'       => array(),
		);
		
		$i = 1;
		foreach ( $order->meisai as $meisai ) {
			$line = array(
				'meisai_date_md'   => date_format($date, 'n/j'),
				'bill_name'        => LABEL_PARTS . " {$meisai->item_size} " . getLabelBy('weldings', $meisai->item_type),
				'quantity'         => "{$meisai->quantity}本",
				'price'            => $meisai->item_sprice,
				'sub_total'        => $meisai->item_sprice * $meisai->quantity,
				'shipping_name'    => $order->shipping_name,
				'url'              => URL::to("office/{$key}/order_detail.html#meisai{$i}"),
			);
			$result['meisai'][] = $line;
			$i++;
		}
		
		// 運賃
		$line = array(
			'meisai_date_md'   => date_format($date, 'n/j'),
			'bill_name'        => LABEL_SHIPPING,
			'quantity'         => '',
			'price'            => $order->shipping_fee,
			'sub_total'        => $order->shipping_fee,
			'shipping_name'    => $order->shipping_name,
			'url'              => URL::to("office/{$key}/order_detail.html#shipping"),
		);
		$result['meisai'][] = $line;
		
		return $result;
	}
	
	private function _create_bill_from_construction ($key) {
		// 検索
		$const = Constructions::get($key, 1);
		
		// 請求先
		$company = Companies::get($const->order_company);
		
		// 請求年月
		$date = date_create_from_format('Y-m-d', $const->complete_date);
		
		/* 請求金額 */
		// 工法使用料
		$license_fee = LicenseFees::get($const->complete_date, $const->amount);
			
		// 消費税
		$rate = Ctax::getBy($const->complete_date);
		$tax  = intval(floor($license_fee * $rate / 100));
		
		$result = array(
			'company_name' => $company->company_name,
			'bill_no'      => '',
			'bill_nen'     => date_format($date, 'Y'),
			'bill_tuki'    => date_format($date, 'n'),
			'bill_date'    => '',
			'total'        => $license_fee + $tax,
			'meisai_type'  => RESULT_TYPE_CONST,
			'meisai'       => array(
				array(
					'meisai_date_md'   => date_format($date, 'n/j'),
					'bill_name'        => LABEL_LICENSEFEE,
					'quantity'         => "一式 （打設{$const->amount}本）",
					'price'            => $license_fee,
					'sub_total'        => $license_fee,
				),
			),
		);
		
		return $result;
	}
	
	private function _create_bill_from_bill($key) {
		$result = get_object_vars(Bills::get($key));
		return BillHelper::createBill($result);
	}
	
	/**
	 * 請求情報の詳細画面を表示します
	 *
	 * @param int   $result_type 結果タイプ(1:請求情報, 2:物件情報, 3:受注情報)
	 * @param mixed $key 各情報の主キーの値
	 */
	public function action_result($result_type, $key) {
		$result = '';
		switch ( $result_type ) {
			/* 請求情報 */
			case RESULT_TYPE_BILL:
				$result = $this->_create_bill_from_bill($key);
				break;
			
			/* 物件情報 */
			case RESULT_TYPE_CONST:
				$result = $this->_create_bill_from_construction($key);
				break;
			
			/* 受注情報 */
			case RESULT_TYPE_ORDER:
				$result = $this->_create_bill_from_order($key);
				break;
		}
		
		
		// ビューで使用する変数
		$data = array(
			'type'   => $result_type,
			'key'    => $key,
			'result' => $result,
		);
		
		// ページ固有JS
		Asset::add('page', 'js/pages/office/bill.js', 'common');
	
		return View::make('office.request_result', $data);
	}
	
	
	/**
	 * 請求情報を作成します。[AJAX]
	 */
	public function action_create_bill($result_type, $key) {
		$bill_no = '';
		switch ( $result_type ) {
			/* 請求情報 */
			case RESULT_TYPE_BILL:
				// このリクエストはあり得ない
				break;
			
			/* 物件情報 */
			case RESULT_TYPE_CONST:
				$bill_no = Bills::createFromConstruction($key);
				break;
			
			/* 受注情報 */
			case RESULT_TYPE_ORDER:
				$bill_no = Bills::createFromOrder($key);
				break;
		}
		
		Session::put(KEY_SUCCESS, TRUE);
		Session::put(KEY_MSG_CODE, 'MC202');
		Session::put(KEY_ARGS, array('請求情報の登録', '請求書印刷ボタン', '請求書'));
		Session::put(KEY_SCRIPT, "\nwindow.reload_flg = true;//戻った場合は検索フォーム再送信\n");
		
		return Response::json(array(
			'status'  => 'OK',
			'bill_no' => $bill_no,
		));
	}
	
	
	/**
	 * 請求書の再発行を行います。[AJAX]
	 */
	public function action_reissue() {
		
		$input = Input::all();
		
		// 年月日のチェック
		$date = explode('-', $input['bill_date']);
		if ( !checkdate($date[1], $date[2], $date[0]) ) {
			return Response::json(array(
				'status' => 'NG',
				'code'   => 'MC006',
				'args'   => array(
					'請求書発行日'
				)
			));
		}
		
		$input['reissue_date'] = REQUESTED_DATE;
		Bills::update($input);
		
		Session::put(KEY_SUCCESS, TRUE);
		Session::put(KEY_MSG_CODE, 'MC202');
		Session::put(KEY_ARGS, array('請求情報の更新', '請求書印刷ボタン', '請求書'));
		Session::put(KEY_SCRIPT, "\nwindow.reload_flg = true;//戻った場合は検索フォーム再送信\n");
		
		return Response::json(array(
			'status'  => 'OK',
			'bill_no' => $input['bill_no'],
		));
	}
	
	
	/**
	 * 一括請求書を発行します。[AJAX]
	 */
	public function action_combined() {
		$target = Input::get('target');
		$bill_no = Bills::createFrom($target);
		
		if ( $bill_no ) {
			Session::put(KEY_SUCCESS, TRUE);
			Session::put(KEY_MSG_CODE, 'MC205');
			Session::put(KEY_ARGS, array("請求書No.{$bill_no}"));
			return Response::json(array(
				'status'  => 'OK',
			));
		}
		
		return Response::json(array(
			'status'  => 'NG',
		));
	}
	
	
	/**
	 * 入金日を登録します。[AJAX]
	 */
	public function action_payment() {
		
		$input = Input::all();
		
		// 年月日のチェック
		$date = explode('-', $input['payment_date']);
		if ( !checkdate($date[1], $date[2], $date[0]) ) {
			return Response::json(array(
				'status' => 'NG',
				'code'   => 'MC006',
				'args'   => array(
					'入金日'
				)
			));
		}
		
		Bills::update($input);
		
		Session::put(KEY_SUCCESS, TRUE);
		Session::put(KEY_MSG_CODE, 'MC202');
		Session::put(KEY_ARGS, array('入金処理', '領収書印刷ボタン', '領収書'));
		Session::put(KEY_SCRIPT, "\nwindow.reload_flg = true;//戻った場合は検索フォーム再送信\n");
		
		return Response::json(array(
			'status'  => 'OK',
		));
	}
	
	
}