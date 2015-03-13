<?php
namespace Logics;

use Laravel\Session;
use Laravel\Log;
use Masters\Companies;
use Laravel\URL;

class BillHelper {
	
	public static function createBill($result) {
		$company = Companies::get($result['bill_company']);
		$result['company_name'] = $company->company_name;
		$result['meisai_type'] = RESULT_TYPE_ORDER;
		$result['payment_dateY'] = parseDateY($result['payment_date']);
		$result['payment_dateM'] = parseDateM($result['payment_date']);
		$result['payment_dateD'] = parseDateD($result['payment_date']);
		
		$order = '';
		
		$meisais = array();
		$i = 1;
		foreach ( $result['meisai'] as $meisai ) {
			// 配列に変換
			$add = get_object_vars($meisai);
				
			// パーツ受注に関する請求情報の場合
			if ( $add['bill_type'] != HINMOKU_LICENSE ) {
				$order = \Orders::get($add['order_no']);
			}
			
			$add['meisai_date_md'] = intval(parseDateM($add['meisai_date'])) . '/' . intval(parseDateD($add['meisai_date']));
			if ( $add['bill_type'] == HINMOKU_LICENSE ) {
				$result['meisai_type'] = RESULT_TYPE_CONST;
				$add['quantity'] = "({$add['quantity']}本)";
				$add['shipping_name'] = $result['company_name'];
				$add['url'] = '';
			}
			else if ( $add['bill_type'] == HINMOKU_ITEM ) {
				$add['quantity'] .= '本';
				$add['shipping_name'] = $order->shipping_name;
				$add['url']           = URL::to("office/{$add['order_no']}/order_detail.html#meisai{$i}");
			}
			else {
				$add['shipping_name'] = $order->shipping_name;
				$add['url']           = URL::to("office/{$add['order_no']}/order_detail.html#shipping");
			}
			$meisais[] = $add;
			$i++;
		}
		$result['meisai'] = $meisais;
		
		return $result;
	}
}