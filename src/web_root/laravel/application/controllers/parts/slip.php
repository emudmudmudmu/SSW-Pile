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
use Utils\MailService;

/**
 * 受注情報関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 108 $
 * $Date: 2013-09-30 10:22:42 +0900 (2013/09/30 (月)) $
 */
class Parts_Slip_Controller extends Parts_Base_Controller {

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
		Asset::add('page', 'js/pages/parts/slip.js', 'common');

		return View::make('parts.slip_search', $data);
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
		Asset::add('page', 'js/pages/parts/slip.js', 'common');

		return View::make('parts.slip_search_result', $data);
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
		OrderHelper::setValuesByScript($order, 'regF', TRUE);

		// ビューで使用する変数
		$data = array(
			'companies'    => $companies,
			'status_list'  => $status_list,
			'weldings_list'  => $weldings_list,
			'order'        => $order,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/parts/slip.js', 'common');

		return View::make('parts.slip_detail', $data);
	}


	/**
	 * 入力内容のチェックを行ないます。
	 */
	public function action_check() {
		$input = Input::all();

		// 年月日のチェック
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
	 * パーツ受注情報の更新を行ないます。
	 */
	public function action_complete() {

		$input = Session::get(KEY_FORM);
		if ( !$input ) {
			// 二度押し対応
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MZ002");
			Session::put(KEY_ARGS, array('検索画面'));
			return Redirect::to_action('parts.slip@index');
		}
		Session::forget(KEY_FORM);
		
		// ステータスは必ず「出荷済」
		$input['order_status'] = ORDER_SHIPPED;

		// 更新
		Orders::update($input);
		
		// メール送信
		OrderHelper::sendShippedMail($input['order_no']);

		// ページ固有JS
		Asset::add('page', 'js/pages/parts/slip.js', 'common');

		return View::make('parts.slip_detail_finish', array('order_no' => $input['order_no']));
	}


}
