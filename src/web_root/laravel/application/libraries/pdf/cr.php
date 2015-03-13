<?php

use Laravel\Bundle;
use Masters\BasicInfo;

// グローバル
define('H' , 4.5);
define('X1',  20);
define('X2',  56);
define('X3',  72);
define('X4',  88);
define('X5', 104);
define('X6', 121);

define('X7' , 152);
define('X8' , 172);
define('X9' , 189.5);
define('X10', 207);
define('X11', 224.5);
define('X12', 242);
define('X13', 259.5);

define('W1', 36);
define('W2', 16);
define('W3', 16);
define('W4', 16);
define('W5', 17);
define('W6', 24);
define('S4', W4 + W5 + W6);

define('W7', 20);
define('W8', 17.5);
define('W9', 17.5);
define('W10', 17.5);
define('W11', 17.5);
define('W12', 17.5);
define('W13', 17.5);
define('S7', W7 + W8 + W9 + W10 + W11 + W12 + W13);

function _l($line) {
	return 25 + (H * $line);
}

/**
 * 施工実績件数表のPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_Cr extends PdfBase {
	
	private $title;
	private $consts;
	private $start;
	private $end;
	private $targetNendo;
	
	private $offset;
	
	private $cat1;
	private $cat2;
	private $cat3;
	private $cat4;
	
	public function __construct($consts = array()) {
		parent::__construct();
		
		// 検索結果を保持
		$this->consts = $consts;
		
		// テンプレート
		$this->setTemplate('pdf.cr1', LANDSCAPE);
		
		$this->pdf->setColor('text', 0, 0, 0);
		
		// 固定文言の出力
		$this->_init();
	}
	
	public function setSpan($start, $end) {
		$this->start = $start;
		$this->end   = $end;
		
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$span = $this->convertToHeisei($start) .'～'. $this->convertToHeisei($end);
		$this->writeCell( X7, _l( 0), S7 + 1.7, H, $span, RIGHT);
		
		// 集計
		if ( $this->consts ) {
			$this->_calc();
			// 書式2の出力
			$this->_write_cr2();
		}
	}
	
	private function _nendo($date) {
		$dates = explode('-', $date);
		$y = $dates[0] - 1988;
		$m = intval($dates[1]);
		if ( $m < 4 ) {
			// ～3月31日までは、前年の年数の年度
			return $y - 1;
		}
		return $y;
	}
	
	private function _write_cr2() {
		// 先に延べ面積、打設本数、最大施工深さの最大値を求める
		// 各ページのヘッダになるため
		$max_totalarea = 0;
		$max_amount    = 0;
		$max_depth     = 0;
		foreach ( $this->consts as $const ) {
			$max_totalarea = max($max_totalarea, $const->totalarea);
			$max_amount    = max($max_amount, $const->amount);
			$max_depth     = max($max_depth, $const->depth);
		}
		// 1ページあたり25件として、
		// ページ数を求める
		$pages = array_chunk($this->consts, 25);
		$all = count($pages);
		$page = 1;
		$line = 1;
		// ページ単位でループ
		foreach ( $pages as $consts ) {
			$this->setTemplate('pdf.cr2', LANDSCAPE);
			$this->_init2($page, $all, $max_totalarea, $max_amount, $max_depth);
			
			$this->setFont(FONT_IPA_MINCHO_P, 8);
			
			$line_on = 9;
			// 同ページ内の物件を出力
			foreach ( $consts as $const ) {
				
				$this->writeCell( 20, _l($line_on),   5, H, $line                                   , CENTER); // No.
				$this->writeCell( 25, _l($line_on),  60, H, $const->construction_name               , LEFT); // 工事名
				$this->writeCell( 85, _l($line_on),  16, H, $this->convertToH($const->complete_date), CENTER); // 竣工年月
				$this->writeCell(101, _l($line_on),  32, H, $const->construction_address            , CENTER); // 所在地
				$this->writeCell(133, _l($line_on),  16, H, getLabelBy('kozo', $const->kouzou)      , CENTER); // 構造
				$this->writeCell(149, _l($line_on),  16, H, $const->yoto                            , CENTER); // 用途
				$this->writeCell(165, _l($line_on),  16, H, getLabelBy('kiso', $const->kiso)        , CENTER); // 基礎形式
				$this->writeCell(181, _l($line_on),  16, H, $const->floor                           , RIGHT); // 階数
				$this->writeCell(197, _l($line_on),  16, H, $const->height                          , RIGHT); // 高さ
				$this->writeCell(213, _l($line_on),  16, H, $const->nokidake                        , RIGHT); // 軒高
				$this->writeCell(229, _l($line_on),  16, H, $const->totalarea                       , RIGHT); // 延べ面積
				$this->writeCell(245, _l($line_on),  16, H, $const->amount                          , RIGHT); // 打設本数
				$this->writeCell(261, _l($line_on),  16, H, $const->depth                           , RIGHT); // 最大施工深さ
				
				// 行番号のインクリメント
				$line_on++;
				$line++;
			}
			
			// ページのインクリメント
			$page++;
		}
	}
	
	// 書式2の固定文言を出力
	private function _init2($page, $all, $max_totalarea, $max_amount, $max_depth) {
		$this->setFont(FONT_IPA_MINCHO_P, 12);
		$this->writeCell( X1,  20, 257,  5, '２．施工実績一覧表', CENTER);
		
		$this->pdf->setColor('text', 256, 0, 0);
		$this->setFont(FONT_IPA_MINCHO_P, 11);
		$this->writeCell( X1 - 1.2, 20, 257,  5, 'SSW-pile工法');
		
		$this->pdf->setColor('text', 0, 0, 0);
		
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$span = $this->convertToHeisei($this->start) .'～'. $this->convertToHeisei($this->end);
		$this->writeCell( X7, _l( 0), S7 + 1.7, H, $span, RIGHT);
		
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell( X1, _l( 4), 193, H, '註）※1：竣工年月が不明の場合は、当該工法の施工完了時の日付を記入');
		$this->setFont(FONT_IPA_MINCHO_P, 7);
		$this->writeCell(261, _l(36),  16 + 1.2, H, "{$page}/{$all}", RIGHT);
		
		
		$this->setFont(FONT_IPA_MINCHO_P, 10);
		$this->writeCell(213, _l( 2),  16, H * 3, "最大値"       , CENTER);
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(229, _l( 2),  16, H    , "延べ面積"     , CENTER);
		$this->writeCell(229, _l( 3),  16, H    , "（㎡）"       , RIGHT);
		$this->writeCell(229, _l( 4),  16, H    , $max_totalarea , RIGHT);
		$this->writeCell(245, _l( 2),  16, H    , "打設本数"     , CENTER);
		$this->writeCell(245, _l( 3),  16, H    , "（本）"       , RIGHT);
		$this->writeCell(245, _l( 4),  16, H    , $max_amount    , RIGHT);
		$this->writeCell(261, _l( 2),  16, H    , "最大施工深さ" , CENTER);
		$this->writeCell(261, _l( 3),  16, H    , "（m）"        , RIGHT);
		$this->writeCell(261, _l( 4),  16, H    , $max_depth     , RIGHT);
		
		$this->writeCell( 20, _l( 6),   5, H * 3, 'No.'          , CENTER);
		$this->writeCell( 25, _l( 6),  60, H * 3, '工事名'       , CENTER);
		$this->writeCell( 85, _l( 6),  16, H * 3, '竣工年月※1'  , CENTER);
		$this->writeCell(101, _l( 6),  32, H * 3, '所在地'       , CENTER);
		$this->writeCell(133, _l( 6),  16, H * 3, '構造'         , CENTER);
		$this->writeCell(149, _l( 6),  16, H * 3, '用途'         , CENTER);
		$this->writeCell(165, _l( 6),  16, H * 3, '基礎形式'     , CENTER);
		
		$this->writeCell(181, _l( 6),16*4, H * 1, '建物規模'     , CENTER);
		$this->writeCell(181, _l( 7),  16, H * 1, '階数'         , CENTER);
		
		$this->writeCell(197, _l( 7),  16, H * 1, '高さ'         , CENTER);
		$this->writeCell(197, _l( 8),  16, H * 1, '（m）'        , RIGHT);
		
		$this->writeCell(213, _l( 7),  16, H * 1, '軒高'         , CENTER);
		$this->writeCell(213, _l( 8),  16, H * 1, '（m）'        , RIGHT);
		
		$this->writeCell(229, _l( 7),  16, H * 1, '延べ面積'     , CENTER);
		$this->writeCell(229, _l( 8),  16, H * 1, '（㎡）'       , RIGHT);
		
		$this->writeCell(245, _l( 7),  16, H * 1, '打設本数'     , CENTER);
		$this->writeCell(245, _l( 8),  16, H * 1, '（本）'       , RIGHT);
		
		$this->writeCell(261, _l( 7),  16, H * 1, '最大施工深さ' , CENTER);
		$this->writeCell(261, _l( 8),  16, H * 1, '（m）'        , RIGHT);
		
	}
	
	private function _calc() {
		// 対象年度の解析
		$this->_reset_target_nendo();
		
		// 集計基準の列タイトルを設定
		$this->_set_titles();
		
		// 対象年度解析・列タイトル設定が終われば、
		// 対象年度を昇順に再並び替えする
		sort($this->targetNendo);
		
		// 「四号建築物」の集計
		$this->cat1 = $this->_calc_cat1();
		// 「学会小規模指針」の集計
		$this->cat2 = $this->_calc_cat23(10, SYBT_GAKKAI);
		// 「その他」の集計
		$this->cat3 = $this->_calc_cat23(18, SYBT_OTHER);
		// 「工作物」の集計
		$this->cat4 = $this->_calc_cat4();
		
		// 合計欄の計算
		$this->_calc_total();
		
		// 帳票左側の「その他」の欄
		$this->_calc_other_max();
	}
	
	private function _hikaku($propname, $index, &$ret, $const) {
		$line = $index - 1;
		if ( $const->$propname == max($ret[$line][$index], $const->$propname) ) {
			$ret[$line][0] = getLabelBy('kozo', $const->kouzou);
			$ret[$line][1] = $const->floor;
			$ret[$line][2] = $const->height;
			$ret[$line][3] = $const->nokidake;
			$ret[$line][4] = $const->totalarea;
		}
	}
	
	private function _calc_other_max() {
		// 種別「その他」の場合は、階数・高さ・軒高・延べ面積の
		// それぞれで最大値を取る物件をリストアップする
		
		// 上から順に、階数・高さ・軒高・延べ面積の第１位
		$ret = array(
				array('', 0, 0, 0, 0),
				array('', 0, 0, 0, 0),
				array('', 0, 0, 0, 0),
				array('', 0, 0, 0, 0),
		);
		foreach ( $this->consts as $const ) {
			if ( $const->sybt == SYBT_OTHER ) {
				// 階数比較
				$this->_hikaku('floor',     1, $ret, $const);
				// 高さ比較
				$this->_hikaku('height',    2, $ret, $const);
				// 軒高比較
				$this->_hikaku('nokidake',  3, $ret, $const);
				// 延べ面積比較
				$this->_hikaku('totalarea', 4, $ret, $const);
			}
		}
		// 値の設定
		$i = 0;
		foreach ( $ret as $line ) {
			$this->writeCell( X1, _l(18 + $i), W1, H, $line[0], CENTER);
			$this->writeCell( X2, _l(18 + $i), W2, H, $line[1], RIGHT);
			$this->writeCell( X3, _l(18 + $i), W3, H, $line[2], RIGHT);
			$this->writeCell( X4, _l(18 + $i), W4, H, $line[3], RIGHT);
			$this->writeCell( X5, _l(18 + $i), W5, H, $line[4], RIGHT);
			
			$i++;
		}
	}
	
	private function _calc_total() {
		$target = array(
				$this->cat1,
				$this->cat2,
				$this->cat3,
				$this->cat4,
		);
		$total = array(0,0,0,0);
		foreach ( $target as $cat ) {
			foreach ( $cat as $line ) {
				$total[0] += $line[0];
				$total[1] += $line[1];
				$total[2] += $line[2];
				$total[3] += $line[3];
			}
		}
		if ( 2 < $this->offset->l )
			$this->writeCell( X8, _l(36),  W8, H, $total[0], RIGHT);
		if ( 1 < $this->offset->l )
			$this->writeCell( X9, _l(36),  W9, H, $total[1], RIGHT);
		$this->writeCell(X10, _l(36), W10, H, $total[2], RIGHT);
		$this->writeCell(X11, _l(36), W11, H, array_sum($total) - $total[3], RIGHT);
		if ( $this->offset->r )
			$this->writeCell(X12, _l(36), W12, H, $total[3], RIGHT);
		$this->writeCell(X13, _l(36), W13, H, array_sum($total), RIGHT);
	}
	
	private function _calc_cat4() {
		// 計４列（「○年度」３列、「○年度以前」１列）の
		// 多次元配列で集計する
		$ret = array(
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
		);
		foreach ( $this->consts as $const ) {
			// 構造種別が「工作物」であれば集計対象
			if ( $const->sybt == SYBT_KOSAKU ) {
				// 年度判定
				$nendo = $this->_nendo($const->complete_date);
				$index = array_search($nendo, $this->targetNendo);
				// 年度が対象年度に含まれていなければ
				if ( $index === FALSE ) {
					$index = 3; // ○年度以前として処理
				}
				else {
					// ○年度の列が空列の場合があるので、
					// その時は→にシフト
					if ( $this->offset->l < 2 ) $index++; 
					if ( $this->offset->l < 3 ) $index++; 
				}
				/* 構造判定 */
				$kozo = -1;
				// まず種別２
				switch ($const->sybt2) {
					// 擁壁
					case SYBT2_YOHEKI: {
						// 構造別
						// RC造
						if ( $const->kouzou == KOZO_RC ) {
							if ( $const->height < 2 ) {
								$kozo = 0;
							}
							else if ( $const->height <= 10 ) {
								$kozo = 1;
							}
							else {
								$kozo = 2;
							}
						}
						// 間知石造, CB造
						else if ( $const->kouzou == KOZO_KENCHI || $const->kouzou == KOZO_CB ) {
							if ( $const->height < 2 ) {
								$kozo = 3;
							}
							else if ( $const->height <= 5 ) {
								$kozo = 4;
							}
						}
						break;
					}
					// 広告塔
					case SYBT2_KOKOKU: 	$kozo = 5; break;
					// 鉄塔
					case SYBT2_TETU:   $kozo = 6; break;
					// その他
					case SYBT2_OTHER:  $kozo = 7; break;
				}
				// 集計対象に当てはまらない場合は、スキップ
				// ※現時点では、たとえば擁壁だが木造、
				//   擁壁でCB造だが5mを超えている、等、
				// 　集計対象外になり得るものがあるので。
				if ( $kozo < 0 ) {
					continue;
				}
				// 集計
				$ret[$kozo][$index]++;
			}
		}
		
		// 集計値を設定
		for ( $i = 0; $i < 4; $i++ ) {
			// 左列から処理
			if ( ($i < 3 && (2 - $i) < $this->offset->l) || ($i == 3 && $this->offset->r) ) {
				$x;$w;
				switch ($i) {
					case 0: $x =  X8; $w =  W8; break;
					case 1: $x =  X9; $w =  W9; break;
					case 2: $x = X10; $w = W10; break;
					case 3: $x = X12; $w = W12; break;
				}
				$this->writeCell( $x, _l(26), $w, H, $ret[0][$i], RIGHT);
				$this->writeCell( $x, _l(27), $w, H, $ret[1][$i], RIGHT);
				$this->writeCell( $x, _l(28), $w, H, $ret[2][$i], RIGHT);
				$this->writeCell( $x, _l(29), $w, H, $ret[3][$i], RIGHT);
				$this->writeCell( $x, _l(30), $w, H, $ret[4][$i], RIGHT);
				$this->writeCell( $x, _l(31), $w, H, $ret[5][$i], RIGHT);
				$this->writeCell( $x, _l(32), $w, H, $ret[6][$i], RIGHT);
				$this->writeCell( $x, _l(33), $w, H, $ret[7][$i], RIGHT);
				// 小計
				$subtotal = $ret[0][$i] + $ret[1][$i] + $ret[2][$i]
						  + $ret[3][$i] + $ret[4][$i] + $ret[5][$i]
						  + $ret[6][$i] + $ret[7][$i];
				$this->writeCell( $x, _l(34), $w, H, $subtotal,  RIGHT);
			}
		}
		/* 期間累計 */
		$span_total = array(
				array_sum($ret[0]) - $ret[0][3],
				array_sum($ret[1]) - $ret[1][3],
				array_sum($ret[2]) - $ret[2][3],
				array_sum($ret[3]) - $ret[3][3],
				array_sum($ret[4]) - $ret[4][3],
				array_sum($ret[5]) - $ret[5][3],
				array_sum($ret[6]) - $ret[6][3],
				array_sum($ret[7]) - $ret[7][3],
		);
		$this->writeCell( X11, _l(26), W11, H, $span_total[0], RIGHT);
		$this->writeCell( X11, _l(27), W11, H, $span_total[1], RIGHT);
		$this->writeCell( X11, _l(28), W11, H, $span_total[2], RIGHT);
		$this->writeCell( X11, _l(29), W11, H, $span_total[3], RIGHT);
		$this->writeCell( X11, _l(30), W11, H, $span_total[4], RIGHT);
		$this->writeCell( X11, _l(31), W11, H, $span_total[5], RIGHT);
		$this->writeCell( X11, _l(32), W11, H, $span_total[6], RIGHT);
		$this->writeCell( X11, _l(33), W11, H, $span_total[7], RIGHT);
		// 小計
		$subtotal = array_sum($span_total);
		$this->writeCell( X11, _l(34), W11, H, $subtotal,  RIGHT);

		/* 累計 */
		$total = array(
				array_sum($ret[0]),
				array_sum($ret[1]),
				array_sum($ret[2]),
				array_sum($ret[3]),
				array_sum($ret[4]),
				array_sum($ret[5]),
				array_sum($ret[6]),
				array_sum($ret[7]),
		);
		$this->writeCell( X13, _l(26), W13, H, $total[0], RIGHT);
		$this->writeCell( X13, _l(27), W13, H, $total[1], RIGHT);
		$this->writeCell( X13, _l(28), W13, H, $total[2], RIGHT);
		$this->writeCell( X13, _l(29), W13, H, $total[3], RIGHT);
		$this->writeCell( X13, _l(30), W13, H, $total[4], RIGHT);
		$this->writeCell( X13, _l(31), W13, H, $total[5], RIGHT);
		$this->writeCell( X13, _l(32), W13, H, $total[6], RIGHT);
		$this->writeCell( X13, _l(33), W13, H, $total[7], RIGHT);
		// 小計
		$subtotal = array_sum($total);
		$this->writeCell( X13, _l(34), W13, H, $subtotal,  RIGHT);
		
		return $ret;
	}
	
	private function _calc_cat23($line_start, $sybt) {
		$line1 = $line_start + 0;
		$line2 = $line_start + 1;
		$line3 = $line_start + 2;
		$line4 = $line_start + 3;
		// 計４列（「○年度」３列、「○年度以前」１列）の
		// 多次元配列で集計する
		$ret = array(
				array(0,0,0,0),
				array(0,0,0,0),
				array(0,0,0,0),
		);
		foreach ( $this->consts as $const ) {
			// 指定された構造種別であれば集計対象
			if ( $const->sybt == $sybt ) {
				// 年度判定
				$nendo = $this->_nendo($const->complete_date);
				$index = array_search($nendo, $this->targetNendo);
				// 年度が対象年度に含まれていなければ
				if ( $index === FALSE ) {
					$index = 3; // ○年度以前として処理
				}
				else {
					// ○年度の列が空列の場合があるので、
					// その時は→にシフト
					if ( $this->offset->l < 2 ) $index++; 
					if ( $this->offset->l < 3 ) $index++; 
				}
				// 構造判定(木造なら0, S造なら1, それ以外は2)
				$kozo = 2;
				switch ($const->kouzou) {
					case KOZO_MOKU: $kozo = 0; break;
					case KOZO_S:    $kozo = 1; break;
				}
				// 集計
				$ret[$kozo][$index]++;
			}
		}
		
		// 集計値を設定
		for ( $i = 0; $i < 4; $i++ ) {
			// 左列から処理
			if ( ($i < 3 && (2 - $i) < $this->offset->l) || ($i == 3 && $this->offset->r) ) {
				$x;$w;
				switch ($i) {
					case 0: $x =  X8; $w =  W8; break;
					case 1: $x =  X9; $w =  W9; break;
					case 2: $x = X10; $w = W10; break;
					case 3: $x = X12; $w = W12; break;
				}
				$this->writeCell( $x, _l($line1), $w, H, $ret[0][$i], RIGHT);
				$this->writeCell( $x, _l($line2), $w, H, $ret[1][$i], RIGHT);
				$this->writeCell( $x, _l($line3), $w, H, $ret[2][$i], RIGHT);
				// 小計
				$subtotal = $ret[0][$i] + $ret[1][$i] + $ret[2][$i];
				$this->writeCell( $x, _l($line4), $w, H, $subtotal,  RIGHT);
			}
		}
		/* 期間累計 */
		$span_total = array(
				array_sum($ret[0]) - $ret[0][3],
				array_sum($ret[1]) - $ret[1][3],
				array_sum($ret[2]) - $ret[2][3],
		);
		$this->writeCell( X11, _l($line1), W11, H, $span_total[0], RIGHT);
		$this->writeCell( X11, _l($line2), W11, H, $span_total[1], RIGHT);
		$this->writeCell( X11, _l($line3), W11, H, $span_total[2], RIGHT);
		// 小計
		$subtotal = array_sum($span_total);
		$this->writeCell( X11, _l($line4), W11, H, $subtotal,  RIGHT);

		/* 累計 */
		$total = array(
				array_sum($ret[0]),
				array_sum($ret[1]),
				array_sum($ret[2]),
		);
		$this->writeCell( X13, _l($line1), W13, H, $total[0], RIGHT);
		$this->writeCell( X13, _l($line2), W13, H, $total[1], RIGHT);
		$this->writeCell( X13, _l($line3), W13, H, $total[2], RIGHT);
		// 小計
		$subtotal = array_sum($total);
		$this->writeCell( X13, _l($line4), W13, H, $subtotal,  RIGHT);
		
		return $ret;
	}
	
	private function _calc_cat1() {
		// 計４列（「○年度」３列、「○年度以前」１列）の
		// 多次元配列で集計する
		$ret = array(
				array(0,0,0,0),
				array(0,0,0,0),
		);
		foreach ( $this->consts as $const ) {
			// 四号建築物であれば集計対象
			if ( $const->sybt == SYBT_4GO ) {
				// 年度判定
				$nendo = $this->_nendo($const->complete_date);
				$index = array_search($nendo, $this->targetNendo);
				// 年度が対象年度に含まれていなければ
				if ( $index === FALSE ) {
					$index = 3; // ○年度以前として処理
				}
				else {
					// ○年度の列が空列の場合があるので、
					// その時は→にシフト
					if ( $this->offset->l < 2 ) $index++; 
					if ( $this->offset->l < 3 ) $index++; 
				}
				// 構造判定(木造なら0, それ以外は1)
				$kozo  = ($const->kouzou == KOZO_MOKU ? 0 : 1);
				// 集計
				$ret[$kozo][$index]++;
			}
		}
		
		// 集計値を設定
		for ( $i = 0; $i < 4; $i++ ) {
			// 左列から処理
			if ( ($i < 3 && (2 - $i) < $this->offset->l) || ($i == 3 && $this->offset->r) ) {
				$x;$w;
				switch ($i) {
					case 0: $x =  X8; $w =  W8; break;
					case 1: $x =  X9; $w =  W9; break;
					case 2: $x = X10; $w = W10; break;
					case 3: $x = X12; $w = W12; break;
				}
				$this->writeCell( $x, _l( 4), $w, H, $ret[0][$i], RIGHT);
				$this->writeCell( $x, _l( 5), $w, H, $ret[1][$i], RIGHT);
				// 小計
				$subtotal = $ret[0][$i] + $ret[1][$i];
				$this->writeCell( $x, _l( 6), $w, H, $subtotal,  RIGHT);
			}
		}
		/* 期間累計 */
		$span_total = array(
				array_sum($ret[0]) - $ret[0][3],
				array_sum($ret[1]) - $ret[1][3],
		);
		$this->writeCell( X11, _l( 4), W11, H, $span_total[0], RIGHT);
		$this->writeCell( X11, _l( 5), W11, H, $span_total[1], RIGHT);
		// 小計
		$subtotal = array_sum($span_total);
		$this->writeCell( X11, _l( 6), W11, H, $subtotal,  RIGHT);

		/* 累計 */
		$total = array(
				array_sum($ret[0]),
				array_sum($ret[1]),
		);
		$this->writeCell( X13, _l( 4), W13, H, $total[0], RIGHT);
		$this->writeCell( X13, _l( 5), W13, H, $total[1], RIGHT);
		// 小計
		$subtotal = array_sum($total);
		$this->writeCell( X13, _l( 6), W13, H, $subtotal,  RIGHT);
		
		return $ret;
	}
	
	private function _set_titles () {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		
		// 最右列(最大年度)
		$nendo = "H{$this->targetNendo[0]}年度";
		$this->writeCell(X10, _l( 3), W10, H, $nendo, CENTER);
		$this->writeCell(X10, _l( 9), W10, H, $nendo, CENTER);
		$this->writeCell(X10, _l(17), W10, H, $nendo, CENTER);
		$this->writeCell(X10, _l(25), W10, H, $nendo, CENTER);
		
		// 次に小さい年度
		if ( 1 < $this->offset->l ) {
			$nendo = "H{$this->targetNendo[1]}年度";
			$this->writeCell( X9, _l( 3),  W9, H, $nendo, CENTER);
			$this->writeCell( X9, _l( 9),  W9, H, $nendo, CENTER);
			$this->writeCell( X9, _l(17),  W9, H, $nendo, CENTER);
			$this->writeCell( X9, _l(25),  W9, H, $nendo, CENTER);
		}
		
		// ３番目の年度
		if ( 2 < $this->offset->l ) {
			$nendo = "H{$this->targetNendo[2]}年度";
			$this->writeCell( X8, _l( 3),  W8, H, $nendo, CENTER);
			$this->writeCell( X8, _l( 9),  W8, H, $nendo, CENTER);
			$this->writeCell( X8, _l(17),  W8, H, $nendo, CENTER);
			$this->writeCell( X8, _l(25),  W8, H, $nendo, CENTER);
		}
		
		// ○年度以前
		if ( $this->offset->r ) {
			$nendo = min($this->targetNendo) - 1;
			$nendo = "H{$nendo}年度以前";
			$this->writeCell(X12, _l( 3), W12, H, $nendo, CENTER);
			$this->writeCell(X12, _l( 9), W12, H, $nendo, CENTER);
			$this->writeCell(X12, _l(17), W12, H, $nendo, CENTER);
			$this->writeCell(X12, _l(25), W12, H, $nendo, CENTER);
		}
	}
	
	private function _reset_target_nendo() {
		// 対象年度の解析
		$this->targetNendo = array();
		foreach ( $this->consts as $const ) {
			// 完工日で計算
			$this->targetNendo[] = $this->_nendo($const->complete_date);
		}
		// 対象年度の最大値
		$max_nendo = max($this->targetNendo);
		
		// 対象年度から過去３年度分は、データがなくても集計対象
		// ただし、集計開始日以前は除外
		if ( $this->_nendo($this->start) <= $max_nendo - 1 ) {
			$this->targetNendo[] = $max_nendo - 1;
		}
		if ( $this->_nendo($this->start) <= $max_nendo - 2 ) {
			$this->targetNendo[] = $max_nendo - 2;
		}
		
		// 値を一意にして逆順ソート(列タイトルを設定した後は再度昇順でソートし直す)
		$this->targetNendo = array_unique($this->targetNendo);
		rsort($this->targetNendo, SORT_NUMERIC);
		
		// 最大で先頭から３年度のみが集計対象
		// それ以前は「○年度以前」として集計するので、
		// $targetNendo からは除外
		$chunked = array_chunk($this->targetNendo, 3);
		$this->targetNendo = $chunked[0];
		
		// ページ右側の集計表について「オフセット」としてその列数を
		// 保持しておく
		// 「H○年度」の列数、「H○年度以前」の列数（1または0）
		$this->offset = (object) array(
				'l' => count($this->targetNendo),
				'r' => (count($this->targetNendo) == 3 && $this->_nendo($this->start) < min($this->targetNendo) ? 1 : 0),
		);
	}
	
	
	/**
	 * 固定文言の出力
	 */
	private function _init() {
		$this->setFont(FONT_IPA_MINCHO_P, 12);
		$this->writeCell( X1,  20, 257,  5, '１．施工実績件数一覧表', CENTER);
		
		$this->pdf->setColor('text', 256, 0, 0);
		$this->setFont(FONT_IPA_MINCHO_P, 11);
		$this->writeCell( X1 - 1.2, 20, 257,  5, 'SSW-pile工法');
		
		$this->pdf->setColor('text', 0, 0, 0);
		
		// タイトル類
		$this->setFont(FONT_IPA_MINCHO_P, 10);
		$this->writeCell( X1 - 1.2, _l(1) - 1, 125, H, '四号建築物、学会小規模指針、その他による分類');
		
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell( X1, _l( 2), W1, H, '①四号建築物');
		$this->writeCell( X1, _l( 8), W1, H, '②小規模指針※');
		$this->writeCell( X1, _l(16), W1, H, '③その他');
		$this->writeCell( X1, _l(24), W1, H, '④工作物');
		
		// 補足文章
		$this->setFont(FONT_IPA_MINCHO_P, 7);
		$this->writeCell( X2, _l( 2),  89, H, '（構造計算書の提出が不要とされる小規模な建築物）');
		$this->writeCell( X2, _l( 8),  89, H, '（学会小規模指針の適用範囲の中で、四号建築物とならない建築物）');
		$this->writeCell( X2, _l(16),  89, H, '（小規模指針の適用範囲外の建築物）');
		$this->writeCell( X2, _l(24),  89, H, '（擁壁、広告塔、鉄塔、その他）');
		
		$this->writeCell( X1, _l(13), 125, H, '※小規模建築物基礎設計指針（日本建築学会、2008）');
		$this->writeCell( X1, _l(22), 125, H, '註)階数、高さ、軒高、延べ面積がそれぞれ最大の案件について、1行に表示。');
		
		// ①四号建築物
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell( X1, _l( 3), W1, H, '構造種別'   , CENTER);
		$this->writeCell( X2, _l( 3), W2, H, '階数'       , CENTER);
		$this->writeCell( X3, _l( 3), W3, H, '高さ'       , CENTER);
		$this->writeCell( X4, _l( 3), W4, H, '軒高'       , CENTER);
		$this->writeCell( X5, _l( 3), W5, H, '延べ面積'   , CENTER);
		
		$this->writeCell( X1, _l( 4), W1, H, '木造'       , CENTER);
		$this->writeCell( X2, _l( 4), W2, H, '≦2'        , CENTER);
		$this->writeCell( X3, _l( 4), W3, H, '≦13m'      , CENTER);
		$this->writeCell( X4, _l( 4), W4, H, '≦9m'       , CENTER);
		$this->writeCell( X5, _l( 4), W5, H, '≦500㎡'    , CENTER);
		
		$this->writeCell( X1, _l( 5), W1, H, 'S造、RC造等', CENTER);
		$this->writeCell( X2, _l( 5), W2, H, '1'          , CENTER);
		$this->writeCell( X3, _l( 5), W3, H, '－'         , CENTER);
		$this->writeCell( X4, _l( 5), W4, H, '－'         , CENTER);
		$this->writeCell( X5, _l( 5), W5, H, '≦200㎡'    , CENTER);
		
		// ②小規模指針
		$this->writeCell( X1, _l( 9), W1, H, '構造種別'   , CENTER);
		$this->writeCell( X2, _l( 9), W2, H, '階数'       , CENTER);
		$this->writeCell( X3, _l( 9), W3, H, '高さ'       , CENTER);
		$this->writeCell( X4, _l( 9), W4, H, '軒高'       , CENTER);
		$this->writeCell( X5, _l( 9), W5, H, '延べ面積'   , CENTER);
		
		$this->writeCell( X1, _l(10), W1, H, '木造'       , CENTER);
		$this->writeCell( X1, _l(11), W1, H, 'S造'        , CENTER);
		$this->writeCell( X1, _l(12), W1, H, 'RC造等'     , CENTER);
		
		$this->writeCell( X2, _l(10), W2, H * 3, '≦3'    , CENTER);
		$this->writeCell( X3, _l(10), W3, H * 3, '≦13m'  , CENTER);
		$this->writeCell( X4, _l(10), W4, H * 3, '≦9m'   , CENTER);
		$this->writeCell( X5, _l(10), W5, H * 3, '≦500㎡', CENTER);
		
		
		// ③その他
		$this->writeCell( X1, _l(17), W1, H, '構造種別'   , CENTER);
		$this->writeCell( X2, _l(17), W2, H, '階数'       , CENTER);
		$this->writeCell( X3, _l(17), W3, H, '高さ'       , CENTER);
		$this->writeCell( X4, _l(17), W4, H, '軒高'       , CENTER);
		$this->writeCell( X5, _l(17), W5, H, '延べ面積'   , CENTER);
		
		// ④工作物
		$this->writeCell( X1, _l(25), W1, H, '種別'       , CENTER);
		$this->writeCell( X2, _l(25), W2, H, '階数'       , CENTER);
		$this->writeCell( X3, _l(25), W3, H, '高さ'       , CENTER);
		$this->writeCell( X4, _l(25), W4, H, 'その他'     , LEFT);
		
		$this->writeCell( X1, _l(26), W1, H * 5, '擁壁'   , CENTER);
		$this->writeCell( X2, _l(26), W2, H * 3, 'RC造'   , CENTER);
		$this->writeCell( X3, _l(26), W3, H    , '＜2m'   , CENTER);
		$this->writeCell( X4, _l(26), S4, H    , '建築基準法の確認不要'   , LEFT);
		
		$this->writeCell( X3, _l(27), W3, H    , '≦10m'   , CENTER);
		$this->writeCell( X4, _l(27), S4, H    , '建築基準法の確認必要'   , LEFT);
	
		$this->writeCell( X3, _l(28), W3, H    , '＞10m'   , CENTER);
		$this->writeCell( X4, _l(28), S4, H    , '安全審査必要（近畿建築行政会議での取り扱い）'   , LEFT);
	
		$this->writeCell( X2, _l(29), W2, H  , '間知石造'  , CENTER);
		$this->writeCell( X3, _l(29), W3, H  , '＜2m'      , CENTER);
		$this->writeCell( X4, _l(29), S4, H  , '建築基準法の確認不要'   , LEFT);
		
		$this->writeCell( X2, _l(30), W2, H  , 'CB造'      , CENTER);
		$this->writeCell( X3, _l(30), W3, H  , '≦5m'      , CENTER);
		$this->writeCell( X4, _l(30), S4, H  , '建築基準法の確認必要（宅造法による制限）'   , LEFT);
		
		$this->writeCell( X1, _l(31), W1, H, '広告塔'      , CENTER);
		$this->writeCell( X2, _l(31), W2, H, '－'          , CENTER);
		
		$this->writeCell( X1, _l(32), W1, H, '鉄塔'        , CENTER);
		$this->writeCell( X2, _l(32), W2, H, '－'          , CENTER);
		
		$this->writeCell( X1, _l(33), W1, H, 'その他'      , CENTER);
		$this->writeCell( X2, _l(33), W2, H, '－'          , CENTER);
		
		
		/* 右側 */
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell( X7 - 1.2, _l( 2), S7, H, '実績件数'    , LEFT);
		$this->writeCell( X7 - 1.2, _l( 8), S7, H, '実績件数'    , LEFT);
		$this->writeCell( X7 - 1.2, _l(16), S7, H, '実績件数'    , LEFT);
		$this->writeCell( X7 - 1.2, _l(24), S7, H, '実績件数'    , LEFT);
		
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(X11, _l( 3),W11, H, '期間累計'    , CENTER);
		$this->writeCell(X11, _l( 9),W11, H, '期間累計'    , CENTER);
		$this->writeCell(X11, _l(17),W11, H, '期間累計'    , CENTER);
		$this->writeCell(X11, _l(25),W11, H, '期間累計'    , CENTER);
		
		$this->writeCell(X13, _l( 3),W13, H, '累計'        , CENTER);
		$this->writeCell(X13, _l( 9),W13, H, '累計'        , CENTER);
		$this->writeCell(X13, _l(17),W13, H, '累計'        , CENTER);
		$this->writeCell(X13, _l(25),W13, H, '累計'        , CENTER);
		
		
		$this->writeCell( X7, _l( 3), W7, H, '構造'        , CENTER);
		$this->writeCell( X7, _l( 4), W7, H, '木造'        , CENTER);
		$this->writeCell( X7, _l( 5), W7, H, 'S、RC造等'   , CENTER);
		$this->writeCell( X7, _l( 6), W7, H, '小計'        , CENTER);
		
		$this->writeCell( X7, _l( 9), W7, H, '構造'        , CENTER);
		$this->writeCell( X7, _l(10), W7, H, '木造'        , CENTER);
		$this->writeCell( X7, _l(11), W7, H, 'S造'         , CENTER);
		$this->writeCell( X7, _l(12), W7, H, 'RC造等'      , CENTER);
		$this->writeCell( X7, _l(13), W7, H, '小計'        , CENTER);
		
		$this->writeCell( X7, _l(17), W7, H, '構造'        , CENTER);
		$this->writeCell( X7, _l(18), W7, H, '木造'        , CENTER);
		$this->writeCell( X7, _l(19), W7, H, 'S造'         , CENTER);
		$this->writeCell( X7, _l(20), W7, H, 'RC造等'      , CENTER);
		$this->writeCell( X7, _l(21), W7, H, '小計'        , CENTER);
		
		$this->writeCell( X7, _l(25), W7, H, '構造'        , CENTER);
		$this->writeCell( X7, _l(26), W7, H, 'RC造,＜2m'   , CENTER);
		$this->writeCell( X7, _l(27), W7, H, 'RC造,≦10m'  , CENTER);
		$this->writeCell( X7, _l(28), W7, H, 'RC造,＞10m'  , CENTER);
		$this->writeCell( X7, _l(29), W7, H, 'CB造等,＜2m' , CENTER);
		$this->writeCell( X7, _l(30), W7, H, 'CB造等,≦5m' , CENTER);
		$this->writeCell( X7, _l(31), W7, H, '広告塔'      , CENTER);
		$this->writeCell( X7, _l(32), W7, H, '鉄塔'        , CENTER);
		$this->writeCell( X7, _l(33), W7, H, 'その他'      , CENTER);
		$this->writeCell( X7, _l(34), W7, H, '小計'        , CENTER);
		
		$this->writeCell( X7, _l(36), W7, H, '合計'        , CENTER);
	}
}
