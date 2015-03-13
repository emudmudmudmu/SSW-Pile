<?php

use Laravel\Bundle;
use Masters\BasicInfo;


/**
 * 請求書のPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_Bill extends Pdf_BillType {
	
	private $title;
	
	public function __construct() {
		parent::__construct();
		$this->setTitle('請求書');
		$this->setBody('下記のとおりご請求申し上げます。');
		$this->setHeaderTitle('税込合計金額');
		$this->setAppendix1(BasicInfo::getBank() 
				. ' ' . BasicInfo::getBankBranch() 
				. ' ' . BasicInfo::getAccountType() 
				. ' 口座番号 ' . BasicInfo::getAccountNumber());
		$this->setAppendix2('口座名義 ' . BasicInfo::getAccountName());
		$this->setDetailHeader(array('月', '日', '品　名', '数 量', '単　価', '金　額', '摘　要'));
		$this->setFooter1Title('小　　計');
		$this->setFooter2Title('消費税等');
		$this->setFooter3Title('合　　計');
		$this->setNoteLabel('備考');
	}
	
	
	/**
	 * ヘッダの見出しを設定します。
	 */
	public function setHeaderTitle($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 14, STYLE_BOLD); // IPAフォントでBOLDは効かない
		$this->writeCell(25, 105, 60, 9, $text);
	}
	
	
	/**
	 * 合計請求金額を設定します。
	 * 
	 * @param int $total 合計請求金額
	 */
	public function setBillingAmount($total) {
		$this->setFont(FONT_IPA_MINCHO_P, 14, STYLE_BOLD); // IPAフォントでBOLDは効かない
		$this->writeCell(75, 105, 40, 9, '\\' . number_format($total), RIGHT);
	}
	
	
	/**
	 * 明細行の標題を設定します。
	 * 
	 * @param array $headers 標題の配列（7要素固定）
	 */
	public function setDetailHeader($headers) {
		$this->setFont(FONT_IPA_MINCHO, 10);
		$this->writeCell( 25, 114,  5, 6, $headers[0], CENTER);
		$this->writeCell( 30, 114,  5, 6, $headers[1], CENTER);
		$this->writeCell( 35, 114, 65, 6, $headers[2], CENTER);
		$this->writeCell(100, 114, 15, 6, $headers[3], CENTER);
		$this->writeCell(115, 114, 20, 6, $headers[4], CENTER);
		$this->writeCell(135, 114, 25, 6, $headers[5], CENTER);
		$this->writeCell(160, 114, 25, 6, $headers[6], CENTER);
	}
	
	
	/**
	 * 明細各行を表わす配列を設定します。
	 * 
	 * @param array $meisai 明細行の配列、各明細行は6要素（最初の要素は日付YYYY-mm-dd形式）
	 */
	public function setDetails($meisai) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$yAxis = 120;
		$i = 1;
		foreach ( $meisai as $detail ) {
			// 最初の要素は日付
			$m = intval(parseDateM($detail[0]));
			$d = intval(parseDateD($detail[0]));
			
			$this->writeCell( 25, $yAxis,  5, 5, $m, RIGHT);
			$this->writeCell( 30, $yAxis,  5, 5, $d, RIGHT);
			$this->writeCell( 35, $yAxis, 65, 5, "{$i}. {$detail[1]}", LEFT);
			$this->writeCell(100, $yAxis, 15, 5, ( $detail[2] ? number_format($detail[2]) : '-'), RIGHT);
			$this->writeCell(115, $yAxis, 20, 5, number_format($detail[3]), RIGHT);
			$this->writeCell(135, $yAxis, 25, 5, '\\' . number_format($detail[4]), RIGHT);
			$this->writeCell(160, $yAxis, 25, 5, $detail[5], LEFT);
			
			$yAxis += 5;
			$i++;
		}
	}
}
