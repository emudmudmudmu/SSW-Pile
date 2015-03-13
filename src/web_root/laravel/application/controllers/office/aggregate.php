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
 * 集計処理関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 196 $
 * $Date: 2013-10-08 20:55:58 +0900 (2013/10/08 (火)) $
 */
class Office_Aggregate_Controller extends Office_Base_Controller {

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
		Asset::add('page', 'js/pages/office/aggregate.js', 'common');

		return View::make('office.aggregate_search', $data);
	}

	/**
	 * 検索します。
	 */
	public function action_search() {
		$s_yyyy = Input::get('s_yyyy');
		$s_mm = Input::get('s_mm');
		$s_dd = Input::get('s_dd');
		$e_yyyy = Input::get('e_yyyy');
		$e_mm = Input::get('e_mm');
		$e_dd = Input::get('e_dd');

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
		$url = URL::to('office/aggregate_search_result.html');

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

		$start_date = $input['s_yyyy'].'-'.$input['s_mm'].'-'.$input['s_dd'].' 00:00:00';
		$end_date = $input['e_yyyy'].'-'.$input['e_mm'].'-'.$input['e_dd'].' 23:59:59';

		$db_datas = DB::query("
						SELECT company_code, COUNT( DISTINCT d_bill.bill_no ) count , company_name, bill_type, SUM( sub_total ) as price
						FROM sswd_bill d_bill
						JOIN sswd_bill_meisai meisai USING ( bill_no )
						JOIN sswm_company company ON ( company.company_code = d_bill.bill_company )
						where meisai_date >= ? AND meisai_date < ?
						GROUP BY bill_type, bill_company
						order by company_code, count DESC",
						array($start_date, $end_date));

		$data += array('d_bill' => $db_datas);
		$data += array('input' => $input);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/aggregate.js', 'common');

		return View::make('office.aggregate_search_result', $data);
	}

	/**
	 * 結果を表示します。
	 */
	public function action_csv() {
		// ビューで使用する変数
		$data = array();
		$input = Session::get(KEY_FORM);

		$start_date = $input['s_yyyy'].'-'.$input['s_mm'].'-'.$input['s_dd'].' 00:00:00';
		$end_date = $input['e_yyyy'].'-'.$input['e_mm'].'-'.$input['e_dd'].' 23:59:59';

		$db_datas = DB::query("
						SELECT company_code, COUNT( DISTINCT d_bill.bill_no ) count , company_name, bill_type, SUM( sub_total ) as price
						FROM sswd_bill d_bill
						JOIN sswd_bill_meisai meisai USING ( bill_no )
						JOIN sswm_company company ON ( company.company_code = d_bill.bill_company )
						where meisai_date >= ? AND meisai_date < ?
						GROUP BY bill_type, bill_company
						order by company_code, count DESC",
						array($start_date, $end_date));

		$data = NULL;
		$dd = NULL;
		$company_code = "";
		$index = 0;
		foreach($db_datas as $bill) {
			if($company_code <> $bill->company_code){
				if( $dd != NULL ) {
					if($data == NULL) {
						$data = array($index => $dd);
						$index++;
					}
					else {
						array_push($data,$index++,$dd);
					}
				}

				$company_code = $bill->company_code;
				$count = $bill->count;
				$dd = NULL;
				$dd = array(
					'company_code' => $bill->company_code,
					'company_name' => $bill->company_name,
					'count' => $bill->count
				);
			}

			if( $bill->bill_type == '1' ) {
				$dd['type1'] = $bill->price;
			}
			if( $bill->bill_type == '2' ) {
				$dd['type2'] = $bill->price;
			}
		}
		if( $dd != NULL ) {
			if($data == NULL) {
				$data = array($index => $dd);
				$index++;
			}
			else {
				array_push($data,$index++,$dd);
			}
		}

		$file_name = path('storage') . 'work/aggregate.csv';
		try {
			File::delete($file_name);
		}
		catch(Exception $ex) {
			Log::error($ex);
		}
		$str = "会社コード,指定施工会社,工事件数,工法使用料,パーツ購入額\n";
		$str = mb_convert_encoding($str, "SJIS", "UTF-8");
		File::append($file_name, $str);
		foreach($data as $bill) {
			if(!isset($bill['company_name'])) continue;
			$str = $bill['company_code'].',';
			$str .= $bill['company_name'].',';
            $str .= $bill['count'].',';
			if(isset($bill['type1'])) {
				$str .= $bill['type1'];
			}
			$str .= ',';
			if(isset($bill['type2'])) {
				$str .= $bill['type2'];
			}
			$str .= "\n";
			$str = MailService::convert_chars($str);
			$str = mb_convert_encoding($str, "SJIS", "UTF-8");
			File::append($file_name, $str);
		}

		return Response::download($file_name);
	}

}