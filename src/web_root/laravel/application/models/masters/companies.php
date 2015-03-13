<?php

namespace Masters;
use Laravel\Cache;
use Laravel\Database as DB;
use Laravel\Log;

class Companies {

	/**
	 * 会社コードの存在チェックを行ないます。
	 *
	 * @param int $company_code 会社コード
	 * @return boolean 指定された会社コードが既に存在していればTRUE
	 */
	public static function exist($company_code) {
		// 件数確認
		$count = DB::table('m_company')->where_company_code($company_code)
			->where_del_flg(0)
			->count();
		return (0 < $count);
	}

	/**
	 * 会社・ログインユーザーの登録を行ないます。
	 *
	 * @param array $input 会員登録画面での入力値
	 */
	public static function create($input) {

		// トランザクション境界
		DB::connection()->transaction(function () use ($input) {

				/* 会社マスタにレコードを作成 */
				// コピー(PHPは値渡しらしい…)
				$company = $input;

				// ログインアカウント関連を削除
				unset($company['member_id']);
				unset($company['member_pswd']);
				unset($company['manager_id']);
				unset($company['manager_pswd']);
				unset($company['shipping_id']);
				unset($company['shipping_pswd']);

				// 加入日を生成
				$company['join_date'] = "{$company['join_dateY']}-{$company['join_dateM']}-{$company['join_dateD']}";
				// 加入日関連を削除
				unset($company['join_dateY']);
				unset($company['join_dateM']);
				unset($company['join_dateD']);

				// 施工エリアを削除
				unset($company['areas']);

				// 登録
				DB::table('m_company')->insert($company);


				/* 施工エリアを登録 */
				foreach ( $input['areas'] as $area ) {
					DB::table('m_area')->insert(array(
							'company_code' => $input['company_code'],
							'pref_code'    => $area,
						));
				}


				/* ログインユーザーを登録 */
				// 指定施工会社用(一般)
				DB::table('m_user')->insert(array(
						'company_code' => $input['company_code'],
						'login_id'     => $input['member_id'],
						'passwd'       => $input['member_pswd'],
						'auth_type'    => AUTH_MEMBER
					));

				if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT ) {

					// 理事会社用
					DB::table('m_user')->insert(array(
							'company_code' => $input['company_code'],
							'login_id'     => $input['manager_id'],
							'passwd'       => $input['manager_pswd'],
							'auth_type'    => AUTH_MANAGER
						));
				}

				if ( $input['company_type'] == COMPANY_MANAGER ) {

					// パーツ出荷担当用
					DB::table('m_user')->insert(array(
							'company_code' => $input['company_code'],
							'login_id'     => $input['shipping_id'],
							'passwd'       => $input['shipping_pswd'],
							'auth_type'    => AUTH_SHIPPING
						));

				}


			}); // トランザクションの終了
	}


	/**
	 * 会社・ログインユーザーの更新を行ないます。
	 *
	 * @param array $input 会員登録画面での入力値
	 */
	public static function update($input) {

		// トランザクション境界
		DB::connection()->transaction(function () use ($input) {
				
				// チェック用に既存の会社を取得
				$current = Companies::get(intval($input['company_code']));
				
				/* 会社マスタを更新 */
				// コピー(PHPは値渡しらしい…)
				$company = $input;

				// ログインアカウント関連を削除
				unset($company['member_id']);
				unset($company['member_pswd']);
				unset($company['manager_id']);
				unset($company['manager_pswd']);
				unset($company['shipping_id']);
				unset($company['shipping_pswd']);

				// 加入日を生成
				$company['join_date'] = "{$company['join_dateY']}-{$company['join_dateM']}-{$company['join_dateD']}";
				// 加入日関連を削除
				unset($company['join_dateY']);
				unset($company['join_dateM']);
				unset($company['join_dateD']);

				// 施工エリアを削除
				unset($company['areas']);

				// 更新日時を設定
				$company['update_date'] = REQUESTED_DATE;

				// 更新
				$company_code = $company['company_code'];
				DB::table('m_company')->where_company_code($company_code)
					->update($company);


				/* 施工エリアを更新(DELETE/INSERT) */
				DB::table('m_area')->where_company_code($company_code)
					->delete();
				foreach ( $input['areas'] as $area ) {
					DB::table('m_area')->insert(array(
							'company_code' => $input['company_code'],
							'pref_code'    => $area,
						));
				}


				/* ログインユーザーを更新 */
				// 会社区分の変更チェック
				if ( $input['company_type'] != $current->company_type ) {
					switch ( $current->company_type ) {
						case COMPANY_MANAGER:
							// 理事会社→共同会社になった場合
							if ( $input['company_type'] == COMPANY_JOINT ) {
								Users::delete($company_code, AUTH_SHIPPING); // 出荷担当を削除
							}
							// 理事会社→一般会社になった場合
							else if ( $input['company_type'] == COMPANY_MEMBER ) {
								Users::delete($company_code, AUTH_MANAGER);  // 管理会社用を削除
								Users::delete($company_code, AUTH_SHIPPING); // 出荷担当を削除
							}
							break;
						case COMPANY_JOINT:
							// 共同会社→一般会社になった場合
							if ( $input['company_type'] == COMPANY_MEMBER ) {
								Users::delete($company_code, AUTH_MANAGER);  // 管理会社用を削除
							}
							break;
					}
				}
				
				
				// 指定施工会社用(一般)
				Companies::_updateUser(AUTH_MEMBER, $input);

				if ( $input['company_type'] == COMPANY_MANAGER || $input['company_type'] == COMPANY_JOINT ) {
					// 理事会社用
					Companies::_updateUser(AUTH_MANAGER, $input);
				}

				if ( $input['company_type'] == COMPANY_MANAGER ) {
					// パーツ出荷担当用
					Companies::_updateUser(AUTH_SHIPPING, $input);
				}


			}); // トランザクションの終了
	}


	public static function _updateUser($auth_type, $input) {
		$company_code = $input['company_code'];
		$prefix = 'member';
		switch ( $auth_type ) {
			case AUTH_MANAGER:
				$prefix = 'manager';
				break;
			case AUTH_SHIPPING:
				$prefix = 'shipping';
				break;
		}

		$login_id = $input["{$prefix}_id"];
		$password = $input["{$prefix}_pswd"];

		// 既存のユーザーを検索(会社コード・権限タイプを指定)
		$user = Users::get($company_code, $auth_type);
		
		Log::debug("既存ユーザー:" . var_export($user, TRUE));
		
		// 既存がある場合は更新
		if ( $user ) {
			// ログインIDに変更がある場合は、存在チェック
			if ( $user->login_id != $login_id ) {
				// 会社コード指定無しで検索
				$count = DB::table('m_user')->where_login_id($login_id)
					->where_del_flg(0)
					->count();
				if ( 0 < $count ) {
					throw new \Exception($login_id, ERR_DUPLICATE_LOGIN_ID);
				}
				// 問題なければ、ログインID・パスワードを更新
				DB::table('m_user')->where_user_id($user->user_id)
					->update(array(
						'login_id'    => $login_id,
						'passwd'      => $password,
						'update_date' => REQUESTED_DATE,
					));
			}
			else {
				// パスワードのみ更新
				DB::table('m_user')->where_user_id($user->user_id)
					->update(array(
						'passwd'      => $password,
						'update_date' => REQUESTED_DATE,
					));
			}
		}
		// 既存が無い場合
		else {
			// ログインIDの重複チェック(会社コード指定無しで検索)
			$count = DB::table('m_user')->where_login_id($login_id)
				->where_del_flg(0)
				->count();
			if ( 0 < $count ) {
				throw new \Exception($login_id, ERR_DUPLICATE_LOGIN_ID);
			}

			// 問題なければ新規登録
			DB::table('m_user')->insert(array(
					'company_code' => $company_code,
					'login_id'     => $login_id,
					'passwd'       => $password,
					'auth_type'    => $auth_type
				));
		}
	}
	
	
	/**
	 * 会社を全件取得します。
	 */
	public static function getAll() {
		return DB::table('m_company')->where('company_code', '<>', 0)
			->where_del_flg(0)
			->order_by('company_code', 'asc')
			->get();
	}


	/**
	 * 指定施工会社の一覧を取得します。
	 */
	public static function getConstructors() {
		return DB::table('m_company')->where('company_code', '<>', 0)
			->where_company_type(COMPANY_MEMBER)
			->where_del_flg(0)
			->order_by('company_code', 'asc')
			->get();
	}


	/**
	 * 会社を取得します。
	 *
	 * @param int $company_code 会社コード
	 */
	public static function get($company_code) {
		$company = DB::table('m_company')->where('company_code', '<>', 0)
			->where_company_code($company_code)
			->where_del_flg(0)
			->first();

		$users = DB::table('m_user')->where_company_code($company_code)
			->where_del_flg(0)
			->get();
		foreach ( $users as $user ) {
			switch ( $user->auth_type ) {
				case AUTH_MANAGER:
					$company->manager = $user;
					break;
				case AUTH_SHIPPING:
					$company->shipping = $user;
					break;
				case AUTH_MEMBER:
					$company->member = $user;
					break;
			}
		}

		$company->areas = DB::table('m_area')->order_by('pref_code', 'asc')
			->where_company_code($company_code)
			->get();

		return $company;
	}

	/**
	 * 会社を取得します。
	 * 会社コードが3桁未満の場合は、前方一致で検索します。
	 * 会社コードが数字では無い場合、全件を取得します。
	 *
	 * @param string $company_code 会社コード
	 */
	public static function getWith($company_code) {

		// 範囲指定で実存会社の会社コードを取得
		$min = str_pad($company_code, 3, '0', STR_PAD_RIGHT);
		$max = str_pad($company_code, 3, '9', STR_PAD_RIGHT);

		if ( !is_numeric($min) ) {
			$min = 1;
			$max = 999;
		}
		else {
			$min = intval($min);
			$max = intval($max);
		}


		$codes = DB::table('m_company')->where('company_code', '<>', 0)
			->where_between('company_code', $min, $max)
			->where_del_flg(0)
			->order_by('company_code', 'asc')
			->get(array(
				'company_code'
			));

		$ret = array();
		foreach ( $codes as $code ) {
			$ret[] = self::get($code->company_code);
		}

		return $ret;
	}
}
