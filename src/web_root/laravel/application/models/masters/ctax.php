<?php
namespace Masters;
use Laravel\Database as DB;
use Laravel\Log;

class Ctax {

	/**
	 * 現在の消費税率を取得します。
	 *
	 * @return number 5%の場合は「5」
	 */
	public static function get() {
		return DB::table('m_ctax')
			->where('start_date', '<=', REQUESTED_DATE)
			->where('end_date'  , '>=', REQUESTED_DATE)
			->only('rate');
	}

	/**
	 * 指定された日付の消費税率を取得します。
	 *
	 * @param string $date 'YYYY-mm-dd'形式の文字列
	 * @param bool   $first_date TRUEの場合は、$date の月初日で検索します。
	 * @return number 5%の場合は「5」
	 */
	public static function getBy($date, $first_date = TRUE) {
		
		$date = parseDate("{$date} 00:00:00", '-');
		if ( $first_date ) {
			$date = substr($date, 0, 8) . '01';
		}
		
		return DB::table('m_ctax')
			->where('start_date', '<=', "{$date} 00:00:00")
			->where('end_date'  , '>=', "{$date} 23:59:59")
			->only('rate');
	}

}
