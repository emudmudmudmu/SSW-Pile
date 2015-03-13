<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * パーツ出荷担当TOP画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 65 $
 * $Date: 2013-09-25 12:20:49 +0900 (2013/09/25 (水)) $
 */
class Parts_Top_Controller extends Parts_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * ログイン後TOP画面を表示します。
	 */
	public function action_index() {
		return View::make('parts.top');
	}
}
