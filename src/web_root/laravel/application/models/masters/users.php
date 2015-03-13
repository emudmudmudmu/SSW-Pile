<?php

namespace Masters;
use Laravel\Cache;
use Laravel\Database as DB;
use Laravel\Log;
use Laravel\Hash;

class Users {

	/**
	 * ログインID・パスワードによるログインを行ない、
	 * 問題なければログインユーザーマスタの内容を返します。
	 *
	 * @param string $login_id ログインID
	 * @param string $passwd   パスワード
	 * @return \stdClass ログインユーザーマスタの当該レコード, 存在しない場合は FALSE
	 */
	public static function login($login_id, $passwd) {
		// レコード取得
		$user = DB::table('m_user')->where_login_id($login_id)
			->first();

		if ( $user ) {
			// パスワードの照合
			if ( $passwd == $user->passwd ) {

				// 最終ログイン日時の更新
				DB::table('m_user')->where_user_id($user->user_id)
					->update(array(
						'last_login_date' => REQUESTED_DATE
					));
				
				// 会社情報の取得
				if ( $user->auth_type != AUTH_SYSADMIN ) {
					$user->company = Companies::get($user->company_code);
				}
				else {
					$company                    = array();
					$company['company_name']    = 'システム管理';
					$company['company_type']    = COMPANY_MANAGER;
					$company['company_code']    = 0;
					$company['ceo']             = '';
					$company['zip1']            = '';
					$company['zip2']            = '';
					$company['address']         = '';
					$company['tel']             = '';
					$company['fax']             = '';
					$company['tanto']           = 'システム管理者';
					$company['email']           = 'info@web-developers.jp';
					$company['join_date']       = '2013-07-01';
					$company['create_date']     = '0000-00-00 00:00:00';
					$company['update_date']     = '0000-00-00 00:00:00';
					$company['delete_date']     = '0000-00-00 00:00:00';
					$company['del_flg']         = 0;
					$user->company = (object) $company;
				}

				return $user;
			}
		}

		return FALSE;
	}

	/**
	 * ログインIDの存在チェックを行ないます。
	 *
	 * @param int $login_id ログインID
	 * @return boolean 指定されたログインIDが既に存在していればTRUE
	 */
	public static function exist($login_id) {
		// 件数確認
		$count = DB::table('m_user')->where_login_id($login_id)
			->where_del_flg(0)
			->count();
		return (0 < $count);
	}
	
	
	/**
	 * 指定した会社で有効なログインアカウントを取得します。
	 *
	 * @param int $company_code 会社コード
	 * @param int $auth_type    権限タイプ
	 */
	public static function get($company_code, $auth_type) {
		return DB::table('m_user')->where_company_code($company_code)
			->where_auth_type($auth_type)
			->where_del_flg(0)
			->first();
	}
	
	
	/**
	 * ユーザーに削除フラグを立てます。
	 *
	 * @param int $company_code 会社コード
	 * @param int $auth_type    権限
	 */
	public static function delete($company_code, $auth_type) {
		DB::table('m_user')
			->where_company_code($company_code)
			->where_auth_type($auth_type)
			->update(array(
				'del_flg' => 1,
				'delete_date' => REQUESTED_DATE,
			)
		);
	}
	
}
