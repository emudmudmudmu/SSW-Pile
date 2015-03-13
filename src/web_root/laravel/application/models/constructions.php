<?php

use Masters\Companies;
class Constructions {


	/**
	 * 物件ステータスの一覧を取得します。
	 *
	 * @return array 物件ステータスの配列('ステータス名'=>'ステータス値' となっていることに注意)
	 */
	public static function getAllStatus() {
		return array(
			'見込み' => CONSTRUCT_ESTIMATE,
			'受注済' => CONSTRUCT_ORDERED,
			'完了'  => CONSTRUCT_COMPLETE,
		);
	}


	/**
	 * 種別の一覧を取得します。
	 *
	 * @return array 種別の配列('種別名'=>'種別値' となっていることに注意)
	 */
	public static function getAllSyubetu() {
		return array(
			'四号建築物'   => SYBT_4GO,
			'学会小規模指針' => SYBT_GAKKAI,
			'その他'     => SYBT_OTHER,
			'工作物'     => SYBT_KOSAKU,
		);
	}


	/**
	 * 種別2の一覧を取得します。
	 *
	 * @return array 種別2の配列('種別2名'=>'種別2値' となっていることに注意)
	 */
	public static function getAllSyubetu_2() {
		return array(
			'擁壁'  => SYBT2_YOHEKI,
			'広告塔' => SYBT2_KOKOKU,
			'鉄塔'  => SYBT2_TETU,
			'その他' => SYBT2_OTHER,
		);
	}


	/**
	 * 構造の一覧を取得します。
	 *
	 * @return array 構造の配列('構造名'=>'構造値' となっていることに注意)
	 */
	public static function getAllKozo() {
		return array(
			'木造'   => KOZO_MOKU,
			'S造'   => KOZO_S,
			'RC造'  => KOZO_RC,
			'間知石造' => KOZO_KENCHI,
			'CB造'  => KOZO_CB,
		);
	}


	/**
	 * 基礎形式の一覧を取得します。
	 *
	 * @return array 基礎形式の配列('基礎形式名'=>'基礎形式値' となっていることに注意)
	 */
	public static function getAllKiso() {
		return array(
			'独立' => KISO_DOKURITU,
			'べた' => KISO_BETA,
			'布'  => KISO_NUNO,
		);
	}


	/**
	 * 同一年度内での、施工会社内通番の次の値を取得します。
	 * DBに対して更新は行ないませんので、取得する値は仮の値です。
	 *
	 * @param int $company_code 会社コード(施工会社コード)
	 * @param int $nendo  年度（指定が無い場合は、date('y') の値）
	 */
	public static function getNextCompanySeq($company_code, $nendo = -1) {
		if ( $nendo == -1 ) {
			$nendo = date('y');
		}

		$max = DB::table('d_construction')->where_construction_company($company_code)
			->where_nendo($nendo)
			->max('company_seq');
		if ( $max ) {
			return $max + 1;
		}
		return 1;
	}

	/**
	 * 枝番の生成を行ないます。
	 *
	 * @param string $construction_no 認定番号
	 * @return number 枝番(同一認定番号の物件が無い場合は1[開始値])
	 */
	public static function getNextEdaNo($construction_no) {

		$max = DB::table('d_construction')->where_construction_no($construction_no)
			->max('construction_eda');
		if ( $max ) {
			return $max + 1;
		}
		return 1;
	}

	/**
	 * 物件の新規登録を行ないます。
	 *
	 * @param array $input 物件登録の入力値
	 */
	public static function create($input) {
		// トランザクション境界
		DB::connection()->transaction(function () use ($input) {
				// 外部キー制約チェックを停止
				DB::query('SET FOREIGN_KEY_CHECKS = 0');

				// NULL許可カラムが未入力の場合は、登録値から除外
				$columns = array(
					'architect_company',
					'architect',
					'engineer',
					'order_company',
					'construction_name',
					'construction_address',
					'construction_start_date',
					'complete_date',
					'report_date',
					'amount',
					'material_id',
					'sybt',
					'sybt2',
					'kouzou',
					'yoto',
					'kiso',
					'floor',
					'height',
					'nokidake',
					'totalarea',
					'depth'
				);
				foreach ( $columns as $column ) {
					if ( !$input[$column] ) {
						unset($input[$column]);
					}
				}

				// 施工会社
				$company_code = pad3($input['construction_company']);
				// 識別年度
				$nendo = pad2($input['nendo']);
				// 年度内会社通番
				$company_seq = pad3(Constructions::getNextCompanySeq($company_code, $nendo));
				// 認定番号の生成
				$construction_no = "W{$company_code}{$nendo}1{$company_seq}";
				$input['construction_no'] = $construction_no;

				// 会社通番の再設定
				$input['company_seq'] = intval($company_seq);

				// 枝番の取得
				$input['construction_eda'] = Constructions::getNextEdaNo($construction_no);

				// 登録
				DB::table('d_construction')->insert($input);
			});
	}
	
	
	/**
	 * 物件の更新を行ないます。
	 *
	 * @param array $input 物件編集の入力値
	 */
	public static function update($input) {
		// トランザクション境界
		DB::connection()->transaction(function () use ($input) {
				// 外部キー制約チェックを停止
				DB::query('SET FOREIGN_KEY_CHECKS = 0');
				
				// キーを取得
				$construction_no = $input['construction_no'];
				$construction_eda = $input['construction_eda'];
				// 更新値からは除外
				unset($input['construction_no']);
				unset($input['construction_eda']);
				
				// NULL許可カラムが未入力の場合は、登録値から除外
				$columns = array(
					'architect_company',
					'architect',
					'engineer',
					'order_company',
					'construction_name',
					'construction_address',
					'construction_start_date',
					'complete_date',
					'report_date',
					'amount',
					'material_id',
					'sybt',
					'sybt2',
					'kouzou',
					'yoto',
					'kiso',
					'floor',
					'height',
					'nokidake',
					'totalarea',
					'depth'
				);
				foreach ( $columns as $column ) {
					if ( !$input[$column] ) {
						unset($input[$column]);
					}
				}
				
				// 更新日時
				$input['update_date'] = REQUESTED_DATE;
				
				// 更新
				DB::table('d_construction')
					->where_construction_no($construction_no)
					->where_construction_eda($construction_eda)
					->update($input);
			});
	}
	
	
	/**
	 * 物件検索を行ないます。
	 * ※検索フォームに依存するので画面との結合度が高くなりますが、
	 *   とりあえず目をつぶります。
	 *
	 * @param array $input 検索フォームの入力値
	 */
	public static function search($input) {
		$table = DB::table('d_construction')->where_del_flg(0);
		
		// 認定番号
		if ( isset($input['number1']) && strlen($input['number1']) ) {
			// 会社コードの前方一致
			$number = $input['number1'];
			Log::sql("where: construction_company between " . pad3r($number) . " and " .  pad3r_9($number));
			$table->where_between('construction_company', pad3r($number), pad3r_9($number));
		}
		if ( isset($input['number2']) && strlen($input['number2']) ) {
			// 年度の前方一致
			$number = $input['number2'];
			Log::sql("where: nendo between " . pad2r($number) . " and " .  pad2r_9($number));
			$table->where_between('nendo', pad2r($number), pad2r_9($number));
		}
		if ( isset($input['number3']) && strlen($input['number3']) ) {
			// 最初の１文字が"1"で無い場合は、ヒットさせない
			if ( substr($input['number3'], 0, 1) != '1' ) {
				Log::sql("where: company_seq is null ");
				$table->where_null('company_seq');
			}
			else {
				// 会社通番の前方一致(最初の１桁は"1"固定なので除外)
				$number = substr($input['number3'], 1);
				Log::sql("where: company_seq between " . pad3r($number) . " and " .  pad3r_9($number));
				$table->where_between('company_seq', pad3r($number), pad3r_9($number));
			}
		}
		
		// 施工会社名
		if ( isset($input['company']) && $input['company'] ) {
			Log::sql("where: construction_company = '{$input['company']}'");
			$table->where_construction_company($input['company']);
		}
		
		// 着工日
		if (
			isset($input['s_s_yyyy'])
		 && isset($input['s_s_mm'])
		 && isset($input['s_s_dd'])
		 && isset($input['s_e_yyyy'])
		 && isset($input['s_e_mm'])
		 && isset($input['s_e_dd'])
		) {
			if (
				$input['s_s_yyyy']
			 || $input['s_s_mm']
			 || $input['s_s_dd']
			 || $input['s_e_yyyy']
			 || $input['s_e_mm']
			 || $input['s_e_dd']
			) {
				$startY = pad4r($input['s_s_yyyy']);
				$startM = $input['s_s_mm'] ? pad2($input['s_s_mm']) : '01';
				$startD = $input['s_s_dd'] ? pad2($input['s_s_dd']) : '01';
				$endY = pad4r_9($input['s_e_yyyy']);
				$endM = $input['s_e_mm'] ? pad2($input['s_e_mm']) : '12';
				$endD = $input['s_e_dd'] ? pad2($input['s_e_dd']) : date('t', mktime(0, 0, 0, intval($endM), 1, intval($endY))); // 月の最終日
				Log::sql("where: construction_start_date between {$startY}-{$startM}-{$startD} and {$endY}-{$endM}-{$endD}");
				$table->where_between('construction_start_date', "{$startY}-{$startM}-{$startD}", "{$endY}-{$endM}-{$endD}");
				$table->order_by('construction_start_date');
			}
		}
		
		// 完工日
		if (
			isset($input['e_s_yyyy'])
		 && isset($input['e_s_mm'])
		 && isset($input['e_s_dd'])
		 && isset($input['e_e_yyyy'])
		 && isset($input['e_e_mm'])
		 && isset($input['e_e_dd'])
		) {
			if (
				$input['e_s_yyyy']
			 || $input['e_s_mm']
			 || $input['e_s_dd']
			 || $input['e_e_yyyy']
			 || $input['e_e_mm']
			 || $input['e_e_dd']
			) {
				$startY = pad4r($input['e_s_yyyy']);
				$startM = $input['e_s_mm'] ? pad2($input['e_s_mm']) : '01';
				$startD = $input['e_s_dd'] ? pad2($input['e_s_dd']) : '01';
				$endY = pad4r_9($input['e_e_yyyy']);
				$endM = $input['e_e_mm'] ? pad2($input['e_e_mm']) : '12';
				$endD = $input['e_e_dd'] ? pad2($input['e_e_dd']) : date('t', mktime(0, 0, 0, intval($endM), 1, intval($endY))); // 月の最終日
				Log::sql("where: complete_date between {$startY}-{$startM}-{$startD} and {$endY}-{$endM}-{$endD}");
				$table->where_between('complete_date', "{$startY}-{$startM}-{$startD}", "{$endY}-{$endM}-{$endD}");
				$table->order_by('complete_date');
			}
		}
		
		// ステータス
		if ( isset($input['status']) && $input['status'] ) {
			Log::sql("where: status = '{$input['status']}'");
			$table->where_status($input['status']);
		}
		
		$numbers = $table->get(array('construction_no'));
		$ret = array();
		foreach ( $numbers as $no ) {
			$ret[] = self::get($no->construction_no, 1);
		}
		
		return $ret;
	}
	
	
	/**
	 * 物件を取得します。
	 *
	 * @param string  $construction_no 認定番号
	 * @param numeric $construction_eda 枝番
	 */
	public static function get($construction_no, $construction_eda) {
		$construction = DB::table('d_construction')
			->where_construction_no($construction_no)
			->where_construction_eda($construction_eda)
			->where_del_flg(0)
			->first();
		
		$construction->construction_company_entity = Companies::get($construction->construction_company);
		return $construction;
	}
}
