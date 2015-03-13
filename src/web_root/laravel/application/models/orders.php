<?php

use Masters\Ctax;
use Masters\Parts;
use Laravel\Log;
/**
 * パーツ受注情報を取り扱うモデルクラスです。
 *
 * @author mizoguchi
 */
class Orders {

	/**
	 * 受注ステータスの一覧を取得します。
	 *
	 * @return array 受注ステータスの配列('ステータス名'=>'ステータス値' となっていることに注意)
	 */
	public static function getAllStatus() {
		return array(
			'未出荷' => ORDER_RECEIPT,
			'出荷済' => ORDER_SHIPPED,
			'キャンセル'  => ORDER_CANCELED,
		);
	}


	/**
	 * 受注ステータスの一覧（詳細名版）を取得します。
	 *
	 * @return array 受注ステータスの配列('ステータス名'=>'ステータス値' となっていることに注意)
	 */
	public static function getAllStatusLong() {
		return array(
			'未出荷（出荷手配中）' => ORDER_RECEIPT,
			'出荷済' => ORDER_SHIPPED,
			'キャンセル'  => ORDER_CANCELED,
		);
	}


	/**
	 * パーツ受注情報を作成します。
	 *
	 * @param array   $input        パーツ発注画面の入力情報
	 * @param int     $company      発注会社
	 * @return number
	 */
	public static function create($input, $company) {
		
		// パーツ代金合計
		$subtotal = 0;
		foreach ( $input['orders'] as $order ) {
			$item = Parts::get($order['item_id'], $company->company_type);
			$subtotal += $item->item_sprice * $order['quantity'];
		}
		
		// パーツ受注情報作成
		$order = array();
		$order['order_date']       = REQUESTED_DATE;
		$order['order_company']    = $company->company_code;
		$order['shipping_company'] = $input['shipping_company'];
		$order['shipping_name']    = $input['shipping_name'];
		$order['shipping_address'] = $input['shipping_address'];
		$order['shipping_person']  = $input['shipping_person'];
		$order['email']            = $input['email'];
		$order['tel']              = $input['tel'];
		$order['fax']              = $input['fax'];
		$order['arrival_date']     = $input['arrival_date'];
		$order['rate']             = Ctax::get();
		$order['subtotal']         = $subtotal;
		
		// 登録
		$order_no = 0;
		// トランザクション境界
		DB::connection()->transaction(function () use ($input, $order, $company, &$order_no) {
			$order_no = DB::table('d_order')->insert_get_id($order);
			// 明細作成
			foreach ( $input['orders'] as $o ) {
				$meisai = Parts::createMeisai($o['item_id'], $o['item_type'], $o['quantity'], $company->company_type);
				$meisai['order_no'] = $order_no;
				DB::table('d_order_meisai')->insert($meisai);
			}
		});
		
		return $order_no;
	}
	
	
	/**
	 * パーツ受注検索を行ないます。
	 * ※検索フォームに依存するので画面との結合度が高くなりますが、
	 *   とりあえず目をつぶります。
	 *
	 * @param array $input 検索フォームの入力値
	 */
	public static function search($input) {
		$table = DB::table('d_order');
		
		// 受注番号
		if ( strlen($input['order_no']) ) {
			Log::sql("where: order_no = {$input['order_no']}");
			$table->where_order_no($input['order_no']);
		}
		
		// 会社名
		if ( $input['company_code'] ) {
			Log::sql("where: company_code = '{$input['company_code']}'");
			$table->where_order_company($input['company_code']);
		}
		
		// 注文日
		if (
			$input['s_yyyy']
		 || $input['s_mm']
		 || $input['s_dd']
		 || $input['e_yyyy']
		 || $input['e_mm']
		 || $input['e_dd']
		) {
			$startY = pad4r($input['s_yyyy']);
			$startM = $input['s_mm'] ? pad2($input['s_mm']) : '01';
			$startD = $input['s_dd'] ? pad2($input['s_dd']) : '01';
			$endY = pad4r_9($input['e_yyyy']);
			$endM = $input['e_mm'] ? pad2($input['e_mm']) : '12';
			$endD = $input['e_dd'] ? pad2($input['e_dd']) : date('t', mktime(0, 0, 0, intval($endM), 1, intval($endY))); // 月の最終日
			Log::sql("where: order_date between {$startY}-{$startM}-{$startD} 00:00:00 and {$endY}-{$endM}-{$endD} 23:59:59");
			$table->where_between('order_date', "{$startY}-{$startM}-{$startD} 00:00:00", "{$endY}-{$endM}-{$endD} 23:59:59");
		}
		
		
		// ステータス
		if ( isset($input['status']) && $input['status'] ) {
			if ( is_array($input['status']) ) {
				Log::sql("where: order_status IN ('" . implode("', '", $input['status']) . "')");
				$table->where_in('order_status', $input['status']);
			}
			else {
				Log::sql("where: order_status = {$input['status']}");
				$table->where_order_status($input['status']);
			}
		}
		
		$orders = $table->get();
		foreach ( $orders as &$order ) {
			$order->meisais = DB::table('d_order_meisai')
									->where_order_no($order->order_no)
									->order_by('order_meisai_no')
									->get();
		}
		
		return $orders;
	}
	
	
	/**
	 * パーツ受注情報を取得します。
	 *
	 * @param int $order_no 受注番号
	 */
	public static function get($order_no) {
		$order = DB::table('d_order')
			->where_order_no($order_no)
			->first();
		
		$order->meisai = DB::table('d_order_meisai')
							->where_order_no($order_no)
							->order_by('order_meisai_no')
							->get();
		
		// 請求書の紐付け
		if ( $order->bill_no ) {
			$order->bill = DB::table('d_bill')
							->where_bill_no($order->bill_no)
							->first();
		}
		else {
			// 請求書がまだ起票されていない場合は、
			// 画面表示に使うフィールドのみ補完する
			$order->bill = (object) array(
				'bill_no'      => '',
				'payment_date' => '',
			);
		}
		
		return $order;
	}
	
	
	/**
	 * パーツ受注情報の更新を行ないます。
	 */
	public static function update($input) {
		
		// 表示のみの項目や不変項目(注文時の単価・消費税等)を除外
		unset($input['bill_no']);
		unset($input['payment_date']);
		unset($input['cancel_date']);
		unset($input['rate']);
		
		$meisais = $input['meisai'];
		unset($input['meisai']);
		
		foreach ( $meisais as &$m ) {
			unset($m['price']);
			unset($m['sprice']);
			unset($m);
		}
		
		
		// トランザクション境界
		DB::connection()->transaction(function () use ($input, $meisais) {
				
				// NULL許可カラムが未入力の場合は、NULLを設定
				$columns = array(
					'shipping_agent',
					'shipping_fee',
					'shipping_date',
					'delivery_date',
					'agent_tel',
					'agent_inqno',
				);
				foreach ( $columns as $column ) {
					if ( !isset($input[$column]) || !$input[$column] ) {
						$input[$column] = NULL;
					}
				}
				
				// 注文時消費税・単価等、不変項目があり、
				// かつ再計算も必要なので、DBから当該レコードを読み込む
				$order_no = $input['order_no'];
				unset($input['order_no']);
				$order = Orders::get($order_no);
				
				// 明細から処理
				// ※現時点での仕様では、明細行の増減は無い
				$subtotal = 0; // パーツ代金(の合計)
				$i = 0;
				foreach ( $order->meisai as $meisai ) {
					DB::table('d_order_meisai')
						->where_order_meisai_no($meisai->order_meisai_no)
						->update($meisais[$i]);
					
					// パーツ代金(合計)の集計
					$subtotal += $meisai->item_sprice * $meisai->quantity;
					
					$i++;
				}
				
				$input['subtotal'] = $subtotal;
				
				// 更新日時
				$input['update_date'] = REQUESTED_DATE;
				
				// ステータスがキャンセルになった場合
				if ( $order->order_status != ORDER_CANCELED && $input['order_status'] == ORDER_CANCELED ) {
					$input['cancel_date'] = REQUESTED_DATE;
				}
				// ステータスがキャンセルからそれ以外になった場合
				else if ( $order->order_status == ORDER_CANCELED && $input['order_status'] != ORDER_CANCELED ) {
					$input['cancel_date'] = NULL;
				}
				
				// パーツ受注情報を処理
				DB::table('d_order')
					->where_order_no($order_no)
					->update($input);
				
			});
	}
}
