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
use Logics\MemberHelper;

/**
 * [事務局]会員登録画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 215 $
 * $Date: 2013-10-13 07:11:29 +0900 (2013/10/13 (日)) $
 */
class Office_Member_Controller extends Office_Base_Controller {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 会員登録画面を表示します。
	 */
	public function action_index() {
		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'prefs' => $prefs
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_register', $data);
	}


	/**
	 * 確認画面を表示します。
	 *
	 * @param string $page_type 'register'(登録機能)または'change'(編集機能)
	 */
	public function action_confirm($page_type) {

		$input = Session::get(KEY_FORM);
		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'input' => $input,
			'prefs' => $prefs
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make("office.member_{$page_type}_check", $data);
	}


	/**
	 * 登録内容の入力チェックを行ないます。
	 */
	public function action_check($skip) {

		$input = Input::all();

		// 登録画面の場合
		if ( $skip == 'check' ) {
			// 会社コード
			if ( !is_numeric($input['company_code']) || $input['company_code'] < 0 || 999 < $input['company_code'] ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC005',
					'args'   => array(
						'会社コード',
						3,
					)
				));
			}

			// 会社コードの存在チェック
			if ( Companies::exist($input['company_code']) ) {
				return MemberHelper::createAjaxNGResponseWithMC003(pad3($input['company_code']), '会社コード', '会社コード');
			}


			// 指定施工会社用ログインアカウント
			if ( Users::exist($input['member_id']) ) {
				return MemberHelper::createAjaxNGResponseWithMC003($input['member_id'], '指定施工会社用ログインアカウント', 'ID');
			}

			if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT ) {

				// 理事会社用ログインアカウント
				if ( Users::exist($input['manager_id']) ) {
					return MemberHelper::createAjaxNGResponseWithMC003($input['manager_id'], '理事会社用ログインアカウント', 'ID');
				}
			}

			if ( $input['company_type'] == COMPANY_MANAGER ) {

				// パーツ出荷担当用ログインアカウント
				if ( Users::exist($input['shipping_id']) ) {
					return MemberHelper::createAjaxNGResponseWithMC003($input['shipping_id'], 'パーツ出荷担当用ログインアカウント', 'ID');
				}
			}
		}
		// 変更画面の場合
		else {
			$company_code = $input['company_code'];

			// ログインIDに変更があれば、存在チェック
			// 指定施工会社用ログインアカウント
			$user = Users::get($company_code, AUTH_MEMBER);
			if ( $input['member_id'] != $user->login_id ) {
				if ( Users::exist($input['member_id']) ) {
					return MemberHelper::createAjaxNGResponseWithMC003($input['member_id'], '指定施工会社用ログインアカウント', 'ID');
				}
			}

			if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT ) {
				// 理事会社用ログインアカウント
				$user = Users::get($company_code, AUTH_MANAGER);
				if ( $user && $input['manager_id'] != $user->login_id ) {
					if ( Users::exist($input['manager_id']) ) {
						return MemberHelper::createAjaxNGResponseWithMC003($input['manager_id'], '理事会社用ログインアカウント', 'ID');
					}
				}
			}

			if ( $input['company_type'] == COMPANY_MANAGER ) {
				// パーツ出荷担当用ログインアカウント
				$user = Users::get($company_code, AUTH_SHIPPING);
				if ( $user && $input['shipping_id'] != $user->login_id ) {
					if ( Users::exist($input['shipping_id']) ) {
						return MemberHelper::createAjaxNGResponseWithMC003($input['shipping_id'], 'パーツ出荷担当用ログインアカウント', 'ID');
					}
				}
			}
		}

		if ( $input['company_type'] == COMPANY_MANAGER ) {
			// 重複ID指定チェック
			$dup_check = array(
				$input['member_id'],
				$input['manager_id'],
				$input['shipping_id']
			);
			if ( count(array_unique($dup_check)) != 3 ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC004',
					'args'   => array(
						'各ログインアカウント(指定施工会社用・理事会社用・パーツ出荷担当用)のID'
					)
				));
			}
		}

		if ( $input['company_type'] == COMPANY_JOINT ) {
			// 重複ID指定チェック
			$dup_check = array(
				$input['member_id'],
				$input['manager_id']
			);
			if ( count(array_unique($dup_check)) != 2 ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC004',
					'args'   => array(
						'各ログインアカウント(指定施工会社用・理事会社用)のID'
					)
				));
			}
		}

		// 加入日
		if ( !checkdate($input['join_dateM'], $input['join_dateD'], $input['join_dateY']) ) {
			return Response::json(array(
				'status' => 'NG',
				'code'   => 'MC006',
				'args'   => array(
					'加入日',
				)
			));
		}

		// 郵便番号1
		if ( $input['zip1'] ) {
			if ( !is_numeric($input['zip1']) || strlen($input['zip1']) != 3 ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC011',
					'args'   => array(
						'郵便番号1',
						'3桁の半角数字',
					)
				));
			}
		}

		// 郵便番号2
		if ( $input['zip2'] ) {
			if ( !is_numeric($input['zip2']) || strlen($input['zip2']) != 4 ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'MC011',
					'args'   => array(
						'郵便番号2',
						'4桁の半角数字',
					)
				));
			}
		}

		// 施工エリアチェックボックスは、
		// チェックが無い場合パラメータとして渡されないので、
		// 補完する
		if ( !isset($input['areas']) ) {
			$input['areas'] = array();
		}

		// セッションに登録
		Session::put(KEY_FORM, $input);

		return Response::json(array(
			'status' => 'OK'
		));
	}


	/**
	 * 完了画面を表示します。
	 */
	public function action_complete() {

		$input = Session::get(KEY_FORM);

		// $input の中身を更新できるのは action_check のみなので、
		// 入力チェックは行なわない
		// ただし、会社コード・各ログインIDの存在チェックは再度行なう

		// 会社コードの存在チェック
		if ( Companies::exist($input['company_code']) ) {
			Session::put(KEY_ARGS, array(
				'会社コード',
				pad3($input['company_code']),
				'会社コード'
			));
			return $this->_to_index_page_with_mc007($input);
		}

		// 指定施工会社用ログインアカウント
		if ( Users::exist($input['member_id']) ) {
			Session::put(KEY_ARGS, array(
				'指定施工会社用ログインアカウント',
				$input['member_id'],
				'ID',
			));
			return $this->_to_index_page_with_mc007($input);
		}

		if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT ) {

			// 理事会社用ログインアカウント
			if ( Users::exist($input['manager_id']) ) {
				Session::put(KEY_ARGS, array(
					'理事会社用ログインアカウント',
					$input['manager_id'],
					'ID',
				));
				return $this->_to_index_page_with_mc007($input);
			}
		}

		if ( $input['company_type'] == COMPANY_MANAGER ) {

			// パーツ出荷担当用ログインアカウント
			if ( Users::exist($input['shipping_id']) ) {
				Session::put(KEY_ARGS, array(
					'パーツ出荷担当用ログインアカウント',
					$input['shipping_id'],
					'ID',
				));
				return $this->_to_index_page_with_mc007($input);
			}
		}

		// 登録
		Companies::create($input);

		// メール送信
		$to = "{$input['tanto']}様 <{$input['email']}>";
		$subject = '[SSW-Pile工法協会] 会員登録のお知らせ';
		try {
			MailService::send_mail($to, $subject, 'mail.entry', $input);
		}
		catch (Exception $ex){
			Log::error(var_export($ex, TRUE));
			Session::put(KEY_WARN, TRUE);
			Session::put(KEY_MSG_CODE, "MZ007");
			$mail = Config::get('email.bcc');
			Session::put(KEY_ARGS, array($mail, $mail, $mail));
		}
		


		// ビューで使用する変数
		$data = array(
			'input' => $input
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_register_finish', $data);
	}


	private function _to_index_page_with_mc007($input) {
		// エラーコード固定
		Session::put(KEY_WARN, true);
		Session::put(KEY_MSG_CODE, 'MC007');

		// Ajaxで値を送信した時の逆を実行
		// JavaScriptで画面に値を設定させる
		MemberHelper::setValuesByScript($input);

		return Redirect::to_action('office.member@index');
	}

	private function _to_change_page_with_mc007($input) {
		// エラーコード固定
		Session::put(KEY_WARN, true);
		Session::put(KEY_MSG_CODE, 'MC007');

		// Ajaxで値を送信した時の逆を実行
		// JavaScriptで画面に値を設定させる
		MemberHelper::setValuesByScript($input);

		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'prefs'        => $prefs,
			'company_code' => $input['company_code'],
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_change', $data);
	}


	/**
	 * 会員検索画面の初期表示を行ないます。
	 */
	public function action_search() {

		// 会社を取得
		$companies = Companies::getAll();

		// ビューで使用する変数
		$data = array(
			'companies' => $companies,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_change_search', $data);
	}


	/**
	 * 会員情報変更画面を表示します。
	 *
	 * @param numeric $company_code 会社コード
	 */
	public function action_change($company_code) {

		$company = Companies::get($company_code);

		// 入力フォーム形式に変換
		$input = array();
		$input['company_code']  = pad3($company->company_code);
		$input['company_name']  = $company->company_name;
		$input['ceo']           = $company->ceo;
		$input['zip1']          = $company->zip1;
		$input['zip2']          = $company->zip2;
		$input['address']       = $company->address;
		$input['tel']           = $company->tel;
		$input['fax']           = $company->fax;
		$input['tanto']         = $company->tanto;
		$input['email']         = $company->email;
		$input['join_dateY']    = substr($company->join_date, 0, 4);
		$input['join_dateM']    = substr($company->join_date, 5, 2);
		$input['join_dateD']    = substr($company->join_date, 8, 2);
		$input['member_id']     = $company->member->login_id;
		$input['member_pswd']   = $company->member->passwd;
		if ( $company->company_type == COMPANY_MANAGER || $company->company_type == COMPANY_JOINT ) {
			$input['manager_id']    = $company->manager->login_id;
			$input['manager_pswd']  = $company->manager->passwd;
		}
		else {
			$input['manager_id']    = '';
			$input['manager_pswd']  = '';
		}
		if ( $company->company_type == COMPANY_MANAGER ) {
			$input['shipping_id']   = $company->shipping->login_id;
			$input['shipping_pswd'] = $company->shipping->passwd;
		}
		else {
			$input['shipping_id']   = '';
			$input['shipping_pswd'] = '';
		}
		$input['company_type']  = $company->company_type;
		$areas = array();
		foreach ( $company->areas as $area ) {
			$areas[] = $area->pref_code;
		}
		$input['areas'] = $areas;
		
		MemberHelper::setValuesByScript($input);

		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'prefs'        => $prefs,
			'company_code' => $company_code,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_change', $data);
	}


	/**
	 * 会員情報編集の完了画面を表示します。
	 */
	public function action_change_complete() {

		$input = Session::get(KEY_FORM);

		// $input の中身を更新できるのは action_check のみなので、
		// 入力チェックは行なわない

		// 更新
		try {
			Companies::update($input);
		}
		catch ( Exception $e ) {
			// 更新時に他社のログインIDがかぶった場合
			if ( $e->getCode() == ERR_DUPLICATE_LOGIN_ID ) {
				Session::put(KEY_ARGS, array(
					'ログインアカウント',
					$e->getMessage(),
					'ID',
				));
				return $this->_to_change_page_with_mc007($input);
			}
			else {
				throw $e;
			}
		}

		// メール送信
		// ※メールアドレスに変更があっても、古いアドレスには送信しない。
		// 　アカウントハックで変更される可能性はあるが、BCCでシステム管理者に送ることでカバーする
//		$to = "{$input['tanto']}様 <{$input['email']}>";
//		$subject = '[○○○○] 会員情報変更のお知らせ';
//		MailService::send_mail($to, $subject, 'mail.change', $input);

		// ビューで使用する変数
		$data = array(
			'input' => $input
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_change_finish', $data);
	}


	public function action_list($condition) {
		// 会社を取得(プルダウン用)
		$companies = Companies::getAll();

		// 検索
		$result = Companies::getWith($condition);

		// 検索結果が１件の場合、即編集画面へ遷移
		if ( count($result) == 1 ) {
			$company_code = $result[0]->company_code;
			return Redirect::to_action('office.member@change', array(
				$company_code
			));
		}

		// ビューで使用する変数
		$data = array(
			'condition' => ($condition == 'all' ? '' : $condition),
			'companies' => $companies,
			'result'    => $result,
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/office/member.js', 'common');

		return View::make('office.member_change_search_result', $data);
	}
}
