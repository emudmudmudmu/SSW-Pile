<?php


use Masters\Parts;
use Masters\Ctax;
use Utils\MailService;
use Masters\BasicInfo;
/**
 * パーツ発注関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 215 $
 * $Date: 2013-10-13 07:11:29 +0900 (2013/10/13 (日)) $
 */
class Construction_Order_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * パーツ発注画面を表示します。
	 */
	public static function action_index() {

		// ログインユーザー
		$user = Session::get(KEY_USER);

		// パーツ仕様
		$weldings = Parts::getAllWelding();

		// パーツ
		$items = Parts::getAll($user->company->company_type);

		// ビューで使用する変数
		$data = array(
			'items'   => $items,
			'weldings' => $weldings,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/order.js', 'common');

		return View::make('construction.parts_order', $data);
	}


	/**
	 * 注文登録内容の入力チェックを行ないます。
	 */
	public function action_check() {
		$input = Input::all();

		// 年月日のチェック
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

		$input = Session::get(KEY_FORM);

		// ログインユーザー
		$user = Session::get(KEY_USER);

		// パーツ仕様
		$weldings = Parts::getAllWelding();

		// パーツ
		$items = Parts::getAll($user->company->company_type);

		// 消費税
		$rate = Ctax::get();

		// 注文内容
		$orders = $input['orders'];
		$total = 0;
		foreach ( $orders as &$order ) {
			// パーツ単価の取得等
			$order += Parts::createMeisai($order['item_id'], $order['item_type'], $order['quantity'], $user->company->company_type);
			$subtotal = $order['item_sprice'] * $order['quantity'];
			$order['subtotal'] = $subtotal;
			$order['tax']      = intval(floor($subtotal * (100 + $rate) / 100) - $subtotal);
			$total += $order['subtotal'] + $order['tax'];
			unset($order);
		}

		// ビューで使用する変数
		$data = array(
			'input'   => $input,
			'orders'  => $orders,
			'total'   => $total,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/order.js', 'common');

		return View::make('construction.parts_order_check', $data);
	}


	/**
	 * パーツ発注を行ない、完了画面を表示します。
	 */
	public function action_complete() {


		$input = Session::get(KEY_FORM);
		if ( !$input ) {
			// 二度押し対応
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MZ002");
			Session::put(KEY_ARGS, array('パーツ発注画面'));
			return Redirect::to_action('construction.order@index');
		}
		Session::forget(KEY_FORM);

		// ログインユーザー
		$user = Session::get(KEY_USER);

		// 登録
		$order_no = Orders::create($input, $user->company);

		// メール送信
		$order = Orders::get($order_no);
		$cc = '';
		if ( $input['email'] ) {
			$cc = "{$input['shipping_person']}様 <{$input['email']}>";
		}
		$to = BasicInfo::getShippingCompanyEmail();

		$order->bi_shipping_company = BasicInfo::getShippingCompanyName();
		$order->tax = intval(floor($order->subtotal * $order->rate / 100));

		$subject = '[SSW-Pile工法協会] パーツ発注依頼のお知らせ';
		try {
			MailService::send_mail($to, $subject, 'mail.order', get_object_vars($order), '', $cc);
		}
		catch (Exception $ex){
			Log::error(var_export($ex, TRUE));
			Session::put(KEY_WARN, TRUE);
			Session::put(KEY_MSG_CODE, "MZ007");
			$mail = Config::get('email.bcc');
			Session::put(KEY_ARGS, array($mail, $mail, $mail));
		}
		
		// ビューで使用する変数
		$data = array(
			'order_no' => $order_no,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/order.js', 'common');

		return View::make('construction.parts_order_finish', $data);
	}

}
