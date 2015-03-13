<?php
namespace Logics;

use Laravel\Session;
use Laravel\Log;
use Masters\Companies;
use Masters\BasicInfo;
use Utils\MailService;

class OrderHelper {
	
	/**
	 * 受注情報編集画面に値を設定する為のJavaScriptを定義します。
	 *
	 * @param stdClass $order    sswd_order, sswd_order_meisaiテーブルのレコードを表わすオブジェクト
	 * @param string   $form_id  画面上のform要素のid属性
	 * @param bool     $shipping 「伝票発行/出荷処理」画面での出力の場合TRUE
	 */
	public static function setValuesByScript($order, $form_id = 'regF', $shipping = FALSE) {
		
		/* 日付整形 */
		list($order_date_y, $order_date_m, $order_date_d) = self::_split_date($order->order_date, TRUE);
		list($arrival_date_y, $arrival_date_m, $arrival_date_d) = self::_split_date($order->arrival_date);
		list($shipping_date_y, $shipping_date_m, $shipping_date_d) = self::_split_date($order->shipping_date);
		list($delivery_date_y, $delivery_date_m, $delivery_date_d) = self::_split_date($order->delivery_date);
		
		list($payment_date_y, $payment_date_m, $payment_date_d) = self::_split_date($order->bill->payment_date);
		$payment_date = ($payment_date_y ? "{$payment_date_y}年 {$payment_date_m}月 {$arrival_date_d}日" : '');
		
		list($cancel_date_y, $cancel_date_m, $cancel_date_d) = self::_split_date($order->cancel_date, TRUE);
		$cancel_date = ($cancel_date_y ? "{$cancel_date_y}年 {$cancel_date_m}月 {$arrival_date_d}日" : '');
		
		/* 注文明細 */
		$loop = '';
		$i = 1;
		$item_total = 0;
		foreach ( $order->meisai as $meisai ) {
			/* 数値整形 */
			// PHP・JS両方でのエスケープなので、\は二重にエスケープ
			$price = '\\\\' . number_format($meisai->item_price);
			$sprice = '\\\\' . number_format($meisai->item_sprice);
			$subtotal = '\\\\' . number_format($meisai->item_sprice * $meisai->quantity);
			
			// パーツ金額合計
			$item_total += ($meisai->item_sprice * $meisai->quantity);
			
			$loop .= <<< LOOP

	$("#item_size_{$i}").val("{$meisai->item_size}");
	$("#quantity_{$i}").val("{$meisai->quantity}");
	$("#item_type_{$i}").val("{$meisai->item_type}");
	setValue("price_{$i}", "{$price}");
	setValue("sprice_{$i}", "{$sprice}");
	setValue("subtotal_{$i}", "{$subtotal}");
	
LOOP;
			$i++;
		}
		
		// パーツ金額合計
		$item_total_text = '\\\\' . number_format($item_total);
		
		// 消費税計算
		$tax = intval(floor( ($item_total + $order->shipping_fee) * $order->rate / 100 ));
		$tax_text = '\\\\' . number_format($tax);
		
		// 合計金額
		$total = $item_total + $order->shipping_fee + $tax;
		$total_text = '\\\\' . number_format($total);
		
		$script = <<< SCRIPT

var form = $("#{$form_id}").get(0);
if ( form ) {
	setValue("order_no", "{$order->order_no}");
	$("#shipping_company").val("{$order->shipping_company}");
	$("#shipping_name").val("{$order->shipping_name}");
	$("#shipping_address").val("{$order->shipping_address}");
	$("#shipping_person").val("{$order->shipping_person}");
	$("#email").val("{$order->email}");
	$("#tel").val("{$order->tel}");
	$("#fax").val("{$order->fax}");
{$loop}
	$("#shipping_agent").val("{$order->shipping_agent}");
	$("#shipping_fee").val("{$order->shipping_fee}");
	setValue("tax", "{$tax_text}");
	setValue("rate", "{$order->rate}");
	setValue("item_total", "{$item_total_text}");
	setValue("total", "{$total_text}");

	$("#shipping_date_y").val("{$shipping_date_y}");
	$("#shipping_date_m").val("{$shipping_date_m}");
	$("#shipping_date_d").val("{$shipping_date_d}");

	$("#delivery_date_y").val("{$delivery_date_y}");
	$("#delivery_date_m").val("{$delivery_date_m}");
	$("#delivery_date_d").val("{$delivery_date_d}");
	
	$("#agent_tel").val("{$order->agent_tel}");
	$("#agent_inqno").val("{$order->agent_inqno}");
}
SCRIPT;
		// 以下、受注編集の場合のみ
		if ( !$shipping ) {
			$script .= <<< SCRIPT

if ( form ) {
	$("#order_company").val(pad3("{$order->order_company}"));
	$("#order_status").val("{$order->order_status}");
	$("#order_date_y").val("{$order_date_y}");
	$("#order_date_m").val("{$order_date_m}");
	$("#order_date_d").val("{$order_date_d}");
	$("#arrival_date_y").val("{$arrival_date_y}");
	$("#arrival_date_m").val("{$arrival_date_m}");
	$("#arrival_date_d").val("{$arrival_date_d}");
	
	$("#bill_no").html("{$order->bill->bill_no}");
	setValue("payment_date", "{$payment_date}");
	setValue("cancel_date", "{$cancel_date}");
}
SCRIPT;
		}
		// 以下、出荷編集のみ
		else {
			$company = Companies::get($order->order_company);
			$order_status = getLabelBy('order_long', $order->order_status);
			
			$script .= <<< SCRIPT
			
	setValue("order_company", "{$company->company_name}");
	setValue("order_status", "{$order_status}");
	setValue("order_date_y", "{$order_date_y}");
	setValue("order_date_m", "{$order_date_m}");
	setValue("order_date_d", "{$order_date_d}");
	setValue("arrival_date_y", "{$arrival_date_y}");
	setValue("arrival_date_m", "{$arrival_date_m}");
	setValue("arrival_date_d", "{$arrival_date_d}");
			
SCRIPT;
		}
		
		
		Session::put(KEY_SCRIPT, $script);
	}
	
	private static function _split_date($date_string, $with_time = FALSE) {
		if ( $date_string ) {
			$format = 'Y-m-d' . ($with_time ? ' H:i:s' : '');
			$date = date_create_from_format($format, $date_string);
			$y = date_format($date, 'Y');
			$m = date_format($date, 'm');
			$d = date_format($date, 'd');
			return array($y, $m, $d);
		}
		return array('', '', '');
	}
	
	
	/**
	 * 出荷完了メールを送信します。
	 * 
	 * @param int $order_no 注文番号
	 */
	public static function sendShippedMail($order_no) {
		$order = \Orders::get($order_no);
		$order->total = intval(floor(($order->subtotal + $order->shipping_fee) * (100 + $order->rate) / 100));
		// 注文内容欄に使用する消費税なので、運賃を含まない
		$order->tax = intval(floor($order->subtotal * $order->rate / 100));
		
		$cc_name = BasicInfo::getShippingCompanyName();
		$cc = BasicInfo::getShippingCompanyEmail();
		
		if ( $cc_name ) {
			$cc = "{$cc_name} 出荷担当 <{$cc}>";
		}
		
		$to = "{$order->shipping_company} {$order->shipping_person}様 <{$order->email}>";
		
		$subject = '[SSW-Pile工法協会] パーツ出荷のお知らせ';
		try {
			MailService::send_mail($to, $subject, 'mail.shipped', get_object_vars($order), '', $cc);
		}
		catch (Exception $ex){
			Log::error(var_export($ex, TRUE));
			Session::put(KEY_WARN, TRUE);
			Session::put(KEY_MSG_CODE, "MZ007");
			$mail = Config::get('email.bcc');
			Session::put(KEY_ARGS, array($mail, $mail, $mail));
		}
		
	}
}