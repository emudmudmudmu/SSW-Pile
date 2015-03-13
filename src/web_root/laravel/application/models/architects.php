<?php

/**
 * 設計担当者を取り扱うモデルクラスです。
 *
 * @author mizoguchi
 */
class Architects {

	/**
	 * 設計担当者の一覧を取得します。
	 *
	 * @return array 有効な設計担当者(sswm_architect)の一覧
	 */
	public static function getAll() {
		$architects = DB::table('m_architect')->where('company_code', '<>', 0)
			->where_del_flg(0)
			->order_by('no', 'asc')
			->get();

		foreach ( $architects as &$architect ) {
			$architect->company = DB::table('m_company')->where_company_code($architect->company_code)
				->first();
			unset($architect);
		}

		return $architects;
	}

	/**
	 * 設計担当者の一覧を取得します。
	 * 設計担当者と会社の紐付けは行ないません。
	 *
	 * @param int $company_code 会社コード
	 * @return array 有効な設計担当者(sswm_architect)の一覧
	 */
	public static function get($company_code) {
		$architects = DB::table('m_architect')->where('company_code', '<>', 0)
			->where_company_code($company_code)
			->where_del_flg(0)
			->order_by('no', 'asc')
			->get();

		return $architects;
	}

	
	/**
	 * 設計担当者を取得します。
	 *
	 * @param int $no No.(設計担当者のID)
	 * @return stdClass 設計担当者(sswm_architect)のレコード
	 */
	public static function getArchtect($no) {
		return DB::table('m_architect')->where_no($no)
			->where_del_flg(0)
			->first();
	}
}
