<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;

/**
 * [事務局]協会基本情報画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-20 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_Society_Register_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 設計担当者設定画面を表示します。
	 */
	public function action_index() {
		// TODO 権限チェック

		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('m_basicinfo')
					->get();
		$data += array("m_basicinfo" => $db_datas );

		// 会社を取得
		$companies = Companies::getAll();

		$data += array("companies" => $companies );


		// ページ固有JS
		Asset::add('page', 'js/pages/office/society_register.js', 'common');

		return View::make('office.society_register', $data);
	}

	/**
	 * 確認画面を表示します。
	 *
	 */
	public function action_confirm() {
		// ビューで使用する変数
		$data = array();

		$input = Session::get(KEY_FORM);

		$db_datas=DB::table('m_company')
					->where('company_code','=',$input['syukka'])
					->get();
		foreach($db_datas as $c) {
			$input['company_name'] = $c->company_name;
			break;
		}
		$data += array('input' => $input);


		// ページ固有JS
		Asset::add('page', 'js/pages/office/society_register.js', 'common');

		return View::make("office.society_register_check", $data);
	}

	/**
	 * 入力チェックを行います。
	 */
	public function action_check() {
		$input = Input::all();

		// 郵便番号1
		if ( $input['zip1'] ) {
			if ( !is_numeric($input['zip1']) || $input['zip1'] < 0 || 999 < $input['zip1'] ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'SR002',
					'args'   => array(
						'郵便番号1',
					)
				));
			}
		}

		// 郵便番号2
		if ( $input['zip2'] ) {
			if ( !is_numeric($input['zip2']) || $input['zip2'] < 0 || 9999 < $input['zip2'] ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'SR002',
					'args'   => array(
						'郵便番号2'
					)
				));
			}
		}

		// ページ固有JS
		Asset::add('page', 'js/pages/office/society_register.js', 'common');

		// セッションに登録
		Session::put(KEY_FORM, $input);

		$url = URL::to('office/society_register_check.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));

	}

	/**
	 * 更新を行います。
	 */
	public function action_finish() {
		$input = Input::all();

		for($i = 1; $i < 17; $i++ ){
			try {
				switch($i) {
					case 1:
						$tmp = $input['association'];
						break;
					case 2:
						$tmp = $input['zip1'];
						break;
					case 3:
						$tmp = $input['zip2'];
						break;
					case 4:
						$tmp = $input['address'];
						break;
					case 5:
						$tmp = $input['comany'];
						break;
					case 6:
						$tmp = $input['tel'];
						break;
					case 7:
						$tmp = $input['fax'];
						break;
					case 8:
						$tmp = $input['tanto'];
						break;
					case 9:
						$tmp = $input['email'];
						break;
					case 10:
						$tmp = $input['syukka'];
						break;
					case 11:
						$tmp = $input['bank_name1'];
						break;
					case 12:
						$tmp = $input['bank_name2'];
						break;
					case 13:
						$tmp = $input['bank_sybt'];
						break;
					case 14:
						$tmp = $input['bank_no'];
						break;
					case 15:
						$tmp = $input['bank_meigi'];
						break;
				}
				$result = DB::table('m_basicinfo')
					->where('basicinfo_id','=',$i)
					->update(array(
						'info_value' => $tmp,
						'update_date' => date("Y-m-d H:i:s")
					));
			}
			catch(Exception $ex) {
				Log::error($ex);
				return Response::json(array(
					'status'   => 'NG',
					'code'  => 'SR011'
				));
			}
		}


		$url = URL::to('office/society_register_finish.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 完了画面を表示します。
	 *
	 */
	public function action_complete() {
		// ビューで使用する変数
		$data = array();

		return View::make("office.society_register_finish", $data);
	}
}

?>