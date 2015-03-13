<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Prefs;

/**
 * [指定施工会社]指定施工会社名簿画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 65 $
 * $Date: 2013-09-25 12:20:49 +0900 (2013/09/25 (水)) $
 */
class Construction_Roster_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 指定施工会社名簿一覧画面を表示します。
	 */
	public function action_index() {
		// TODO 権限チェック

		// ビューで使用する変数
		$data = array();

		$db_datas = DB::table('m_company')
			->where('company_code', '<>', 0)
			->where_del_flg(0)
			->order_by('company_code', 'asc')
			->get();

		$data += array("m_company" => $db_datas );

		return View::make('construction.roster', $data);
	}


	/**
	 * 指定施工会社名簿詳細を表示します。
	 */
	public function action_detail($company_code) {
		$data = array();

		$db_datas = DB::table('m_company')
			->where('company_code', '=', $company_code)
			->get();
		$data += array("m_company" => $db_datas );

		$areas = DB::table('m_area')
			->where_company_code($company_code)
			->order_by('pref_code', 'asc')
			->get();
		$data += array("m_area" => $areas );

		$prefs = Prefs::getAll();
		$data += array("prefs" => $prefs );


		return View::make('construction.roster_detail', $data);
	}
}
