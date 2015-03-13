<?php

use Laravel\Log;
use Laravel\Asset;
use Laravel\Response;
use Laravel\Input;
use Laravel\Session;
use Masters\Prefs;
use Masters\Companies;
use Masters\Users;
use Logics\MemberHelper;

/**
 * [指定施工会社]会員登録情報変更画面関連コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 65 $
 * $Date: 2013-09-25 12:20:49 +0900 (2013/09/25 (水)) $
 */
class Construction_Member_Controller extends Construction_Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 会員登録情報変更画面を表示します。
	 */
	public function action_index() {
		$ss = Session::get(KEY_USER);

		$company_code = $ss->company_code;
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
		$input['company_type']  = $company->company_type;
		$areas = array();
		foreach ( $company->areas as $area ) {
			$areas[] = $area->pref_code;
		}
		$input['areas'] = $areas;

		MemberHelper::setValuesByScript($input,'regF',false);

		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'prefs'        => $prefs,
			'company_code' => $input['company_code'],
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/member.js', 'common');

		return View::make('construction.member_change', $data);
	}


	/**
	 * 登録内容の入力チェックを行ないます。
	 */
	public function action_check() {

		$input = Input::all();

		$company_code = $input['company_code'];

		// 加入日
		if ( !checkdate($input['join_dateM'], $input['join_dateD'], $input['join_dateY']) ) {
			return Response::json(array(
				'status' => 'NG',
				'code'   => 'CC005',
				'args'   => array(
					'加入日',
				)
			));
		}

		// 郵便番号1
		if ( $input['zip1'] ) {
			if ( !is_numeric($input['zip1']) || $input['zip1'] < 0 || 999 < $input['zip1'] ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'CC004',
					'args'   => array(
						'郵便番号1',
						3,
					)
				));
			}
		}

		// 郵便番号2
		if ( $input['zip2'] ) {
			if ( !is_numeric($input['zip2']) || $input['zip2'] < 0 || 9999 < $input['zip2'] ) {
				return Response::json(array(
					'status' => 'NG',
					'code'   => 'CC004',
					'args'   => array(
						'郵便番号2',
						4,
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
	 * 確認画面を表示します。
	 *
	 */
	public function action_confirm() {

		$input = Session::get(KEY_FORM);
		$prefs = Prefs::getAll();

		// ビューで使用する変数
		$data = array(
			'input' => $input,
			'prefs' => $prefs
		);

		// ページ固有JS
		Asset::add('page', 'js/pages/construction/member.js', 'common');

		return View::make("construction.member_change_check", $data);
	}

	/**
	 * 更新します。
	 *
	 */
	public function action_finish() {
		$input = Session::get(KEY_FORM);

		$input['member_pswd'] = $input['password'];

		// $input の中身を更新できるのは action_check のみなので、
		// 入力チェックは行なわない
		unset($input['password']);
		unset($input['password2']);


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
		Asset::add('page', 'js/pages/construction/member.js', 'common');

		return View::make('construction.member_change_finish', $data);
	}
}
