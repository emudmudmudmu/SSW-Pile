<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * [事務局]システム設定/材種設定画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-20 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_System_Material_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 単価設定画面を表示します。
	 */
	public function action_index() {
		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('m_material')
					->get();
		$data += array("m_material" => $db_datas );


		// ページ固有JS
		Asset::add('page', 'js/pages/office/system_material.js', 'common');

		return View::make('office.system_material', $data);
	}

	/**
	 * 新規登録します。
	 */
	public function action_new() {
		$material_code = Input::get('material_code');
		$material_name = Input::get('material_name');

		try {
			$result = DB::table('m_material')
				->where('material_code', '=', $material_code)
				->count();

			if($result>0) {
				return Response::json(array(
					'status'   => 'NG',
					'code'  => 'SM004'
				));
			}
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SM010'
			));
		}

		try {
			$result = DB::table('m_material')
				->insert(array(
					'material_code' => $material_code,
					'material_name' => $material_name
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SM004'
			));
		}

		$url = URL::to('office/system_material.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 更新します。
	 */
	public function action_update() {
		$material_id = Input::get('material_id');
		$material_code = Input::get('material_code');
		$material_name = Input::get('material_name');

		try {
			$result = DB::table('m_material')
				->where('material_id', '!=', $material_id)
				->where('material_code', '=', $material_code)
				->count();

			if($result>0) {
				return Response::json(array(
					'status'   => 'NG',
					'code'  => 'SM004'
				));
			}
		}
				catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SM010'
			));
		}

		try {
			$result = DB::table('m_material')
				->where('material_id','=',$material_id)
				->update(array(
					'material_code' => $material_code,
					'material_name' => $material_name
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SM011'
			));
		}

		$url = URL::to('office/system_material.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 削除します。
	 */
	public function action_delete() {
		$material_id = Input::get('material_id');

		try {
			$result = DB::table('m_material')
				->where('material_id','=',$material_id)
				->delete();
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'SM012'
			));
		}

		$url = URL::to('office/system_material.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

}

?>