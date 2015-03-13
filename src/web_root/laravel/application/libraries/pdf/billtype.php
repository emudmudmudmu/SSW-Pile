<?php

use Laravel\Bundle;
use Masters\BasicInfo;


/**
 * 請求書タイプのPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_BillType extends Pdf_Common {
	
	public function __construct() {
		parent::__construct();
		// テンプレート固定
		$this->setTemplate('pdf.bill_type');
		
		// テンプレートを指定した後、この定義が有効になる
		$this->setSenderName(BasicInfo::getAssociationName());
		$this->setZip(BasicInfo::getZip());
		$this->setAddress(BasicInfo::getAddress());
		$this->setTel(BasicInfo::getTel());
		$this->setFax(BasicInfo::getFax());
		$this->setPerson(BasicInfo::getPerson());
	}
	

	/**
	 * 補足1を設定します。
	 *
	 * @param string $text 補足1
	 */
	public function setAppendix1($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(115, 90, 70, HEIGHT_9, $text);
	}
	
	
	/**
	 * 補足2を設定します。
	 *
	 * @param string $text 補足2
	 */
	public function setAppendix2($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(115, 94, 70, HEIGHT_9, $text);
	}
	
	
	/**
	 * 本文(一行)を設定します。
	 */
	public function setBody($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(25, 95, 80, HEIGHT_9, $text);
	}
	
	
}
