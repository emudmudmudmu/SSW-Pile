<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * 指定施工会社TOP画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 65 $
 * $Date: 2013-09-25 12:20:49 +0900 (2013/09/25 (水)) $
 */
class Construction_Top_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * ログイン後TOP画面を表示します。
	 */
	public function action_index() {
		// TODO 権限チェック

		// ビューで使用する変数
		$data = array();

		$db_datas=DB::table('d_news')
					->get();
		$data += array("d_news" => $db_datas );

		return View::make('construction.top', $data);
	}


	/**
	 * 協会からのお知らせ詳細を表示します。
	 */
	public function action_detail($news_id) {
		$data = array();

		$db_datas=DB::table('d_news')
					->where('news_id', '=', $news_id)
					->get();
		$data += array("d_news" => $db_datas );

		return View::make('construction.top_detail', $data);
	}
}
