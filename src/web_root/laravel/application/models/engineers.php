<?php

/**
 * 施工管理技術者を取り扱うモデルクラスです。
 *
 * @author mizoguchi
 */
class Engineers {

	/**
	 * 施工管理技術者の一覧を取得します。
	 *
	 * @return array 有効な施工管理技術者(sswm_engineer)の一覧
	 */
	public static function getAll() {
		$engineers = DB::table('m_engineer')->where('company_code', '<>', 0)
			->where_del_flg(0)
			->order_by('no', 'asc')
			->get();

		foreach ( $engineers as &$engineer ) {
			$engineer->company = DB::table('m_company')->where_company_code($engineer->company_code)
				->first();
			unset($engineer);
		}

		return $engineers;
	}

	/**
	 * 施工管理技術者の一覧を取得します。
	 * 施工管理技術者と会社の紐付けは行ないません。
	 *
	 * @param int $company_code 会社コード
	 * @return array 有効な施工管理技術者(sswm_engineer)の一覧
	 */
	public static function get($company_code) {
		$engineers = DB::table('m_engineer')->where('company_code', '<>', 0)
			->where_company_code($company_code)
			->where_del_flg(0)
			->order_by('no', 'asc')
			->get();

		return $engineers;
	}

	/**
	 * 施工管理技術者を取得します。
	 *
	 * @param int $no No.(施工管理技術者のID)
	 * @return stdClass 施工管理技術者(sswm_engineer)のレコード
	 */
	public static function getEngineer($no) {
		return DB::table('m_engineer')->where_no($no)
			->where_del_flg(0)
			->first();
	}
}
