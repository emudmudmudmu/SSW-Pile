<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;

/**
 * [事務局]システム設定/設計担当者設定設定画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-20 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_System_Architect_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 設計担当者設定画面を表示します。
	 */
	public function action_index() {
		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('m_architect')
					->where('del_flg', '=', '0')
					->get();
		$data += array("m_architect" => $db_datas );

		// 会社を取得
		$companies = Companies::getAll();
		$data += array("companies" => $companies );

		// ページ固有JS
		Asset::add('page', 'js/pages/office/system_architect.js', 'common');

		return View::make('office.system_architect', $data);
	}

	/**
	 * 新規登録します。
	 */
	public function action_new() {
		$name = Input::get('name');
		$company_code = Input::get('company_code');
		$certificated = Input::get('certificated');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $certificated);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SA002',
    			'args'     => array('認定日')
			));
		}

		try {
			$result = DB::table('m_architect')
				->insert(array(
					'name' => $name,
					'company_code' => $company_code,
					'certificated' => $certificated
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SA010'
			));
		}

		$url = URL::to('office/system_architect.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 更新します。
	 */
	public function action_update() {
		$no = Input::get('no');
		$name = Input::get('name');
		$company_code = Input::get('company_code');
		$certificated = Input::get('certificated');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $certificated);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SA002',
    			'args'     => array('認定日')
			));
		}

		try {
			$result = DB::table('m_architect')
				->where('no','=',$no)
				->update(array(
					'name' => $name,
					'company_code' => $company_code,
					'certificated' => $certificated,
					'update_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SA011'
			));
		}

		$url = URL::to('office/system_architect.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 削除します。
	 */
	public function action_delete() {
		$no = Input::get('no');

		try {
			$result = DB::table('m_architect')
				->where('no','=',$no)
				->update(array(
					'del_flg' => '1',
					'delete_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SA012'
			));
		}

		$url = URL::to('office/system_architect.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

}

?>