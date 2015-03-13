<?php

use Laravel\Bundle;


/**
 * 受注伝票兼出荷指示書のPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_Inst extends Pdf_OrderType {
	
	private $title;
	
	public function __construct() {
		parent::__construct();
		$this->setTitle('受注伝票兼出荷指示書');
		$this->setFooter1Title('小　　計');
		$this->setFooter2Title('消費税等');
		$this->setFooter3Title('合　　計');
		$this->setNoteLabel('備考');
		
		$this->setLead(0, '納入期日');
		$this->setLead(1, '納入会社');
		$this->setLead(2, '納入場所');
		$this->setLead(3, '');
		$this->setLead(4, '納入担当');
		$this->setLead(5, 'メールアドレス');
		$this->setLastLeadLeftTitle('TEL');
		$this->setLastLeadRightTitle('FAX');
	}
	
	
	/**
	 * 左上リードの最終行、左側のタイトルを設定します。
	 * 
	 * @param string $text タイトル
	 */
	public function setLastLeadLeftTitle($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(25, 107.5, 40, 4.5, $text);
	}
	
	
	/**
	 * 左上リードの最終行、左側の値を設定します。
	 * 
	 * @param string $text 値
	 */
	public function setLastLeadLeftValue($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(25, 107.5, 40, 4.5, $text, CENTER);
	}
	
	
	/**
	 * 左上リードの最終行、右側のタイトルを設定します。
	 * 
	 * @param string $text タイトル
	 */
	public function setLastLeadRightTitle($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(65, 107.5, 40, 4.5, $text);
	}
	
	
	/**
	 * 左上リードの最終行、右側の値を設定します。
	 * 
	 * @param string $text 値
	 */
	public function setLastLeadRightValue($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 8);
		$this->writeCell(65, 107.5, 40, 4.5, $text, CENTER);
	}
	
}
