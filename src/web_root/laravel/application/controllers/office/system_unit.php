<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * [事務局]システム設定/単価設定画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-19 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_System_Unit_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 単価設定画面を表示します。
	 */
	public function action_index() {
		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('m_licensefees')
					->where('del_flg', '=', '0')
					->get();
		$data += array("m_licensefees" => $db_datas );

		$db_datas=DB::table('m_item')
					->where('del_flg', '=', '0')
					->get();
		$data += array("m_item" => $db_datas );


		// ページ固有JS
		Asset::add('page', 'js/pages/office/system_unit.js', 'common');

		return View::make('office.system_unit', $data);
	}

	/**
	 * 工法使用料単価設定を新規登録します。
	 */
	public function action_licensefees_new() {
		$l_start_date = Input::get('l_start_date');
		$l_end_date = Input::get('l_end_date');
		$l_licensefees_price1 = Input::get('l_licensefees_price1');
		$l_licensefees_price2 = Input::get('l_licensefees_price2');
		$l_licensefees_price3 = Input::get('l_licensefees_price3');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $l_start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SU002',
    			'args'     => array('適用開始日')
			));
		}
		// 日付チェックを行う
		$l_end_date = trim($l_end_date);
		if ($l_end_date) {
			list($y, $m, $d) = explode('/', $l_end_date);
			if( !checkdate($m, $d, $y) ) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU002',
	    			'args'     => array('適用終了日')
				));
			}

			if (strtotime($l_start_date) >= strtotime($l_end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU006'
				));
			}
		}
		else {
			$l_end_date = '9999-12-31 23:59:59';
		}

		if($l_licensefees_price1 =="") {
			$l_licensefees_price1 = 0;
		}
		if($l_licensefees_price2 =="") {
			$l_licensefees_price2 = 0;
		}
		if($l_licensefees_price3 =="") {
			$l_licensefees_price3 = 0;
		}


		try {
			$result = DB::table('m_licensefees')
				->insert(array(
					'start_date' => $l_start_date,
					'end_date' => $l_end_date,
					'licensefees_price1' => $l_licensefees_price1,
					'licensefees_price2' => $l_licensefees_price2,
					'licensefees_price3' => $l_licensefees_price3,
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU010'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 工法使用料単価設定を更新します。
	 */
	public function action_licensefees_update() {
		$licensefee_id = Input::get('licensefee_id');
		$l_start_date = Input::get('l_start_date');
		$l_end_date = Input::get('l_end_date');
		$l_licensefees_price1 = Input::get('l_licensefees_price1');
		$l_licensefees_price2 = Input::get('l_licensefees_price2');
		$l_licensefees_price3 = Input::get('l_licensefees_price3');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $l_start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SU002',
    			'args'     => array('適用開始日')
			));
		}
		// 日付チェックを行う
		$l_end_date = trim($l_end_date);
		if ($l_end_date) {
			list($y, $m, $d) = explode('/', $l_end_date);
			if( !checkdate($m, $d, $y) ) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU002',
	    			'args'     => array('適用終了日')
				));
			}

			if (strtotime($l_start_date) >= strtotime($l_end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU006'
				));
			}
		}
		else {
			$l_end_date = '9999-12-31 23:59:59';
		}

		if($l_licensefees_price1 =="") {
			$l_licensefees_price1 = 0;
		}
		if($l_licensefees_price2 =="") {
			$l_licensefees_price2 = 0;
		}
		if($l_licensefees_price3 =="") {
			$l_licensefees_price3 = 0;
		}


		try {
			$result = DB::table('m_licensefees')
				->where('licensefee_id','=',$licensefee_id)
				->update(array(
					'start_date' => $l_start_date,
					'end_date' => $l_end_date,
					'licensefees_price1' => $l_licensefees_price1,
					'licensefees_price2' => $l_licensefees_price2,
					'licensefees_price3' => $l_licensefees_price3,
					'update_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU011'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 工法使用料単価設定を削除します。
	 */
	public function action_licensefees_delete() {
		$licensefee_id = Input::get('licensefee_id');

		try {
			$result = DB::table('m_licensefees')
				->where('licensefee_id','=',$licensefee_id)
				->update(array(
					'del_flg' => '1',
					'delete_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU012'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}
















	/**
	 * パーツ単価設定を新規登録します。
	 */
	public function action_item_new() {
		$i_start_date = Input::get('i_start_date');
		$i_end_date = Input::get('i_end_date');
		$i_item_size = Input::get('i_item_size');
		$i_item_price1 = Input::get('i_item_price1');
		$i_item_sprice1 = Input::get('i_item_sprice1');
		$i_item_price2 = Input::get('i_item_price2');
		$i_item_sprice2 = Input::get('i_item_sprice2');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $i_start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SU062',
    			'args'     => array('適用開始日')
			));
		}
		// 日付チェックを行う
		$i_end_date = trim($i_end_date);
		if ($i_end_date) {
			list($y, $m, $d) = explode('/', $i_end_date);
			if( !checkdate($m, $d, $y) ) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU062',
	    			'args'     => array('適用終了日')
				));
			}

			if (strtotime($i_start_date) >= strtotime($i_end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU068'
				));
			}
		}
		else {
			$i_end_date = '9999-12-31 23:59:59';
		}

		if($i_item_size =="") {
			$i_item_size = 0;
		}
		if($i_item_price1 =="") {
			$i_item_price1 = 0;
		}
		if($i_item_sprice1 =="") {
			$i_item_sprice1 = 0;
		}
		if($i_item_price2 =="") {
			$i_item_price2 = 0;
		}
		if($i_item_sprice2 =="") {
			$i_item_sprice2 = 0;
		}

		// 日付チェック
		// 同一の先端翼径の適用終了日より後の適用開始日か確認
		try {
			$count=DB::table('m_item')
				->where('item_size', '=', $i_item_size)
				->where(function($query) use ($i_start_date) {
					$query->or_where('end_date', '>=', $i_start_date);
					$query->or_where('end_date', '=', '9999-12-31 23:59:59');
				})
				->where('del_flg', '=', 0)
				->count();
			if ($count > 0) {
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU073'
			));
							}
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU070'
			));
		}

		try {
			$result = DB::table('m_item')
				->insert(array(
					'start_date' => $i_start_date,
					'end_date' => $i_end_date,
					'item_size' => $i_item_size,
					'item_price1' => $i_item_price1,
					'item_sprice1' => $i_item_sprice1,
					'item_price2' => $i_item_price2,
					'item_sprice2' => $i_item_sprice2
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU070'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * パーツ単価設定を更新します。
	 */
	public function action_item_update() {
		$item_id = Input::get('item_id');
		$i_start_date = Input::get('i_start_date');
		$i_end_date = Input::get('i_end_date');
		$i_item_size = Input::get('i_item_size');
		$i_item_price1 = Input::get('i_item_price1');
		$i_item_sprice1 = Input::get('i_item_sprice1');
		$i_item_price2 = Input::get('i_item_price2');
		$i_item_sprice2 = Input::get('i_item_sprice2');

		// 日付チェックを行う
		list($y, $m, $d) = explode('/', $i_start_date);
		if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'SU062',
    			'args'     => array('適用開始日')
			));
		}
		// 日付チェックを行う
		$i_end_date = trim($i_end_date);
		if ($i_end_date) {
			list($y, $m, $d) = explode('/', $i_end_date);
			if( !checkdate($m, $d, $y) ) {
			return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU062',
	    			'args'     => array('適用終了日')
				));
			}

			if (strtotime($i_start_date) >= strtotime($i_end_date)) {
				return Response::json(array(
					'status'   => 'NG',
					'code'     => 'SU068'
				));
			}
		}
		else {
			$i_end_date = '9999-12-31 23:59:59';
		}

		if($i_item_size =="") {
			$i_item_size = 0;
		}
		if($i_item_price1 =="") {
			$i_item_price1 = 0;
		}
		if($i_item_sprice1 =="") {
			$i_item_sprice1 = 0;
		}
		if($i_item_price2 =="") {
			$i_item_price2 = 0;
		}
		if($i_item_sprice2 =="") {
			$i_item_sprice2 = 0;
		}

		// 日付チェック
		// 同一の先端翼径の適用終了日より後の適用開始日か確認
		try {
			$count=DB::table('m_item')
				->where('item_id', '<>', $item_id)
				->where('item_size', '=', $i_item_size)
				->where(function($query) use ($i_start_date) {
					$query->or_where('end_date', '>=', $i_start_date);
					$query->or_where('end_date', '=', '9999-12-31 23:59:59');
				})
				->where('del_flg', '=', 0)
				->count();
			if ($count > 0) {
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU073'
			));
							}
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU070'
			));
		}

		try {
			$result = DB::table('m_item')
				->where('item_id','=',$item_id)
				->update(array(
					'start_date' => $i_start_date,
					'end_date' => $i_end_date,
					'item_size' => $i_item_size,
					'item_price1' => $i_item_price1,
					'item_sprice1' => $i_item_sprice1,
					'item_price2' => $i_item_price2,
					'item_sprice2' => $i_item_sprice2,
					'update_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU071'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * パーツ単価設定を削除します。
	 */
	public function action_item_delete() {
		$item_id = Input::get('item_id');

		try {
			$result = DB::table('m_item')
				->where('item_id','=',$item_id)
				->update(array(
					'del_flg' => '1',
					'delete_date' => date("Y-m-d H:i:s")
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SU072'
			));
		}

		$url = URL::to('office/system_unit.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}
}

?>