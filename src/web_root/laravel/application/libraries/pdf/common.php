<?php

/**
 * PDFテンプレートの共通箇所を実装するクラスです。
 * 
 * @author mizoguchi
 */
class Pdf_Common extends PdfBase {
	
	private $title;
	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 標題を設定します。
	 */
	public function setTitle($title) {
		$this->title;
		$this->pdf->SetTitle($title);
		
		$this->setFont(FONT_IPA_MINCHO_P, 16);
		$this->writeCell(25, 30, 80, HEIGHT_16, $title);
	}
	
	/**
	 * 宛名を設定します。
	 */
	public function setDear($dear) {
		$this->SetFont(FONT_IPA_MINCHO_P, 14);
		$this->writeCell(25, 49.2, 80, HEIGHT_14, $dear);
	}
	
	/**
	 * 発行日を設定します。
	 * 
	 * @param string $date YYYY-mm-dd形式の文字列
	 */
	function setDate($date) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(135, 30, 40, HEIGHT_9, $this->convertToHeisei($date));
	}
	
	
	/**
	 * No.を設定します。
	 * 
	 * @param int $no 番号
	 */
	public function setNo($no) {
		$this->pdf->SetSubject("{$this->title} No. {$no}");
		
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(160, 34.6, 25.7, HEIGHT_9, "No. {$no}", RIGHT);
	}
	
	
	/**
	 * 差出人を設定します。
	 * 
	 * @param string $sender 差出人
	 */
	public function setSenderName($sender) {
		$this->pdf->SetAuthor($sender);
		
		$this->setFont(FONT_IPA_MINCHO_P, 14, STYLE_ITALIC); // IPAフォントでイタリックは効かない
		$this->writeCell(115, 60, 69.2, HEIGHT_14, $sender);
	}
	
	
	/**
	 * 郵便番号を設定します。
	 *
	 * @param string $zip 郵便番号
	 */
	public function setZip($zip) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(115, 66, 70, HEIGHT_9, $zip);
	}
	
	
	/**
	 * 住所を設定します。
	 *
	 * @param string $address 住所
	 */
	public function setAddress($address) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(115, 70, 70, HEIGHT_9, $address);
	}
	
	
	/**
	 * 電話番号を設定します。
	 *
	 * @param string $tel 電話番号
	 */
	public function setTel($tel) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 74, 50, HEIGHT_9, "TEL：{$tel}");
	}
	
	
	/**
	 * FAX番号を設定します。
	 *
	 * @param string $fax FAX番号
	 */
	public function setFax($fax) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 78, 50, HEIGHT_9, "FAX：{$fax}");
	}
	
	/**
	 * 担当者を設定します。
	 *
	 * @param string $person 担当者
	 */
	public function setPerson($person) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 82, 50, HEIGHT_9, "担当：{$person}");
	}
	
	
	/**
	 * フッタ1の見出しを設定します。
	 */
	public function setFooter1Title($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(115, 215, 20, 5, $text);
	}
	
	
	/**
	 * フッタ2の見出しを設定します。
	 */
	public function setFooter2Title($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(115, 220, 20, 5, $text);
	}
	
	
	/**
	 * フッタ3の見出しを設定します。
	 */
	public function setFooter3Title($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(115, 225, 20, 5, $text);
	}
	
	
	/**
	 * フッタ1を設定します。
	 */
	public function setFooter1($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 215, 50, 5, $text, RIGHT);
	}
	
	
	/**
	 * フッタ2を設定します。
	 */
	public function setFooter2($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 220, 50, 5, $text, RIGHT);
	}
	
	
	/**
	 * フッタ3を設定します。
	 */
	public function setFooter3($text) {
		$this->setFont(FONT_IPA_MINCHO, 9);
		$this->writeCell(135, 225, 50, 5, $text, RIGHT);
	}
	
	
	/**
	 * 末尾備考欄のラベルを設定します。
	 * 
	 * @param string $text ラベル
	 */
	public function setNoteLabel($text) {
		$this->setFont(FONT_IPA_MINCHO_P, 9);
		$this->writeCell(25, 240, 20, HEIGHT_9, $text);
	}
	
	
	/**
	 * 小計を設定します。
	 * 
	 * @param int $subtotal 小計金額
	 */
	public function setSubTotal($subtotal) {
		$this->setFooter1('\\' . number_format($subtotal));
	}
	
	
	/**
	 * 消費税額を設定します。
	 * 
	 * @param int $tax 消費税額
	 */
	public function setTax($tax) {
		$this->setFooter2('\\' . number_format($tax));
	}
	
	
	/**
	 * 合計を設定します。
	 * 
	 * @param int $total 合計
	 */
	public function setTotal($total) {
		$this->setFooter3('\\' . number_format($total));
	}
}
