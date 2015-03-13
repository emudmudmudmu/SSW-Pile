<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Prefs;
use Masters\Companies;
use Masters\Users;
use Laravel\Redirect;
use Laravel\Config;
use Utils\MailService;
use Laravel\View;
use Masters\Materials;

/**
 * [指定施工会社]請求関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 120 $
 * $Date: 2013-10-01 12:46:58 +0900 (2013/10/01 (火)) $
 */
class Construction_Bill_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 受取請求履歴画面を表示します。
	 */
	public function action_index() {
		$user = Session::get(KEY_USER);
	
		// 請求情報を取得
		$bills = Bills::getBy($user->company_code);
	
		// ビューで使用する変数
		$data = array(
			'bills' => $bills,
		);
	
		// ページ固有JS
		Asset::add('page', 'js/pages/construction/bill.js', 'common');
	
		return View::make('construction.receipt_billing_history', $data);
	}
	


	/**
	 * 受取請求詳細画面を表示します。
	 */
	public function action_detail($bill_no) {
		$user = Session::get(KEY_USER);
		
		// 請求情報を取得
		$bill = Bills::get($bill_no);

		// ビューで使用する変数
		$data = array(
			'bill' => $bill,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/bill.js', 'common');

		return View::make('construction.receipt_billing_detail', $data);
	}

}
