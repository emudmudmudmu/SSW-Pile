<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * [事務局]システム設定/消費税設定画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-20 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_System_Tax_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 消費税設定画面を表示します。
	 */
	public function action_index() {
		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('m_ctax')
					->get();
		$data += array("m_ctax" => $db_datas );


		// ページ固有JS
		Asset::add('page', 'js/pages/office/system_tax.js', 'common');

		return View::make('office.system_tax', $data);
	}

	/**
	 * 新規登録します。
	 */
	public function action_new() {
		$start_date = Input::get('start_date');
		$end_date = Input::get('end_date');
		$rate = Input::get('rate');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'ST002',
    			'args'     => array('開始日')
			));
		}
		// 日付チェックを行う
		$end_date = trim($end_date);
		if ($end_date) {
			list($y, $m, $d) = explode('/', $end_date);
			if( !checkdate($m, $d, $y) ) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'ST002',
	    			'args'     => array('終了日')
				));
			}

			if (strtotime($start_date) >= strtotime($end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'ST004'
				));
			}
		}
		else {
			$end_date = '9999-12-31 23:59:59';
		}

		try {
			$result = DB::table('m_ctax')
				->insert(array(
					'start_date' => $start_date,
					'end_date' => $end_date,
					'rate' => $rate
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'ST010'
			));
		}

		$url = URL::to('office/system_tax.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 更新します。
	 */
	public function action_update() {
		$ctax_id = Input::get('ctax_id');
		$start_date = Input::get('start_date');
		$end_date = Input::get('end_date');
		$rate = Input::get('rate');

		// 日付チェックを行う
		// $l_start_date
		list($y, $m, $d) = explode('/', $start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'ST002',
    			'args'     => array('開始日')
			));
		}
		// 日付チェックを行う
		$end_date = trim($end_date);
		if ($end_date) {
			list($y, $m, $d) = explode('/', $end_date);
			if( !checkdate($m, $d, $y) ) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'ST002',
	    			'args'     => array('終了日')
				));
			}

			if (strtotime($start_date) >= strtotime($end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'ST004'
				));
			}
		}
		else {
			$end_date = '9999-12-31 23:59:59';
		}


		try {
			$result = DB::table('m_ctax')
				->where('ctax_id','=',$ctax_id)
				->update(array(
					'start_date' => $start_date,
					'end_date' => $end_date,
					'rate' => $rate
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'ST011'
			));
		}

		$url = URL::to('office/system_tax.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 削除します。
	 */
	public function action_delete() {
		$ctax_id = Input::get('ctax_id');

		try {
			$result = DB::table('m_ctax')
				->where('ctax_id','=',$ctax_id)
				->delete();
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'ST012'
			));
		}

		$url = URL::to('office/system_tax.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

}

?>