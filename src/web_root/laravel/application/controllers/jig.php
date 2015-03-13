<?php

use Laravel\Session;
use Laravel\View;
use Laravel\Response;
use Laravel\Event;
use Laravel\Input;
use Laravel\Hash;

/**
 * システム管理者用治具コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 184 $
 * $Date: 2013-10-08 02:09:59 +0900 (2013/10/08 (火)) $
 */
class Jig_Controller extends Base_Controller {

	private $allows = array(
		'114.173.74.247',
		'210.199.18.214'
	);

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 治具画面
	 */
	public function action_index() {
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->allows) ) {
			return Event::first('404');
		}

		return View::make('common.jig');
	}

	public function action_hash() {
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->allows) ) {
			return Event::first('404');
		}

		$input = Input::get('input');
		$output = Hash::make($input);

		return Response::json(array(
			'output' => $output
		));

	}
	
	
	public function action_pdf() {
		$mongon = Input::get('mongon');
		$font = Input::get('font');
		$font_size = Input::get('size');
		$pdf = new Pdf_BillType();
		$pdf->setDebug(true);
		$pdf->setTitle('請求書');
		$pdf->setDear($mongon);
		return Response::make($pdf->output("test_001.pdf"), 200, array('Content-Type' => 'application/pdf'));
	}
}
