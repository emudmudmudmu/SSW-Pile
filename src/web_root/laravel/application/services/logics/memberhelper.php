<?php
namespace Logics;

use Laravel\Session;
use Laravel\Response;

class MemberHelper {

	/**
	 * 会員情報編集画面に値を設定する為のJavaScriptを定義します。
	 *
	 * @param array $input sswm_userテーブルのレコードを表わす配列
	 * @param string $form_id 画面上のform要素のid属性
	 * @param bool $with_account ログインアカウント・登録区分(会社区分)の入力フォームが無い場合はFALSEに設定
	 */
	public static function setValuesByScript($input, $form_id = 'regF', $with_account = TRUE) {
		$areas = implode(',', $input['areas']);
		$script = <<< SCRIPT

var form = $("#{$form_id}").get(0);
if ( form ) {
	form.company_code.value     = "{$input['company_code']}";
	form.company.value          = "{$input['company_name']}";
	form.representative.value   = "{$input['ceo']}";
	form.zip01.value            = "{$input['zip1']}";
	form.zip02.value            = "{$input['zip2']}";
	form.address.value          = "{$input['address']}";
	form.tel.value              = "{$input['tel']}";
	form.fax.value              = "{$input['fax']}";
	form.person.value           = "{$input['tanto']}";
	form.email.value            = "{$input['email']}";
	form.yyyy.value             = "{$input['join_dateY']}";
	form.mm.value               = "{$input['join_dateM']}";
	form.dd.value               = "{$input['join_dateD']}";
	var areas                   = [{$areas}];
	form.common_id.value        = "{$input['member_id']}";
	form.common_pass.value      = "{$input['member_pswd']}";
	for ( var i = 0; i < form.area.length; i++ ) {
	    if ( in_array( form.area[i].value, areas )  ) {
	        form.area[i].checked = true;
	    }
	}
}
SCRIPT;
		if ( $with_account ) {
			$script .= <<< SCRIPT
if ( form ) {
	form.director_id.value      = "{$input['manager_id']}";
	form.director_pass.value    = "{$input['manager_pswd']}";
	form.parts_id.value         = "{$input['shipping_id']}";
	form.parts_pass.value       = "{$input['shipping_pswd']}";
	var divValue                = {$input['company_type']};
	for ( var i = 0; i < 3; i++ ) {
	    if ( form.division[i].value == divValue ) {
	        form.division[i].checked = true;
	    }
	}
}
SCRIPT;
		}


		Session::put(KEY_SCRIPT, $script);
	}


	/**
	 * エラーメッセージコードMC003に該当するAjaxレスポンスを生成して返します。
	 *
	 * ※MC003 = "{0}「{1}」は既に使われています。別の{2}を入力してください。"
	 *
	 * @param mixed $val    {1}に該当する値。エラーの対象となっているもの
	 * @param mixed $label1 {0}に該当する値。主に画面上の項目名
	 * @param mixed $label2 {2}に該当する値。$label1 と同じか、その省略名称
	 * @return \Laravel\Response Responseオブジェクト
	 */
	public static function createAjaxNGResponseWithMC003($val, $label1, $label2) {
		return Response::json(array(
			'status' => 'NG',
			'code'   => 'MC003',
			'args'   => array(
				$label1,
				$val,
				$label2
			)
		));
	}



}