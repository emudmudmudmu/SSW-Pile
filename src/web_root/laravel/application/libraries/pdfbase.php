<?php

use Laravel\Bundle;


if (!defined('PORTRAIT')) { define('PORTRAIT', 'P');}
if (!defined('LANDSCAPE')) {define('LANDSCAPE', 'L');}
if (!defined('CENTER')) {   define('CENTER', 'C');}
if (!defined('LEFT')) {     define('LEFT', 'L');}
if (!defined('RIGHT')) {    define('RIGHT', 'R');}
if (!defined('STYLE_REGULAR')) {    define('STYLE_REGULAR', '');}
if (!defined('STYLE_BOLD')) {       define('STYLE_BOLD', 'B');}
if (!defined('STYLE_ITALIC')) {     define('STYLE_ITALIC', 'I');}
if (!defined('STYLE_UNDERLINE')) {  define('STYLE_UNDERLINE', 'U');}
if (!defined('FONT_IPA_MINCHO')) {  define('FONT_IPA_MINCHO'  , 1);}
if (!defined('FONT_IPA_MINCHO_P')) {define('FONT_IPA_MINCHO_P', 2);}
if (!defined('FONT_IPA_GOTHIC')) {  define('FONT_IPA_GOTHIC'  , 3);}
if (!defined('FONT_IPA_GOTHIC_P')) {define('FONT_IPA_GOTHIC_P', 4);}
if (!defined('HEIGHT_9')) {define('HEIGHT_9', 3.68);}
if (!defined('HEIGHT_12')) {define('HEIGHT_12', 4.9);}
if (!defined('HEIGHT_14')) {define('HEIGHT_14', 5.72);}
if (!defined('HEIGHT_16')) {define('HEIGHT_16', 7.36);}


class PdfBase {

	protected  $pdf;
	private $debug;
	private $_tplIdx;
	private $IPA_Mincho;
	private $IPA_Mincho_P;
	private $IPA_Gothic;
	private $IPA_Gothic_P;

	function __construct() {
		$this->pdf = new FPDI(PORTRAIT, 'mm', 'A4', true, 'UTF-8', false, false);
		$pdf = &$this->pdf;
		
		$this->IPA_Gothic   = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipag.ttf');
		$this->IPA_Gothic_P = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipagp.ttf');
		$this->IPA_Mincho   = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipam.ttf');
		$this->IPA_Mincho_P = $pdf->addTTFfont(path('app') . 'libraries/fonts/ipamp.ttf');
		
		
		$pdf->SetCreator('SSW-Pile工法協会Webサイト');
//		$pdf->SetAuthor('SSW-Pile工法協会');
//		$pdf->SetTitle('請求書');
//		$pdf->SetSubject('保管用');
//		$pdf->SetKeywords('SSW-Pile, 請求書');
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
		
		// TODO このあたりは設定ファイルに外だしする？
		$pdf->setColor('text', 0, 0, 128);
		// デバッグ時の矩形描画
		$pdf->SetDrawColor(255, 0, 0);
	}
	
	public function setTemplate($dot_separated_str, $orientation = PORTRAIT) {
		$path = explode('.', $dot_separated_str);
		$path = implode('/', $path);
		
		$pdf = &$this->pdf;
		
		$pdf->setSourceFile(path('app') . "views/{$path}.pdf");
		$pdf->AddPage($orientation);
		$this->_tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($this->_tplIdx);
	}
	
	public function setDebug($debug) {
		$this->debug = $debug;
	}
	
	public function setFont($font_family, $font_size = 16, $style = STYLE_REGULAR) {
		$font = $this->IPA_Mincho_P;
		switch ( $font_family ) {
			case FONT_IPA_MINCHO:
				$font = $this->IPA_Mincho;
				break;
			case FONT_IPA_MINCHO_P:
				$font = $this->IPA_Mincho_P;
				break;
			case FONT_IPA_GOTHIC:
				$font = $this->IPA_Gothic;
				break;
			case FONT_IPA_GOTHIC_P:
				$font = $this->IPA_Gothic_P;
				break;
		}
		
		$this->pdf->SetFont($font, $style, $font_size);
	}
	
	public function output($name) {
		return $this->pdf->Output($name, 'I');
	}
	
	protected function writeCell($x, $y, $w, $h, $text, $align = LEFT, $ln = 0) {
		$this->pdf->SetXY($x, $y);
		$fill = FALSE;
		$link = '';
		$stretch = 1;
		$border = $this->debug ? 1 : 0;
		$this->pdf->Cell($w, $h, $text , $border, $ln, $align, $fill, $link, $stretch);
	}
	
	protected function convertToHeisei($date) {
		$dates = explode('-', $date);
		$y = $dates[0] - 1988;
		$m = intval($dates[1]);
		$d = intval($dates[2]);
		return "平成{$y}年{$m}月{$d}日";
	}
	
	protected function convertToH($date) {
		$dates = explode('-', $date);
		$y = $dates[0] - 1988;
		$m = $dates[1];
		$d = $dates[2];
		return "H{$y}.{$m}.{$d}";
	}
}
