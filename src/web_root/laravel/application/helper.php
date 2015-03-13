<?php

use Masters\Parts;
/**
 * グローバル関数定義
 *
 * $Author: mizoguchi $
 * $Rev: 205 $
 * $Date: 2013-10-11 01:52:10 +0900 (2013/10/11 (金)) $
 */

/**
 * 日付のみ取得します。（yyyy/mm/dd）
 * @param unknown_type $date
 */
function parseDate($date, $sep = '/'){

	if ($date == '9999-12-31 23:59:59' || !$date) {
		return "";
	}
	$dt = new DateTime($date);

	return $dt->format("Y{$sep}m{$sep}d");
}


/**
 * 第２引数の数値を、第１引数で指定された桁数の幅で右寄せして
 * カンマ区切りにします。
 * 
 * @param int    $length   整形桁数(幅)
 * @param int    $val      対象となる数値
 * @param string $currency 通貨記号
 */
function numberFormatFor($length, $val, $currency = '') {
	$number = $currency . number_format($val);
	return str_pad($number, $length, ' ', STR_PAD_LEFT);
}

/**
 * 引数の文字列を左0埋め2桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 左0埋め2桁で整形した文字列
 */
function pad2($input) {
	return _pad($input, 2);
}

/**
 * 引数の文字列を左0埋め3桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 左0埋め3桁で整形した文字列
 */
function pad3($input) {
	return _pad($input, 3);
}

/**
 * 引数の文字列を左0埋め4桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 左0埋め4桁で整形した文字列
 */
function pad4($input) {
	return _pad($input, 4);
}

/**
 * 引数の文字列を左0埋め5桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 左0埋め5桁で整形した文字列
 */
function pad5($input) {
	return _pad($input, 5);
}

function _pad($input, $len) {
	return str_pad($input, $len, '0', STR_PAD_LEFT);
}

/**
 * 会社区分のラベルを返します。
 * @param int $company_type 会社区分
 */
function getLabelByCompanyType($company_type) {
	switch($company_type) {
		case COMPANY_MEMBER:
			return '一般指定施工会社';
		case COMPANY_MANAGER:
			return '理事会社';
		case COMPANY_JOINT:
			return '共同開発会社';
	}
	return '';
}

/**
 * 各定数値のラベルを返します。
 * @param string $cls 定数値の種類
 * @param int $val 表示する定数値
 */
function getLabelBy($cls, $val) {
	$consts = array();
	switch ($cls) {
		case 'status':
			$consts = Constructions::getAllStatus();
			break;
		case 'sybt':
			$consts = Constructions::getAllSyubetu();
			break;
		case 'sybt2':
			$consts = Constructions::getAllSyubetu_2();
			break;
		case 'kozo':
			$consts = Constructions::getAllKozo();
			break;
		case 'kouzou':
			$consts = Constructions::getAllKozo();
			break;
		case 'kiso':
			$consts = Constructions::getAllKiso();
			break;
		case 'weldings':
			$consts = Parts::getAllWelding();
			break;
		case 'order':
			$consts = Orders::getAllStatus();
			break;
		case 'order_long':
			$consts = Orders::getAllStatusLong();
			break;
		case 'hinmoku':
			$consts = Bills::getAllHinmoku();
			break;
	}
	foreach ( $consts as $k => $v ) {
		if ( $v == $val ) {
			return $k;
		}
	}
	return '';
}


/**
 * YYYY-mm-dd 形式の文字列を「yyyy年mm月dd日」に変換します。
 */
function getLabelYMD($ymd) {
	$date = explode('-', $ymd);
	if ( count($date) == 3 ) {
		$date = array_map('intval', $date);
		return "{$date[0]}年 {$date[1]}月 {$date[2]}日";
	}
	return '';
}

/**
 * YYYY-mm-dd形式の文字列から YYYY の箇所を切り出します。
 */
function parseDateY($date) {
	if ( $date && strlen($date) == 10 ) {
		return substr($date, 0, 4);
	}
	return '';
}

/**
 * YYYY-mm-dd形式の文字列から mm の箇所を切り出します。
 */
function parseDateM($date) {
	if ( $date && strlen($date) == 10 ) {
		return substr($date, 5, 2);
	}
	return '';
}

/**
 * YYYY-mm-dd形式の文字列から dd の箇所を切り出します。
 */
function parseDateD($date) {
	if ( $date && strlen($date) == 10 ) {
		return substr($date, 8, 2);
	}
	return '';
}

/**
 * 配列中のオブジェクトを検索し、条件に一致するオブジェクトのプロパティ値を返します。
 *
 * @param unknown $needle 検索条件
 * @param unknown $haystack 検索対象となるオブジェクトが要素になっている配列
 * @param unknown $property 検索対象プロパティ
 * @param unknown $return_property 取得するプロパティ
 */
function array_search_with($needle, $haystack, $property, $return_property) {
	foreach ( $haystack as $obj ) {
		if ( $obj->$property == $needle ) {
			return $obj->$return_property;
		}
	}
	return FALSE;
}

/**
 * 引数の文字列を右0埋め2桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右0埋め2桁で整形した文字列
 */
function pad2r($input) {
	return _padr($input, 2);
}

/**
 * 引数の文字列を右0埋め3桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右0埋め2桁で整形した文字列
 */
function pad3r($input) {
	return _padr($input, 3);
}

/**
 * 引数の文字列を右0埋め4桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右0埋め2桁で整形した文字列
 */
function pad4r($input) {
	return _padr($input, 4);
}

/**
 * 引数の文字列を右9埋め2桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右9埋め2桁で整形した文字列
 */
function pad2r_9($input) {
	return _padr($input, 2, '9');
}

/**
 * 引数の文字列を右9埋め3桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右9埋め2桁で整形した文字列
 */
function pad3r_9($input) {
	return _padr($input, 3, '9');
}

/**
 * 引数の文字列を右9埋め4桁で整形します。
 *
 * @param mixed $input 入力文字列
 * @return string 右9埋め2桁で整形した文字列
 */
function pad4r_9($input) {
	return _padr($input, 4, '9');
}

function _padr($input, $len, $pad = '0') {
	return str_pad($input, $len, $pad, STR_PAD_RIGHT);
}


function parseConstructionNo($no) {
	if ( strlen($no) != 10 ) {
		return '';
	}
	return substr($no, 0, 4) .'-'. substr($no, 4, 2) .'-'. substr($no, 6, 4);
}
