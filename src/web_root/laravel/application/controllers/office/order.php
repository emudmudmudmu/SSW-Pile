<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;
use Logics\OrderHelper;
use Masters\Parts;
use Masters\BasicInfo;
use Logics\BillHelper;
use Utils\MailService;

/**
 * 受注情報関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 196 $
 * $Date: 2013-10-08 20:55:58 +0900 (2013/10/08 (火)) $
 */
class Office_Order_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 一覧画面を表示します。
	 */
	public function action_index() {
		
		// 会社一覧
		$companies = Companies::getAll();
		
		// 受注ステータス
		$status_list = Orders::getAllStatus();

		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
			'status_list'  => $status_list,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');

		return View::make('office.order_search', $data);
	}


	/**
	 * 受注検索を行ないます。
	 */
	public function action_search() {
		// 入力値
		$input = Input::get();
	
		// 会社一覧
		$companies = Companies::getAll();
	
		// 受注ステータス
		$status_list = Orders::getAllStatus();
	
		// 検索
		$result = Orders::search($input);
	
		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
			'status_list'  => $status_list,
			'result'       => $result,
		);
	
		// 入力値展開
		$data += $input;
		
		// チェックボックスが未選択の場合の対応
		if ( !isset($data['status']) ) {
			$data['status'] = array();
		}
	
		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');
	
		return View::make('office.order_search_result', $data);
	}
	

	/**
	 * 受注内容を表示します。
	 */
	public function action_change($order_no) {
		// 会社一覧
		$companies = Companies::getAll();
		
		// 受注ステータス
		$status_list = Orders::getAllStatusLong();
		
		// 仕様
		$weldings_list = Parts::getAllWelding();

		// 取得
		$order = Orders::get($order_no);
		
		// 値設定用のJavaScriptを発行
		OrderHelper::setValuesByScript($order);
		
		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
			'status_list'  => $status_list,
			'weldings_list'  => $weldings_list,
			'order'        => $order,
		);
		
		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');

		return View::make('office.order_detail', $data);
	}
	
	
	/**
	 * 入力内容のチェックを行ないます。
	 */
	public function action_check() {
		$input = Input::all();

		// 年月日の警告
		$str = '';
		$sep = '';
		if ( $input['shipping_date'] == 'warn' ) {
			$str .= "{$sep}出荷日";
			$sep = ', ';
			$input['shipping_date'] = '';
		}
		if ( $input['delivery_date'] == 'warn' ) {
			$str .= "{$sep}到着予定日";
			$sep = ', ';
			$input['delivery_date'] = '';
		}
		if ( $str ) {
			// 入力エラー(年月日のいずれかが欠けている)であれば確認画面で表示される
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MC010");
			Session::put(KEY_ARGS, array(
				$str
			));
		}

		// 年月日のチェック
		if ( $input['order_date'] ) {
			$date = explode('-', $input['order_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'注文日'
					)
				));
			}
		}
		if ( $input['arrival_date'] ) {
			$date = explode('-', $input['arrival_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'納入希望日'
					)
				));
			}
		}
		if ( $input['shipping_date'] ) {
			$date = explode('-', $input['shipping_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'出荷日'
					)
				));
			}
		}
		if ( $input['delivery_date'] ) {
			$date = explode('-', $input['delivery_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'到着予定日'
					)
				));
			}
		}

		// セッションに登録
		Session::put(KEY_FORM, $input);

		return Response::json(array(
			'status' => 'OK'
		));
	}


	/**
	 * 確認画面を表示します。
	 */
	public function action_confirm() {
		// 入力値
		$input = Session::get(KEY_FORM);
		
		// 金額の再計算
		$item_total = 0;
		foreach ( $input['meisai'] as &$meisai ) {
			$meisai['subtotal'] = intval($meisai['sprice']) * intval($meisai['quantity']);
			$item_total += $meisai['subtotal'];
			unset($meisai);
		}
		$shipping_fee = intval($input['shipping_fee']);
		$tax = intval(floor( ($item_total + $shipping_fee) * intval($input['rate']) / 100 ));
		$total = $item_total + $shipping_fee + $tax;
		
		
		// 施工会社(発注会社)名
		$company = Companies::get($input['order_company']);
		
		
		// ビューで使用する変数
		$data = array(
			'company_name'  => $company->company_name,
			'input'         => $input,
			'item_total'    => $item_total,
			'tax'           => $tax,
			'total'         => $total,
		);
		
		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');

		return View::make('office.order_detail_check', $data);
	}
	
	
	/**
	 * パーツ受注情報の更新を行ないます。
	 */
	public function action_complete($order_no) {
		
		$input = Session::get(KEY_FORM);
		if ( !$input ) {
			// 二度押し対応
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MZ002");
			Session::put(KEY_ARGS, array('受注情報変更画面'));
			return Redirect::to_action('office.order@change', array($order_no));
		}
		Session::forget(KEY_FORM);

		// 更新
		Orders::update($input);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');

		return View::make('office.order_detail_finish');
	}

	
	/**
	 * パーツ代金精算検索画面を表示します。
	 */
	public function action_pay_search() {
	
		// ビューで使用する変数
		$data = array(
		);
	
		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');
	
		return View::make('office.parts_price_search', $data);
	}
	

	/**
	 * パーツ代金精算画面を表示します。
	 */
	public function action_pay() {
		
		// 入力値
		$input = Input::all();
		
		// パーツ出荷場所
		$company_name = BasicInfo::getShippingCompanyName();
		
		// 計算
		list($bills, $orders, $order_total, $bill_total) = Bills::calcClearing($input['yyyy'], $input['mm']);
		
		// ビューで使用する変数
		$data = array(
			'input'        => $input,
			'company_name' => $company_name,
			'bills'        => $bills,
			'orders'       => $orders,
			'order_total'  => $order_total,
			'bill_total'   => $bill_total,
		);
	
		// ページ固有JS
		Asset::add('page', 'js/pages/office/order.js', 'common');
	
		return View::make('office.parts_price_search_result', $data);
	}
	
	
	/**
	 * パーツ代金精算用のCSVを出力します。
	 * 
	 * @param string $ym YYYYmm 形式の文字列
	 */
	public function action_clearing($ym) {
		
		$y = substr($ym, 0, 4);
		$m = substr($ym, 4, 2);
		
		$f = path('storage') . "work/clearing_{$ym}.csv";
		try {
			File::delete($f);
		}
		catch(Exception $ex) {
			Log::error(var_export($ex, TRUE));
		}
		
		// 取得
		list($bills, $orders, $order_total, $bill_total) = Bills::calcClearing($y, $m);
		
		// 受注内容
		$this->_write_line($f, "■発注内容内訳（{$y}年{$m}月）");
		$this->_write_line($f, "注文日,注文No.,金額(税込),指定施工会社,納入先");
		foreach ( $orders as $order ) {
			$line = '';
			
			$line .=  "{$order['order_date']}";
			$line .= ",{$order['order_no']}";
			$line .= ",{$order['total']}";
			$line .= ",{$order['company_name']}";
			$line .= ",{$order['shipping_name']}";
			
			$this->_write_line($f, $line);
		}
		
		// １行空け
		$this->_write_line($f, '');
		
		// 請求内容
		$this->_write_line($f, "■請求書内容内訳（{$y}年{$m}月）");
		$this->_write_line($f, "請求書No.,注文月日,品名,数量,単価,金額(税抜),納入先");
		foreach ( $bills as $bill ) {
			foreach ( $bill['meisai'] as $meisai ) {
				
				if ( $meisai['bill_type'] == HINMOKU_LICENSE ) {
					// 工法使用料はスキップ
					continue;
				}
				
				$line = '';
				
				$line .= "{$meisai['bill_no']}";
				$line .= ",{$meisai['meisai_date_md']}";
				$line .= ",{$meisai['bill_name']}";
				$line .= ",{$meisai['quantity']}";
				$line .= ",{$meisai['price']}";
				$line .= ",{$meisai['sub_total']}";
				$line .= ",{$meisai['shipping_name']}";
				
				
				$this->_write_line($f, $line);
			}
		}
		
		return Response::download($f);
	}
	
	private function _write_line($filename, $str) {
		$str = MailService::convert_chars($str);
		$str = mb_convert_encoding($str, "SJIS", "UTF-8");
		File::append($filename, "{$str}\n");
	}
}
