<?php

namespace Masters;

use Laravel\Database as DB;
use Laravel\Log;

class LicenseFees {
	
	
	/**
	 * 指定された日付の工法使用料を取得します。
	 *
	 * @param string $date 日付文字列(YYYY-mm-dd形式)
	 * @param int $amount  打設本数
	 * @return number 工法使用料
	 */
	public static function get($date, $amount) {
		// カラム名
		$column = 'licensefees_price';
		if ( $amount < 11 ) {
			$column .= '1';
		}
		else if ( 50 < $amount ) {
			$column .= '3';
		}
		else {
			$column .= '2';
		}
		
		// ※時刻部分は任意で良い
		$ret = DB::table('m_licensefees')
			->where('start_date', '<=', "{$date} 00:00:00")
			->where('end_date'  , '>=', "{$date} 23:59:59")
			->where_del_flg(0)
			->first($column);
		
		return $ret->$column;
	}
}