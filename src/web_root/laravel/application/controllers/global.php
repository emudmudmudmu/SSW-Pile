<?php

use Laravel\Session;

/**
 * 全画面共通部品生成用コントローラー
 *
 * $Author: mizoguchi $
 * $Rev: 115 $
 * $Date: 2013-09-30 21:45:27 +0900 (2013/09/30 (月)) $
 */
class Global_Controller extends Base_Controller {

	/**
	 * js/definitions.js を生成するメソッドです。
	 */
	public function action_definitions() {
		$base = URL::base();

		// ブラウザ判定
		$user_agent = Request::server('HTTP_USER_AGENT');
		$matches = array();
		$matchesOS = array();
		$oldBrowser = 'false';
		$lte6 = 'false';
		$ie = 'false';
		$ie8 = 'false';
		$xp = 'false';
		if ( preg_match('/MSIE ([0-9\.]*)/', $user_agent, $matches) ) {
			$ie = 'true';
			if ( doubleval($matches[1]) < 8.0 ) {
				$oldBrowser = 'true';
			}
			if ( floor(doubleval($matches[1])) == 8 ) {
				$ie8 = 'true';
			}
			if ( doubleval($matches[1]) < 7.0 ) {
				$lte6 = 'true';
			}
		}
		if ( preg_match('/Windows NT ([0-9\.]*)/', $user_agent, $matchesOS) ) {
			if ( 0 < count($matches) && doubleval($matches[1]) < 6.0 ) {
				$xp = 'true';
			}
		}
		
		// 会社区分
		$companies = array(COMPANY_MANAGER, COMPANY_JOINT, COMPANY_MEMBER);
		
		// 物件ステータス
		$status = array(CONSTRUCT_ESTIMATE, CONSTRUCT_ORDERED, CONSTRUCT_COMPLETE);
		
		// 検索結果タイプ
		$types = array(RESULT_TYPE_BILL, RESULT_TYPE_CONST, RESULT_TYPE_ORDER);
		
		$content = <<< JS
// ベースURL
var base = "{$base}";

// IE7以下のブラウザかどうか
var oldBrowser = {$oldBrowser};

// IE6以下のブラウザかどうか
var lte6 = {$lte6};

// IEかどうか
var ie = {$ie};

// IE8かどうか
var ie8 = {$ie8};

// XPかどうか
var xp = {$xp};

// 会社区分
var COMPANY_MANAGER = {$companies[0]};
var COMPANY_JOINT   = {$companies[1]};
var COMPANY_MEMBER  = {$companies[2]};

// 物件ステータス
var CONSTRUCT_ESTIMATE = {$status[0]};
var CONSTRUCT_ORDERED  = {$status[1]};
var CONSTRUCT_COMPLETE = {$status[2]};

// 検索結果タイプ
var RESULT_TYPE_BILL  = {$types[0]};
var RESULT_TYPE_CONST = {$types[1]};
var RESULT_TYPE_ORDER = {$types[2]};

$(document).ready(function () {
JS;
		// 画面メッセージの付加
		$has_message = false;
		$code = '';
		if ( Session::has(KEY_MSG_CODE) ) {
			$code = 'getMessage("' . Session::get(KEY_MSG_CODE) . '"';
			if ( Session::has(KEY_ARGS) ) {
				$code .= ', ["' . implode('", "', Session::get(KEY_ARGS)) . '"]';
				Session::forget(KEY_ARGS);
			}
			$code .= ')';
			Session::forget(KEY_MSG_CODE);
		}
		if ( Session::has(KEY_ERROR) ) {
			$message = $code ? $code : '"' . Session::get(KEY_ERROR) . '"';
			$content .= "Bar.add(\"error\", {$message});" . LF;
			Session::forget(KEY_ERROR);
			$has_message = true;
		}
		else if ( Session::has(KEY_WARN) ) {
			$message = $code ? $code : '"' . Session::get(KEY_WARN) . '"';
			$content .= "Bar.add(\"warn\", {$message});" . LF;
			Session::forget(KEY_WARN);
			$has_message = true;
		}
		else if ( Session::has(KEY_INFO) ) {
			$message = $code ? $code : '"' . Session::get(KEY_INFO) . '"';
			$content .= "Bar.add(\"info\", {$message});" . LF;
			Session::forget(KEY_INFO);
			$has_message = true;
		}
		else if ( Session::has(KEY_SUCCESS) ) {
			$message = $code ? $code : '"' . Session::get(KEY_SUCCESS) . '"';
			$content .= "Bar.add(\"success\", {$message});" . LF;
			Session::forget(KEY_SUCCESS);
			$has_message = true;
		}
		if ( $has_message ) {
			$content .= "Bar.showAll();";
			$content .= "reRendering();";
		}

		if ( Session::has(KEY_SCRIPT) ) {
			$content .= Session::get(KEY_SCRIPT);
			Session::forget(KEY_SCRIPT);
		}
		$content .= "});";
		
		$response = Response::make($content);
		$response->header('Content-Type', 'text/javascript; charset="UTF-8"');

		return $response;
	}

}
