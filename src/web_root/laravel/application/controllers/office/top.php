<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;

/**
 * 事務局TOP画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 125 $
 * $Date: 2013-10-02 14:09:29 +0900 (2013/10/02 (水)) $
 */
class Office_Top_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * ログイン後TOP画面を表示します。
	 */
	public function action_index() {
		return View::make('office.top');
	}
}
