<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Companies;

/**
 * [事務局]事務局からのお知らせ画面関連コントローラー
 *
 * $Author: murayama $
 * $Rev: 32 $
 * $Date: 2013-09-20 15:17:45 +0900 (2013/09/19 (木)) $
 */
class Office_News_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 事務局からのお知らせ画面を表示します。
	 */
	public function action_index() {
		// TODO 権限チェック

		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('d_news')
					->get();
		$data += array("d_news" => $db_datas );

		// ページ固有JS
		Asset::add('page', 'js/pages/office/news.js', 'common');

		return View::make('office.news', $data);
	}

	/**
	 * 確認画面を表示します。
	 *
	 */
	public function action_confirm() {
		// ビューで使用する変数
		$data = array();

		$input = Session::get(KEY_FORM);
		$data += array('input' => $input);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/news.js', 'common');

		return View::make("office.news_check", $data);
	}

	/**
	 * 入力チェックを行います。
	 */
	public function action_check() {
		$input = Input::all();

		// 日付チェックを行う
		$date_format = 'Y/m/d';
		$input['news_date'] = trim($input['news_date']);
		$time = strtotime($input['news_date']);
		$is_valid = date($date_format, $time) == $input['news_date'];
		if(!$is_valid) {
			return Response::json(array(
				'status'   => 'NG',
				'code'     => 'NW003'
			));
		}


		// ページ固有JS
		Asset::add('page', 'js/pages/office/news.js', 'common');

		// セッションに登録
		Session::put(KEY_FORM, $input);

		$url = URL::to('office/news_check.html');

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

		try {
			$result = DB::table('d_news')
				->insert(array(
					'news_date' => $input['news_date'],
					'news_title' => $input['news_title'],
					'news_content' => $input['news_content']
				));
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'NW010'
			));
		}


		$url = URL::to('office/news_finish.html');

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

		return View::make("office.news_finish", $data);
	}


	/**
	 * 削除を行います。
	 */
	public function action_delete() {
		$input = Input::all();

		try {
			$result = DB::table('d_news')
				->where('news_id','=',$input['news_id'])
				->delete();
		}
		catch(Exception $ex) {
			Log::error($ex);
			return Response::json(array(
				'status'   => 'NG',
				'code'  => 'NW012'
			));
		}


		$url = URL::to('office/news_delete_finish.html');

		return Response::json(array(
			'status'   => 'OK',
			'url' => $url
		));
	}

	/**
	 * 完了画面を表示します。
	 *
	 */
	public function action_delete_complete() {
		// ビューで使用する変数
		$data = array();

		return View::make("office.news_delete_finish", $data);
	}
}

?>