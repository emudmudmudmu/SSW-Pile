<?php

use Laravel\Bundle;


/**
 * 領収書のPDFファイルを生成します。
 * 
 * @author mizoguchi
 */
class Pdf_Receipt extends Pdf_OrderType {
	
	private $title;
	
	public function __construct() {
		parent::__construct();
		$this->setTitle('領収書');
		$this->setFooter1Title('小　　計');
		$this->setFooter2Title('消費税等');
		$this->setFooter3Title('合　　計');
		$this->setNoteLabel('備考');
		
		$this->setLead(1, 'ご住所');
		$this->setLead(2, 'ご担当者');
		$this->setLead(3, 'メールアドレス');
		$this->setLead(4, 'TEL');
		$this->setLead(5, 'FAX');
		
		$this->setRegards('今後ともよろしくお願いいたします。');
	}
}
