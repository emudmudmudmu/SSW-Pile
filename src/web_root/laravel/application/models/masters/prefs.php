<?php

namespace Masters;
use Laravel\Cache;
use Laravel\Database as DB;
use Laravel\Log;

class Prefs {

	/**
	 * 都道府県マスターから都道府県一覧を取得します。
	 *
	 * @return array 都道府県一覧
	 */
	public static function getAll() {
		// 都道府県マスタが変わる事はないと思われるので、
		// キャッシュ生成は１回限りで永続化する。
		// キャッシュ再生成の必要がある場合は、
		// 手動でキャッシュファイル削除
		// （もしくはマスタメンテ機能を作成）
		return Cache::get('pref', function () {
			$prefs = DB::table('m_pref')->get();
			Cache::forever('pref', $prefs);
			return $prefs;
		});
	}

	/**
	 * 都道府県マスターから都道府県名を取得します。
	 *
	 * @param int $pref_code 都道府県コード
	 * @return string 都道府県名
	 */
	public static function getName($pref_code) {

		if ( !is_numeric($pref_code) ) {
			throw new \Exception('Invalid Argument');
		}

		$int_code = intval($pref_code);

		if ( $int_code < 1 || 47 < $int_code ) {
			throw new \Exception('Invalid Argument');
		}

		$code = str_pad($int_code, 2, '0', STR_PAD_LEFT);

		// キャッシュについての考え方は getAll() と同じ
		return Cache::get("pref{$code}", function () use ($code, $int_code) {
			$pref = DB::table('m_pref')->where_pref_code($int_code)
				->first();
			Cache::forever("pref{$code}", $pref->pref_name);
			return $pref->pref_name;
		});
	}
}
