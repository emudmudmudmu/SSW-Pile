<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Masters\Users;
use Laravel\Session;

/**
 * ログイン関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 73 $
 * $Date: 2013-09-26 13:18:31 +0900 (2013/09/26 (木)) $
 */
class Home_Controller extends Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * ログインページを表示します。
	 */
	public function action_index() {

		// ページ固有JS
		Asset::add('page', 'js/pages/common/login.js', 'common');

		return View::make('common.login');
	}

	/**
	 * ログイン処理を実行します。
	 */
	public function action_login() {

		$login_id = Input::get('login_id');
		$passwd = Input::get('passwd');
		
		// ログイン
		$user = Users::login($login_id, $passwd);

		if ( $user ) {
			
			// セッションに格納
			Session::put(KEY_USER, $user);
			
			// 権限によるURLの分岐
			$url = '';
			switch ( $user->auth_type ) {
				case AUTH_MANAGER:
				case AUTH_SYSADMIN:
					$url = URL::to('office/top.html');
					break;
				case AUTH_MEMBER:
					$url = URL::to('construction/top.html');
					break;
				case AUTH_SHIPPING:
					$url = URL::to('parts/top.html');
					break;
			}
				
			
			return Response::json(array(
				'status'   => 'OK',
				'url' => $url
			));
		}
		
		return Response::json(array(
			'status'   => 'NG',
			'message' => 'MC101'
		));
	}
	
	public function action_logout() {
		
		// セッションの削除
		Session::flush();
		
		// メッセージの登録
		Session::put(KEY_INFO, true);
		Session::put(KEY_MSG_CODE, "MC102");
		Session::put(KEY_ARGS, array());
		
		// ページ固有JS
		Asset::add('page', 'js/pages/common/login.js', 'common');

		return View::make('common.login');
	}
}
