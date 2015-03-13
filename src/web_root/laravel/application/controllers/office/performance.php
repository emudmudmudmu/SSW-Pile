<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;
use Logics\OrderHelper;
use Masters\Parts;
use Masters\LicenseFees;
use Masters\Ctax;
use Logics\BillHelper;
use Utils\MailService;

/**
 * 実績表出力関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 206 $
 * $Date: 2013-10-11 02:04:23 +0900 (2013/10/11 (金)) $
 */
class Office_Performance_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 検索画面を表示します。
	 */
	public function action_index() {


		// ビューで使用する変数
		$data = array(
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/performance.js', 'common');

		return View::make('office.performance_search', $data);
	}

	/**
	 * 検索します。
	 */
	public function action_search() {
		$s_yyyy = Input::get('e_s_yyyy');
		$s_mm = Input::get('e_s_mm');
		$s_dd = Input::get('e_s_dd');
		$e_yyyy = Input::get('e_e_yyyy');
		$e_mm = Input::get('e_e_mm');
		$e_dd = Input::get('e_e_dd');

		// 日付チェックを行う
		if( !checkdate($s_mm, $s_dd, $s_yyyy) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'CC005',
    			'args'     => array('開始日')
			));
		}

		if( !checkdate($e_mm, $e_dd, $e_yyyy) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'CC005',
    			'args'     => array('終了日')
			));
		}

		Session::put(KEY_FORM,Input::all());
		$url = URL::to('office/performance_search_result.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 結果を表示します。
	 */
	public function action_result() {
		// ビューで使用する変数
		$data = array();
		$input = Session::get(KEY_FORM);
		// 必ずステータスが「完了」のもの
		$input['status'] = CONSTRUCT_COMPLETE;

		$db_datas = Constructions::search($input);
		
		$data += array('d_construction' => $db_datas);
		$data += array('input' => $input);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/performance.js', 'common');

		return View::make('office.performance_search_result', $data);
	}


	/**
	 * 結果を表示します。
	 */
	public function action_csv() {
		// ビューで使用する変数
		$data = array();
		$input = Session::get(KEY_FORM);
		// 必ずステータスが「完了」のもの
		$input['status'] = CONSTRUCT_COMPLETE;
		
		$db_datas = Constructions::search($input);
		
		$file_name = path('storage') . 'work/construction.csv';
		try {
			File::delete($file_name);
		}
		catch(Exception $ex) {
			Log::error($ex);
		}
		$str = "認定番号,枝番,施工会社,識別年度,会社通番,設計会社,設計担当者,施工管理技術者,";
		$str .= "発注元,工事名称,工事場所,着工日,完工日,報告書承認日,打設本数,材種ID,種別,種別2,";
		$str .= "構造,用途,基礎形式,階数,高さ,軒高,延べ面積,最大施工深さ,請求書番号,ステータス\n";
		$str = mb_convert_encoding($str, "SJIS", "UTF-8");
		File::append($file_name, $str);
		foreach($db_datas as $data) {
			$str =  $data->construction_no.',';
			$str .= $data->construction_eda.',';
			$str .= $data->construction_company.',';
			$str .= $data->nendo.',';
			$str .= $data->company_seq.',';
			$str .= $data->architect_company.',';
			$str .= $data->architect.',';
			$str .= $data->engineer.',';
			$str .= $data->order_company.',';
			$str .= $data->construction_name.',';
			$str .= $data->construction_address.',';
			$str .= $data->construction_start_date.',';
			$str .= $data->complete_date.',';
			$str .= $data->report_date.',';
			$str .= $data->amount.',';
			$str .= $data->material_id.',';
			$str .= $data->sybt.',';
			$str .= $data->sybt2.',';
			$str .= $data->kouzou.',';
			$str .= $data->yoto.',';
			$str .= $data->kiso.',';
			$str .= $data->floor.',';
			$str .= $data->height.',';
			$str .= $data->nokidake.',';
			$str .= $data->totalarea.',';
			$str .= $data->depth.',';
			$str .= $data->bill_no.',';
			$str .= $data->status;

			$str .= "\n";
			$str = MailService::convert_chars($str);
			$str = mb_convert_encoding($str, "SJIS", "UTF-8");
			File::append($file_name, $str);
		}

		return Response::download($file_name);
	}
}