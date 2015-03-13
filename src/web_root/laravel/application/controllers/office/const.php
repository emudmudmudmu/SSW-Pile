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
 * [事務局]物件管理関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 109 $
 * $Date: 2013-09-30 11:34:03 +0900 (2013/09/30 (月)) $
 */
class Office_Const_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 物件登録画面を表示します。
	 */
	public function action_index() {
		$user = Session::get(KEY_USER);

		// 物件ステータス
		$status_list = Constructions::getAllStatus();

		// 指定施工会社一覧(TODO 理事会社も施工会社になり得る？)
		$constructors = Companies::getAll();

		// 設計会社一覧(TODO 理事会社も設計会社になり得る？)
		$designers = Companies::getAll();

		// 発注元一覧
		$contractee = Companies::getAll();

		// 施工管理技術者
		// ※本来は user -> company の company に紐付いているので
		// 　ログイン時に $user に紐付けておいても良いかもしれない
		// 　が、サイズが大きくなる場合もあるのでここで毎回取得する
		$company_code = $user->company
			->company_code;
		$engineers = Engineers::getAll($company_code);

		// 会社通番
		$company_seq = Constructions::getNextCompanySeq($company_code);

		// 材種
		$materials = Materials::getAll();

		// 種別
		$sybts = Constructions::getAllSyubetu();

		// 種別2
		$sybt2s = Constructions::getAllSyubetu_2();

		// 構造
		$kozos = Constructions::getAllKozo();

		// 基礎形式
		$kisos = Constructions::getAllKiso();

		// ビューで使用する変数
		$data = array(
			'status_list'  => $status_list,
			'user'         => $user,
			'constructors' => $constructors,
			'designers'    => $designers,
			'contractee'   => $contractee,
			'company_seq'  => $company_seq,
			'materials'    => $materials,
			'sybts'        => $sybts,
			'sybt2s'       => $sybt2s,
			'kozos'        => $kozos,
			'kisos'        => $kisos,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_register', $data);
	}


	/**
	 * 年度・会社コードをキーに、物件.通番の次の値を仮取得します。
	 */
	public function action_seq() {
		$input = Input::get();
		$max = Constructions::getNextCompanySeq($input['company_code'], $input['nendo']);

		return Response::json(array(
			'status' => 'OK',
			'seq'    => $max
		));
	}


	/**
	 * 会社コードをキーに、施工管理技術者の一覧を取得します。
	 */
	public function action_engineer() {
		$company_code = Input::get('company_code');
		$engineers = Engineers::get($company_code);
		$ret = array();
		foreach ( $engineers as $egnr ) {
			$obj = new stdClass();
			$obj->no = $egnr->no;
			$obj->name = $egnr->name;
			$ret[] = $obj;
		}

		return Response::json(array(
			'status'    => 'OK',
			'engineers' => $ret
		));
	}


	/**
	 * 会社コードをキーに、設計担当者の一覧を取得します。
	 */
	public function action_architect() {
		$company_code = Input::get('company_code');
		$architects = Architects::get($company_code);
		$ret = array();
		foreach ( $architects as $arch ) {
			$obj = new stdClass();
			$obj->no = $arch->no;
			$obj->name = $arch->name;
			$ret[] = $obj;
		}

		return Response::json(array(
			'status'     => 'OK',
			'architects' => $ret
		));
	}

	/**
	 * AJAXによる入力チェックを行ないます。
	 */
	public function action_check() {
		$input = Input::all();

		// 年月日の警告
		$str = '';
		$sep = '';
		if ( $input['construction_start_date'] == 'warn' ) {
			$str .= "{$sep}着工日";
			$sep = ', ';
			$input['construction_start_date'] = '';
		}
		if ( $input['complete_date'] == 'warn' ) {
			$str .= "{$sep}完工日";
			$sep = ', ';
			$input['complete_date'] = '';
		}
		if ( $input['report_date'] == 'warn' ) {
			$str .= "{$sep}報告書承認日";
			$sep = ', ';
			$input['report_date'] = '';
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
		if ( $input['construction_start_date'] ) {
			$date = explode('-', $input['construction_start_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'着工日'
					)
				));
			}
		}
		if ( $input['complete_date'] ) {
			$date = explode('-', $input['complete_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'完工日'
					)
				));
			}
		}
		if ( $input['report_date'] ) {
			$date = explode('-', $input['report_date']);
			if ( !checkdate($date[1], $date[2], $date[0]) ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC006',
					'args'   => array(
						'報告書承認日'
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
	 *
	 * @param string $page_type 'register'(登録機能)または'change'(編集機能)
	 */
	public function action_confirm($page_type) {

		$input = Session::get(KEY_FORM);

		// 施工会社(必須)
		$construction_company = Companies::get($input['construction_company']);

		// 設計会社名
		$architect_company = '';
		if ( $input['architect_company'] ) {
			$architect_company = Companies::get($input['architect_company'])->company_name;
		}

		// 発注元会社名
		$order_company = '';
		if ( $input['order_company'] ) {
			$order_company = Companies::get($input['order_company'])->company_name;
		}

		// 施工管理技術者名
		$engineer = '';
		if ( $input['engineer'] ) {
			$engineer = Engineers::getEngineer($input['engineer'])->name;
		}

		// 設計担当者名
		$architect = '';
		if ( $input['architect'] ) {
			$architect = Architects::getArchtect($input['architect'])->name;
		}

		// 材種
		$materials = Materials::getAll();


		// ビューで使用する変数
		$data = array(
			'input'                => $input,
			'construction_company' => $construction_company,
			'architect_company'    => $architect_company,
			'order_company'        => $order_company,
			'engineer'             => $engineer,
			'architect'            => $architect,
			'materials'            => $materials,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make("office.thing_{$page_type}_check", $data);
	}


	/**
	 * 物件の登録を行ないます。
	 */
	public function action_complete() {

		$input = Session::get(KEY_FORM);
		if ( !$input ) {
			// 二度押し対応
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MZ002");
			Session::put(KEY_ARGS, array('物件登録画面'));
			return Redirect::to_action('office.const@index');
		}
		Session::forget(KEY_FORM);

		// 登録
		Constructions::create($input);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_register_finish');
	}


	/**
	 * 物件検索画面の初期表示を行ないます。
	 */
	public function action_search() {

		// 会社を取得
		$companies = Companies::getAll();

		// 物件ステータスを取得
		$status = Constructions::getAllStatus();

		// ビューで使用する変数
		$data = array(
			'companies' => $companies,
			'status' => $status,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_search', $data);
	}


	/**
	 * 物件検索を行ないます。
	 */
	public function action_do_search() {
		// 入力値
		$input = Input::get();

		// 会社を取得
		$companies = Companies::getAll();

		// 物件ステータスを取得
		$status = Constructions::getAllStatus();

		// 検索
		$result = Constructions::search($input);

		// ビューで使用する変数
		$data = array(
			'companies' => $companies,
			'status_list' => $status,
			'result' => $result,
		);

		// 入力値展開
		$data += $input;

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_search_result', $data);
	}


	private function _set_values_by_script($input, $form_id = 'regF') {

		$construction_company = pad3($input->construction_company);
		$design_company = pad3($input->architect_company);
		$contactee = pad3($input->order_company);

		$script = <<< SCRIPT

var form = $("#{$form_id}").get(0);

if ( form ) {
	$("#constructor").val("{$construction_company}");
	$("#design_company").val("{$design_company}");
	$("#contractee").val("{$contactee}");
	$("#material_id").val("{$input->material_id}");
	$("#sybt").val("{$input->sybt}");
	$("#sybt2").val("{$input->sybt2}");
	$("#kozo").val("{$input->kouzou}");
	$("#kiso").val("{$input->kiso}");
	$("#status").val("{$input->status}");
	form.year.value                  = "{$input->nendo}";
	form.construction_name.value     = "{$input->construction_name}";
	form.work_place.value            = "{$input->construction_address}";
	form.number.value                = "{$input->amount}";
	form.use.value                   = "{$input->yoto}";
	form.floor.value                 = "{$input->floor}";
	form.height.value                = "{$input->height}";
	form.hotels_high.value           = "{$input->nokidake}";
	form.area.value                  = "{$input->totalarea}";
	form.depth_construction.value    = "{$input->depth}";
	_set_ymd("s", "{$input->construction_start_date}");
	_set_ymd("e", "{$input->complete_date}");
	_set_ymd("a", "{$input->report_date}");

	// 施工管理技術者・設計担当者のプルダウンを構築
	reloadEngineers();
	reloadArchitects();
	$("#architect").val("{$input->architect}");
	$("#engineer").val("{$input->engineer}");
}
SCRIPT;
		Session::put(KEY_SCRIPT, $script);
	}


	/**
	 * 物件情報変更画面を表示します。
	 *
	 * @param string  $construction_no 認定番号
	 * @param numeric $construction_eda 枝番
	 */
	public function action_change($construction_no, $construction_eda) {

		$construction = Constructions::get($construction_no, $construction_eda);

		$this->_set_values_by_script($construction);

		// 物件ステータス
		$status_list = Constructions::getAllStatus();

		// 指定施工会社一覧(TODO 理事会社も施工会社になり得る？)
		$constructors = Companies::getAll();

		// 設計会社一覧(TODO 理事会社も設計会社になり得る？)
		$designers = Companies::getAll();

		// 発注元一覧
		$contractee = Companies::getAll();

		// 材種
		$materials = Materials::getAll();

		// 種別
		$sybts = Constructions::getAllSyubetu();

		// 種別2
		$sybt2s = Constructions::getAllSyubetu_2();

		// 構造
		$kozos = Constructions::getAllKozo();

		// 基礎形式
		$kisos = Constructions::getAllKiso();

		// ビューで使用する変数
		$data = array(
			'construction' => $construction,
			'status_list'  => $status_list,
			'constructors' => $constructors,
			'designers'    => $designers,
			'contractee'   => $contractee,
			'materials'    => $materials,
			'sybts'        => $sybts,
			'sybt2s'       => $sybt2s,
			'kozos'        => $kozos,
			'kisos'        => $kisos,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_change', $data);
	}


	/**
	 * 物件情報編集の完了画面を表示します。
	 */
	public function action_change_complete() {

		$input = Session::get(KEY_FORM);
		if ( !$input ) {
			// 二度押し対応
			Session::put(KEY_WARN, true);
			Session::put(KEY_MSG_CODE, "MZ002");
			Session::put(KEY_ARGS, array('物件検索画面'));
			return Redirect::to_action('office.const@search');
		}
		Session::forget(KEY_FORM);

		// 更新
		Constructions::update($input);

		// ビューで使用する変数
		$data = array(
			'input' => $input
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/thing.js', 'common');

		return View::make('office.thing_change_finish', $data);
	}


}
