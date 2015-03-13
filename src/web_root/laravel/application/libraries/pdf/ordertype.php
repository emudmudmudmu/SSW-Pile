<?php

use Laravel\Bundle;
use Masters\BasicInfo;


/**
 * 注文書タイプのPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_OrderType extends Pdf_Common {
	
	public function __construct() {
		parent::__construct();
		// テンプレート固定
		$this->setTemplate('pdf.order_type');
		
		// テンプレートを指定した後、この定義が有効になる
		$this->setSenderName(BasicInfo::getAssociationName());
		$this->setZip(BasicInfo::getZip());
		$this->setAddress(BasicInfo::getAddress());
		$this->setTel(BasicInfo::getTel());
		$this->setFax(BasicInfo::getFax());
		$this->setPerson(BasicInfo::getPerson());
		
		$this->setDetailHeader(array('品　名', '数 量', '単　価', '金　額'));
	}
	
	
	/**
	 * 左上リード箇所に文言を設定します。
	 * 
	 * @param int $line 行番号
	 * @param string $text 表示する内容
	 */
	public function setLead($line, $text) {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(25, 85 + (($line - 1) * 4.5), 80, 4.5, $text);
	}
	
	
	/**
	 * 左上リード箇所に文言を設定します。
	 * １段下げた位置から開始します。
	 * 
	 * @param int $line 行番号
	 * @param string $text 表示する内容
	 */
	public function setIndentedLead($line, $text) {
		
		if ( preg_match('#[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}#', $text) ) {
			$text = $this->convertToHeisei($text);
		}
		
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(50, 85 + (($line - 1) * 4.5), 55, 4.5, $text);
	}
	
	
	/**
	 * 最後につける文言を設定します。
	 * 
	 * @param string $text 文言
	 */
	public function setRegards($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(115, 234, 70, HEIGHT_9, $text);
	}
	
	
	/**
	 * 明細行の標題を設定します。
	 * 
	 * @param array $headers 標題の配列（4要素固定）
	 */
	public function setDetailHeader($headers) {
		$this->setFont(FONT_IPA_MINCHO, 10);
		$this->writeCell( 25, 114, 90, 6, $headers[0], CENTER);
		$this->writeCell(115, 114, 15, 6, $headers[1], CENTER);
		$this->writeCell(130, 114, 25, 6, $headers[2], CENTER);
		$this->writeCell(155, 114, 30, 6, $headers[3], CENTER);
	}
	
	
	/**
	 * 明細各行を表わす配列を設定します。
	 * 
	 * @param array $meisai 明細行の配列、各明細行は4要素
	 */
	public function setDetails($meisai) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$yAxis = 120;
		$i = 1;
		foreach ( $meisai as $detail ) {
			$this->writeCell( 25, $yAxis, 90, 5, "{$i}. {$detail[0]}", LEFT);
			$this->writeCell(115, $yAxis, 15, 5, ( $detail[1] ? number_format($detail[1]) : '-'), RIGHT);
			$this->writeCell(130, $yAxis, 25, 5, number_format($detail[2]), RIGHT);
			$this->writeCell(155, $yAxis, 30, 5, '\\' . number_format($detail[3]), RIGHT);
			
			$yAxis += 5;
			$i++;
		}
	}
}
