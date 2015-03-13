<?php

use Masters\Ctax;
use Masters\Parts;
use Masters\Companies;
use Masters\LicenseFees;
use Logics\BillHelper;
use Laravel\Log;
/**
 * 請求情報を取り扱うモデルクラスです。
 *
 * @author mizoguchi
 */
class Bills {

	/**
	 * 品目一覧を取得します。
	 *
	 * @return array 品目の配列('品目名'=>'品目値' となっていることに注意)
	 */
	public static function getAllHinmoku() {
		return array(
			'工法使用料' => HINMOKU_LICENSE,
			'パーツ代金' => HINMOKU_ITEM,
			'運賃'       => HINMOKU_SHIPPING,
		);
	}


	/**
	 * 請求・及び請求対象検索を行ないます。
	 * ※検索フォームに依存するので画面との結合度が高くなりますが、
	 *   とりあえず目をつぶります。
	 *
	 * @param array $input 検索フォームの入力値
	 */
	public static function search($input) {
		$db_bill  = DB::table('d_bill');

		// 請求書No.
		if ( $input['no'] ) {
			// 請求書番号が指定された時は、検索対象が請求テーブルのみ
			Log::sql("where: bill_no = {$input['no']}");
			$db_bill->where_bill_no($input['no']);
			// 年月・請求先での存在チェックを行なう
			if ( $input['yyyy'] ) {
				$db_bill->where_bill_nen($input['yyyy']);
			}
			if ( $input['mm'] ) {
				$db_bill->where_bill_tuki($input['mm']);
			}
			if ( $input['company_code'] ) {
				$db_bill->where_bill_company($input['company_code']);
			}

			// 存在チェック＆取得
			if ( 0 < $db_bill->count() ) {
				// 検索結果は配列なので、１件でも配列にして返す
				$bill = self::get($input['no']);

				// 会社名を設定
				$company = Companies::get($bill->bill_company);
				$bill->company_name = $company->company_name;

				// 結果タイプを設定
				$bill->result_type = RESULT_TYPE_BILL;

				return array( $bill );
			}
			else {
				// 検索結果無し
				return array();
			}
		}

		// 請求書No.に指定が無い場合は、請求書番号に入力の無い、物件・パーツ受注も検索対象
		$db_const = DB::table('d_construction')->where_null('bill_no');
		$db_order = DB::table('d_order')->where_null('bill_no');
		// 物件はステータスが「(工事)完了」のもの
		$db_const->where_status(CONSTRUCT_COMPLETE)
				 ->where_not_null('complete_date');
		// パーツ受注は、受注ステータスが「出荷済」のもの
		$db_order->where_order_status(ORDER_SHIPPED)
		         ->where_not_null('shipping_date');

		// 請求先（会社）
		if ( $input['company_code'] ) {
			Log::sql("where: company_code = '{$input['company_code']}'");
			$db_bill->where_bill_company($input['company_code']);   // 請求先
			$db_const->where_order_company($input['company_code']); // 発注元
			$db_order->where_order_company($input['company_code']); // 発注元
		}

		// 請求年月
		if ( $input['yyyy'] ) {
			$db_bill->where_bill_nen($input['yyyy']);

			$min = "{$input['yyyy']}-01-01";
			$max = "{$input['yyyy']}-12-31";
			$db_const->where_between('complete_date', $min, $max);
			$db_order->where_between('order_date', "{$min} 00:00:00", "{$max} 23:59:59");
		}
		if ( $input['mm'] ) {
			// $input['mm']に値がある時は、必ず$input['yyyy']に値がある
			$db_bill->where_bill_tuki($input['mm']);

			// 月の最終日
			$last_day = date('t', mktime(0, 0, 0, intval($input['mm']), 1, intval($input['yyyy'])));
			$min = "{$input['yyyy']}-" . pad2($input['mm']) . '-01';
			$max = "{$input['yyyy']}-" . pad2($input['mm']) . '-' . pad2($last_day);
			$db_const->where_between('complete_date', $min, $max);
			$db_order->where_between('order_date', "{$min} 00:00:00", "{$max} 23:59:59");
		}


		// 請求情報検索
		$result = $db_bill->get();
		foreach ( $result as &$bill ) {
			// 会社名を設定
			$company = Companies::get($bill->bill_company);
			$bill->company_name = $company->company_name;

			// 結果タイプを設定
			$bill->result_type = RESULT_TYPE_BILL;

			// 主キー
			$bill->key = $bill->bill_no;

			unset($bill);
		}
		if ( !$result ) {
			$result = array();
		}

		// 物件情報検索
		$constructions = $db_const->get();
		foreach ( $constructions as $const ) {

			// 完工日・打設本数・発注企業に指定が無ければ請求対象では無い
			if ( !$const->complete_date || !$const->amount || !$const->order_company ) {
				continue;
			}


			// 請求先
			$company = Companies::get($const->order_company);

			/* 請求金額の仮算出 */
			// 工法使用料
			$license_fee = LicenseFees::get($const->complete_date, $const->amount);

			// 工法使用料が発生しない場合はスキップ
			if ( !$license_fee ) {
				continue;
			}

			// 消費税
			$rate = Ctax::getBy($const->complete_date);
			$tax  = intval(floor($license_fee * $rate / 100));

			// 請求日取得
			$bill_date = '';
			if ( $const->bill_no ) {
				$const->bill = self::get($const->bill_no);
				$bill_date = $const->bill->bill_date;
			}

			// 整形
			$bill = array(
				'key'          => $const->construction_no,
				'company_name' => $company->company_name,
				'total'        => $license_fee + $tax,
				'tax'          => $tax,
				'result_type'  => RESULT_TYPE_CONST,
				'bill_no'      => $const->bill_no,
				'bill_date'    => $bill_date,
			);

			// 結果に追加
			$result[] = (object) $bill;
		}

		// パーツ受注情報検索
		$orders = $db_order->get();
		foreach ( $orders as $order ) {
			// 請求先
			$company = Companies::get($order->order_company);

			/* 請求金額の仮算出 */
			$total = $order->subtotal + $order->shipping_fee;
			$tax   = intval(floor($total * $order->rate / 100));

			// 請求日取得
			$bill_date = '';
			if ( $order->bill_no ) {
				$order->bill = self::get($order->bill_no);
				$bill_date = $order->bill->bill_date;
			}

			// 整形
			$bill = array(
				'key'          => $order->order_no,
				'company_name' => $company->company_name,
				'total'        => $total + $tax,
				'tax'          => $tax,
				'result_type'  => RESULT_TYPE_ORDER,
				'bill_no'      => $order->bill_no,
				'bill_date'    => $bill_date,
			);

			// 結果に追加
			$result[] = (object) $bill;
		}

		return $result;
	}


	/**
	 * 請求情報を取得します。
	 */
	public static function get($bill_no) {
		$bill = DB::table('d_bill')
				->where_bill_no($bill_no)
				->first();

		// ステータス
		$bill->status_txt = "請求済";
		if ( $bill->payment_date ) {
			$bill->status_txt = "請求済";
		}

		$bill->meisai = DB::table('d_bill_meisai')
							->where_bill_no($bill_no)
							->order_by('bill_meisai_no')
							->get();
		$order_no = 0;
		foreach ( $bill->meisai as &$meisai ) {
			$order_no = $meisai->order_no;
			$meisai->meisai_date_obj = date_create_from_format('Y-m-d', $meisai->meisai_date);
			unset($meisai);
		}

		if ( $order_no ) {
			$bill->order = Orders::get($order_no);
		}

		return $bill;
	}


	/**
	 * 請求情報を取得します。
	 */
	public static function getBy($company_code) {
		$bills = DB::table('d_bill')
				->where_bill_company($company_code)
				->order_by('bill_nen', 'desc')
				->order_by('bill_tuki', 'desc')
				->get('bill_no');

		$ret = array();
		foreach ( $bills as $bill ) {
			$ret[] = self::get($bill->bill_no);
		}
		return $ret;
	}


	/**
	 * 物件情報から請求情報を作成します。
	 */
	public static function createFromConstruction($construction_no) {
		
		list($const, $bill, $meisai) = self::_construction_to_bill($construction_no);
		
		$bill_no = "";
		// トランザクション境界
		DB::connection()->transaction(function () use ($const, $bill, $meisai, &$bill_no) {

			// 請求
			$bill_no = DB::table('d_bill')->insert_get_id($bill);

			// 請求明細
			$meisai['bill_no'] = $bill_no;
			DB::table('d_bill_meisai')->insert($meisai);

			// 物件の更新
			DB::table('d_construction')
				->where_construction_no($const->construction_no)
				->update(array (
					'bill_no'     => $bill_no,
					'update_date' => REQUESTED_DATE,
				));


		});

		return $bill_no;
	}


	private static function _construction_to_bill($construction_no) {
		$const = Constructions::get($construction_no, 1);

		// 完工日＝請求月
		$date = date_create_from_format('Y-m-d', $const->complete_date);

		// 請求金額
		$total = LicenseFees::get($const->complete_date, $const->amount);
		$rate  = Ctax::getBy($const->complete_date);
		$tax   = intval(floor( $total * $rate / 100 ));

		$bill = array(
			'bill_nen'      => date_format($date, 'Y'),
			'bill_tuki'     => date_format($date, 'm'),
			'bill_company'  => $const->order_company,
			'total'         => $total + $tax,
			'tax'           => $tax,
			'rate'          => $rate,
			'bill_date'     => parseDate(REQUESTED_DATE, '-'),
		);

		$meisai = array(
			'meisai_date'   => $const->complete_date,
			'bill_type'     => HINMOKU_LICENSE,
			'bill_name'     => LABEL_LICENSEFEE,
			'price'         => $total,
			'quantity'      => $const->amount,
			'sub_total'     => $total,
		);
		
		return array($const, $bill, $meisai);
	}
	
	
	
	/**
	 * 受注情報から請求情報を作成します。
	 */
	public static function createFromOrder($order_no) {
		
		list($order, $bill, $meisais) = self::_order_to_bill($order_no);
	
		$bill_no = "";
		// トランザクション境界
		DB::connection()->transaction(function () use ($order, $bill, $meisais, &$bill_no) {
	
			// 請求
			$bill_no = DB::table('d_bill')->insert_get_id($bill);
	
			// 請求明細
			foreach ( $meisais as $meisai ) {
				$meisai['bill_no'] = $bill_no;
				DB::table('d_bill_meisai')->insert($meisai);
			}
	
			// 受注の更新
			DB::table('d_order')
				->where_order_no($order->order_no)
				->update(array (
					'bill_no'     => $bill_no,
					'update_date' => REQUESTED_DATE,
				));
	
	
		});
	
		return $bill_no;
	}
	
	
	private static function _order_to_bill($order_no) {
		$order = Orders::get($order_no);
	
		// 注文日＝請求月
		$date = date_create_from_format('Y-m-d H:i:s', $order->order_date);
	
		// 請求金額
		$tax   = intval(floor( ( $order->subtotal + $order->shipping_fee ) * $order->rate / 100 ));
	
		$bill = array(
			'bill_nen'      => date_format($date, 'Y'),
			'bill_tuki'     => date_format($date, 'm'),
			'bill_company'  => $order->order_company,
			'total'         => $order->subtotal + $order->shipping_fee + $tax,
			'tax'           => $tax,
			'rate'          => $order->rate,
			'bill_date'     => parseDate(REQUESTED_DATE, '-'),
		);
	
		$meisais = array();
		foreach ( $order->meisai as $meisai ) {
			$line = array (
				'meisai_date'   => $order->order_date,
				'bill_type'     => HINMOKU_ITEM,
				'bill_name'     => LABEL_PARTS . " {$meisai->item_size} " . getLabelBy('weldings', $meisai->item_type),
				'price'         => $meisai->item_sprice,
				'quantity'      => $meisai->quantity,
				'sub_total'     => $meisai->item_sprice * $meisai->quantity,
				'order_no'      => $order->order_no,
			);
	
			$meisais[] = $line;
		}
	
		// 運賃
		$line = array(
			'meisai_date'  => $order->order_date,
			'bill_type'    => HINMOKU_SHIPPING,
			'bill_name'    => LABEL_SHIPPING,
			'price'        => $order->shipping_fee,
			'sub_total'    => $order->shipping_fee,
			'order_no'     => $order->order_no,
		);
		$meisais[] = $line;
		
		return array($order, $bill, $meisais);
	}
	
	
	/**
	 * 受注情報・物件情報から一括請求情報を作成します。
	 * 
	 * @param string $target "結果タイプ:主キー"がカンマ区切りで連結された文字列
	 *                 (例) 1:3,2:W100131001,3:2 … 請求情報のbill_no=3,物件情報の認定番号=W100131001,受注情報のorder_no=2)
	 */
	public static function createFrom($target) {
		// 請求情報
		$bill = array(
			'bill_nen'      => '',
			'bill_tuki'     => '',
			'bill_company'  => '',
			'total'         => '',
			'tax'           => '',
			'rate'          => '',
			'bill_date'     => '',
		);
		
		// 明細行
		$all = array();
		
		// 主キー
		$keys = array();
		
		// 引数から、物件の認定番号・受注の注文番号を抽出
		$elements = explode(',', $target);
		
		// 全件処理しつつ、請求金額の計算と明細行の作成
		foreach ( $elements as $k ) {
			// $k は "結果タイプ":"結果タイプに応じた主キー" になっている
			$expand = explode(':', $k);
			$type   = $expand[0];
			$key    = $expand[1];
			$keys[] = $key;
			
			// 結果タイプで請求情報生成処理を分岐
			$bill_t = '';
			$meisai = '';
			switch ( $type ) {
				// 物件の場合
				case RESULT_TYPE_CONST: {
					list($const, $bill_t, $meisai) = self::_construction_to_bill($key);
					break;
				}
				// 受注の場合
				case RESULT_TYPE_ORDER: {
					list($order, $bill_t, $meisai) = self::_order_to_bill($key);
					break;
				}
			}
			
			$check = self::_check_bills($bill, $bill_t);
			if ( $check ) {
				Log::error("Bills::createFrom('{$target}')");
				Log::error("主キー={$key}");
				throw new Exception("{$check}が統一できません。データの確認が必要です。");
			}
			
			if ( !$bill['bill_nen'] ) {
				$bill = $bill_t;
				// 消費税を除いておく（後述参照）
				$bill['total'] -= $bill['tax'];
			}
			else {
				// 個別請求情報の、$bill_t['total']は請求金額であるため、
				// 消費税額を含んでいる。
				// 一括請求の消費税額は別途計算し直すので、
				// ここでは消費税額を除いて合計する
				$bill['total'] += ($bill_t['total'] - $bill_t['tax']);
			}
			// 明細
			if ( isset($meisai[0]) && is_array($meisai[0]) ) {
				$all = array_merge($all, $meisai);
			}
			else {
				$all[] = $meisai;
			}
			
			Log::debug(str_replace("\\n", "", var_export($bill, TRUE)));
		}
		
		// 最後に消費税の計算
		Log::debug("total-tax={$bill['total']}");
		$bill['tax'] = intval(floor( $bill['total'] * $bill['rate'] / 100 ));
		$bill['total'] += $bill['tax'];
		
		$bill_no = "";
		// トランザクション境界
		DB::connection()->transaction(function () use ($keys, $bill, $all, &$bill_no) {

			// 請求
			$bill_no = DB::table('d_bill')->insert_get_id($bill);

			// 請求明細
			foreach ( $all as $meisai ) {
				$meisai['bill_no'] = $bill_no;
				DB::table('d_bill_meisai')->insert($meisai);
			}
			
			// 物件・受注の更新
			foreach ( $keys as $key ) {
				if ( preg_match('#^W[0-9]{9}#', $key) ) {
					DB::table('d_construction')
						->where_construction_no($key)
						->update(array (
							'bill_no'     => $bill_no,
							'update_date' => REQUESTED_DATE,
						));
				}
				else {
					DB::table('d_order')
						->where_order_no($key)
						->update(array (
							'bill_no'     => $bill_no,
							'update_date' => REQUESTED_DATE,
						));
				}
			}
			
		});

		return $bill_no;
	}
	
	
	/**
	 * 2つの請求情報を比較して、請求年月・請求先・消費税が一致するかどうかを検査する。
	 * 第1引数の請求情報の各項目が初期値の場合は検査を行なわない。
	 * 
	 * @return string 一致しなかった項目名(カンマ区切り)
	 */
	private static function _check_bills( $bill, $target_bill ) {
		$ret = array();
		
		if ( $bill['bill_nen'] && $bill['bill_nen'] != $target_bill['bill_nen'] ) {
			$ret[] = '請求年';
		}
		if ( $bill['bill_tuki'] && $bill['bill_tuki'] != $target_bill['bill_tuki'] ) {
			$ret[] = '請求月';
		}
		if ( $bill['bill_company'] && $bill['bill_company'] != $target_bill['bill_company'] ) {
			$ret[] = '請求先';
		}
		if ( $bill['rate'] && $bill['rate'] != $target_bill['rate'] ) {
			$ret[] = '消費税率';
		}
		return implode(',', $ret);
	}
	
	
	/**
	 * 請求情報の更新を行ないます。
	 */
	public static function update($input) {
		DB::table('d_bill')->where_bill_no($input['bill_no'])->update($input);
	}


	/**
	 * 精算用の計算を行ないます。
	 *
	 * @param int $pay_y 精算年月の年
	 * @param int $pay_m 精算年月の月
	 */
	public static function calcClearing($pay_y, $pay_m) {
		// 精算年月で請求情報を検索
		$temp_no = DB::table('d_bill')
					->where_bill_nen($pay_y)
					->where_bill_tuki($pay_m)
					->order_by('bill_no')
					->get('bill_no');
		$bills = array();
		$bill_total = 0;
		foreach ( $temp_no as $no ) {
			$bill = self::get($no->bill_no);
			$bills[] = BillHelper::createBill(get_object_vars($bill));
			// 工法使用料に対する請求は除外なので、
			// あらかじめ差し引いておく
			// ※一括請求の場合も考慮しての実装
			foreach ( $bill->meisai as $meisai ) {
				if ( $meisai->bill_type == HINMOKU_LICENSE ) {
					$bill_total -= intval(floor($meisai->sub_total * (100 + $bill->rate) / 100));
				}
			}
			$bill_total += $bill->total; // $bill->total は税込み金額
		}

		// 請求情報に存在する受注情報を検索
		$orders = array();
		$did = array();
		foreach ( $bills as $bill ) {
			foreach ( $bill['meisai'] as $meisai ) {
				$order_no = $meisai['order_no'];
				if ( $order_no && !in_array($order_no, $did) ) {
					$orders[] = Orders::get($order_no);
					$did[] = $order_no;
				}
			}
		}
		// 表示用に集計
		$order_total = 0;
		$order_data = array();
		foreach ( $orders as $order ) {
			$company = Companies::get($order->order_company);
			$order_date = date_create_from_format('Y-m-d H:i:s', $order->order_date);

			$total = 0;
			foreach ( $order->meisai as $meisai ) {
				$total += $meisai->item_price * $meisai->quantity; // 発注単価で精算
			}
			
			$total += $order->shipping_fee;
			$line = array(
				'order_date'      => date_format($order_date, 'Y/m/d'),
				'order_date_md'   => date_format($order_date, 'n/j'),
				'order_no'        => $order->order_no,
				'company_name'    => $company->company_name,
				'shipping_name'   => $order->shipping_name,
				'total'           => intval(floor($total * (100 + $order->rate) / 100))
			);
			$order_total += $line['total'];

			$order_data[] = $line;
		}

		return array($bills, $order_data, $order_total, $bill_total);
	}
}
