<?php

use Laravel\Bundle;


if (!defined('LANDSCAPE')) {define('LANDSCAPE', 'L');}
if (!defined('CENTER')) {define('CENTER', 'C');}
if (!defined('LEFT')) {define('LEFT', 'L');}
if (!defined('RIGHT')) {define('RIGHT', 'R');}

class PdfTest {

	private $pdf;
	private $_tplIdx;
	private $_w;
	private $_h;
	private $_ln;
	private $IPA_Mincho;
	private $IPA_Mincho_P;
	private $IPA_Gothic;
	private $IPA_Gothic_P;

	function __construct() {
		$this->pdf = new FPDI('P', 'mm', 'A4', true, 'UTF-8', false, false);
		$pdf = &$this->pdf;
		
		$this->IPA_Gothic   = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipag.ttf');
		$this->IPA_Gothic_P = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipagp.ttf');
		$this->IPA_Mincho   = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipam.ttf');
		$this->IPA_Mincho_P = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipamp.ttf');
		
		
		$pdf->SetCreator('SSW-Pile物件管理システム');
		$pdf->SetAuthor('SSW-Pile工法協会');
		$pdf->SetTitle('請求書');
		$pdf->SetSubject('保管用');
		$pdf->SetKeywords('SSW-Pile, 請求書');
		$pdf->setPrintHeader(FALSE);
		$pdf->setPrintFooter(FALSE);
		$pdf->SetMargins(0, 0);
		
		$pdf->SetAutoPageBreak(TRUE, 0);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$l = array();
		$l['a_meta_charset'] = 'UTF-8';
		$l['a_meta_dir'] = 'ltr';
		$l['a_meta_language'] = 'ja';
		$l['w_page'] = 'ページ';
		$pdf->setLanguageArray($l);
		
		// set array for viewer preferences
		$preferences = array(
				'HideToolbar' => true,
				'HideMenubar' => false,
				'HideWindowUI' => false,
				'FitWindow' => true,
				'CenterWindow' => true,
				'DisplayDocTitle' => true,
				'NonFullScreenPageMode' => 'UseNone', // UseNone, UseOutlines, UseThumbs, UseOC
				'ViewArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
				'ViewClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
				'PrintArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
				'PrintClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
				'PrintScaling' => 'None', // None, AppDefault
				'Duplex' => 'Simplex', // Simplex, DuplexFlipShortEdge, DuplexFlipLongEdge
				'PickTrayByPDFSize' => false,
				'PrintPageRange' => null,
				'NumCopies' => 1
		);
		
		// Check the example n. 60 for advanced page settings
		
		// set pdf viewer preferences
		$pdf->setViewerPreferences($preferences);
		
		

		$pdf->SetDrawColor(255, 0, 0);
	}
	
	public function setTemplate($dot_separated_str) {
		$path = explode('.', $dot_separated_str);
		$path = implode('/', $path);
		
		$pdf = &$this->pdf;
		
		$pdf->setSourceFile(path('app') . "views/{$path}.pdf");
		$pdf->AddPage(LANDSCAPE);
		$this->_tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($this->_tplIdx);
	}
	
	public function setText($text) {
		$this->set_cell(10, 10, 120, 12);
		$this->write_cell($text);
	}
	
	public function setFont($index, $font_size = 16) {
		$font = $this->IPA_Mincho_P;
		switch ( $index ) {
			case 1:
				$font = $this->IPA_Mincho;
				break;
			case 2:
				$font = $this->IPA_Mincho_P;
				break;
			case 3:
				$font = $this->IPA_Gothic;
				break;
			case 4:
				$font = $this->IPA_Gothic_P;
				break;
		}
		
		$this->pdf->SetFont($font, '', $font_size);
	}
	
	public function output($name) {
		return $this->pdf->Output($name, 'I');
	}
	
	private function set_cell($x, $y, $w, $h, $ln = 0) {
		$this->pdf->SetXY($x, $y);
		$this->_w = $w;
		$this->_h = $h;
		$this->_ln = $ln;
	}

	private function write_cell($text, $align = LEFT) {
		$fill = FALSE;
		$link = '';
		$stretch = 1;
		$this->pdf->Cell($this->_w, $this->_h, $text , 1, $this->_ln, $align, $fill, $link, $stretch);
	}

}
