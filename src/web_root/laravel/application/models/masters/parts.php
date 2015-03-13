<?php
namespace Masters;
use Laravel\Database as DB;
use Laravel\Log;

class Parts {

	/**
	 * パーツ仕様の一覧を取得します。
	 *
	 * @return array パーツ仕様の配列('仕様名'=>'仕様値' となっていることに注意)
	 */
	public static function getAllWelding() {
		return array(
			'正回転仕様' => WELDING_NORMAL,
			'逆回転仕様' => WELDING_REVERSE,
			'接合なし'  => WELDING_NO,
		);
	}


	/**
	 * 適用期間内のパーツを全て取得します。
	 *
	 * @param int $company_type 会社種類(指定がある場合は、
	 *                          会社種類に対応した発注単価・販売単価を
	 *                          item_price, item_spriceフィールドに設定する。
	 *                          指定が無い場合は全カラムをそのまま取得する。
	 *                          )
	 */
	public static function getAll($company_type = -1) {
		if ( $company_type == -1 ) {
			return DB::table('m_item')->where('start_date', '<=', REQUESTED_DATE)
				->where('end_date', '>=', REQUESTED_DATE)
				->where_del_flg(0)
				->order_by('item_size', 'asc')
				->get();
		}
		else {
			$postfix = ($company_type == COMPANY_MEMBER ? '2' : '1');
			$columns = array(
				'item_id',
				'item_size',
				'start_date',
				'end_date',
				"item_price{$postfix} as item_price",
				"item_sprice{$postfix} as item_sprice",
				'create_date',
				'update_date',
				'delete_date',
				'del_flg'
			);

			return DB::table('m_item')->where('start_date', '<=', REQUESTED_DATE)
				->where('end_date', '>=', REQUESTED_DATE)
				->where_del_flg(0)
				->order_by('item_size', 'asc')
				->get($columns);
		}
	}


	/**
	 * 適用期間内のパーツを取得します。
	 *
	 * @param int $item_id パーツ単価ID
	 * @param int $company_type 会社種類(指定がある場合は、
	 *                          会社種類に対応した発注単価・販売単価を
	 *                          item_price, item_spriceフィールドに設定する。
	 *                          指定が無い場合は全カラムをそのまま取得する。
	 *                          )
	 */
	public static function get($item_id, $company_type = -1) {
		if ( $company_type == -1 ) {
			return DB::table('m_item')->where('start_date', '<=', REQUESTED_DATE)
			->where_item_id($item_id)
			->where('end_date', '>=', REQUESTED_DATE)
			->where_del_flg(0)
			->order_by('item_size', 'asc')
			->first();
		}
		else {
			$postfix = ($company_type == COMPANY_MEMBER ? '2' : '1');
			$columns = array(
				'item_id',
				'item_size',
				'start_date',
				'end_date',
				"item_price{$postfix} as item_price",
				"item_sprice{$postfix} as item_sprice",
				'create_date',
				'update_date',
				'delete_date',
				'del_flg'
					);
	
			return DB::table('m_item')->where('start_date', '<=', REQUESTED_DATE)
			->where_item_id($item_id)
			->where('end_date', '>=', REQUESTED_DATE)
			->where_del_flg(0)
			->order_by('item_size', 'asc')
			->first($columns);
		}
	}
	
	
	/**
	 * 明細登録用の配列を生成します。
	 *
	 * @param int     $item_id      パーツ単価ID
	 * @param int     $item_type    仕様
	 * @param int     $quantity     数量
	 * @param int     $company_type 会社種類
	 * @return array  明細(sswd_order_meisai)に該当する連想配列
	 */
	public static function createMeisai($item_id, $item_type, $quantity, $company_type) {
		// パーツ単価の取得
		$item = self::get($item_id, $company_type);
		
		$meisai = array();
		$meisai['item_size']   = $item->item_size;
		$meisai['item_type']   = $item_type;
		$meisai['item_price']  = $item->item_price;
		$meisai['item_sprice'] = $item->item_sprice;
		$meisai['quantity']    = $quantity;
		
		return $meisai;
	}
}
