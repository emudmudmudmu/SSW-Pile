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

/**
 * パーツ発注履歴関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 108 $
 * $Date: 2013-09-30 10:22:42 +0900 (2013/09/30 (月)) $
 */
class Construction_Parts_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 一覧画面を表示します。
	 */
	public function action_index() {
		
		// ログインユーザー
		$user = Session::get(KEY_USER);
		
		// 入力値
		$input = array();

		$input['order_no']      = '';
		$input['company_code']  = $user->company->company_code; // 自社発注のみ
		$input['status']        = ORDER_SHIPPED; // 出荷済のみ
		$input['s_yyyy']        = '';
		$input['s_mm']          = '';
		$input['s_dd']          = '';
		$input['e_yyyy']        = '';
		$input['e_mm']          = '';
		$input['e_dd']          = '';
		
		// 会社一覧
		$companies = Companies::getAll();

		// 受注ステータス
		$status_list = Orders::getAllStatus();

		// 検索
		$result = Orders::search($input);

		// ビューで使用する変数
		$data = array(
			'companies'             => $companies,
			'status_list'           => $status_list,
			'result'                => $result,
			'shipping_company_name' => BasicInfo::getShippingCompanyName(),
		);

		// 入力値展開
		$data += $input;

		// チェックボックスが未選択の場合の対応
		if ( !isset($data['status']) ) {
			$data['status'] = array();
		}

		return View::make('construction.parts_order_history', $data);
	}


	/**
	 * 受注内容を表示します。
	 */
	public function action_detail($order_no) {
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
		Asset::add('page', 'js/pages/parts/slip.js', 'common');

		return View::make('construction.parts_order_history_detail', $data);
	}

}
