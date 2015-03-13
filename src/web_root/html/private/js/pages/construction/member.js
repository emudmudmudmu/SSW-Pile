/**
 * 会員登録関連画面用JS
 * (office/member.jsを流用)
 *
 * $Author: mizoguchi $
 * $Rev: 61 $
 * $Date: 2013-09-24 18:09:05 +0900 (2013/09/24 (火)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {

	// 編集時ボタン
	$(document).on("click", "#member_change_button", function (e) {
		_input_check();
	});

	// 更新ボタン
	$(document).on("click", "#reg_button", function (e) {
		window.location.href = base + "construction/member_change_finish.html";
	});
});

function _input_check() {
	Bar.clear();

	/* 入力値を集約 */
	var form = $("#regF").get(0);

	// 施工エリア
	var areas = [];
	for ( var i = 0; i < form.area.length; i++ ) {
		if ( form.area[i].checked ) {
			areas.push(form.area[i].value);
		}
	}

	var data = {
		company_code : form.company_code.value,
		areas        : areas,
		company_name : form.company.value,
		ceo          : form.representative.value,
		zip1         : form.zip01.value,
		zip2         : form.zip02.value,
		address      : form.address.value,
		tel          : form.tel.value,
		fax          : form.fax.value,
		tanto        : form.person.value,
		email        : form.email.value,
		join_dateY   : form.yyyy.value,
		join_dateM   : form.mm.value,
		join_dateD   : form.dd.value,
		password     : form.password.value,
		password2    : form.password2.value,
		member_id    : form.common_id.value,
		member_pswd  : form.common_pass.value,
		company_type : form.company_type.value
	};

	/* 必須チェック */

	// 会社コード
	if ( _empty( data.company_code ) ) {
		Bar.add("error", getMessage("CC001", ["会社コード"]));
	}
	// 加入日
	if ( _empty( data.join_dateY ) || _empty( data.join_dateM ) || _empty( data.join_dateD ) ) {
		Bar.add("error", getMessage("CC001", ["加入日"]));
	}
	// 会社名
	if ( _empty( data.company_name ) ) {
		Bar.add("error", getMessage("CC001", ["会社名"]));
	}
	// 代表者
	if ( _empty( data.ceo ) ) {
		Bar.add("error", getMessage("CC001", ["代表者"]));
	}
	// 郵便番号1
	if ( _empty( data.zip1 ) ) {
		Bar.add("error", getMessage("CC001", ["郵便番号1"]));
	}
	// 郵便番号2
	if ( _empty( data.zip2 ) ) {
		Bar.add("error", getMessage("CC001", ["郵便番号2"]));
	}
	// 住所
	if ( _empty( data.address ) ) {
		Bar.add("error", getMessage("CC001", ["住所"]));
	}
	// 電話番号
	if ( _empty( data.tel ) ) {
		Bar.add("error", getMessage("CC001", ["電話番号"]));
	}
	// FAX番号
	if ( _empty( data.fax ) ) {
		Bar.add("error", getMessage("CC001", ["FAX番号"]));
	}
	// 連絡担当者(メール送信に必要)
	if ( _empty( data.tanto ) ) {
		Bar.add("error", getMessage("CC001", ["連絡担当者"]));
	}
	// E-Mail(メール送信に必要)
	if ( _empty( data.email ) ) {
		Bar.add("error", getMessage("CC001", ["E-Mail"]));
	}
	// 施工エリア
	if ( data.areas.length == 0 ) {
		Bar.add("error", getMessage("CC002", ["施工エリア"]));
	}
	// パスワード
	if ( _empty( data.password ) ) {
		Bar.add("error", getMessage("CC001", ["パスワード"]));
	}
	// パスワード(再入力)
	if ( _empty( data.password2 ) ) {
		Bar.add("error", getMessage("CC001", ["パスワード（再入力）"]));
	}
	// パスワードチェック
	if ( data.password != data.password2 ) {
		Bar.add("error", getMessage("CC003"));
	}

	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}

	else {
		q(base + "construction/member_check.json", data).done(function (res) {
			if ( res.status == "OK" ) {
				window.location.href = base + "construction/member_change_check.html";
			}
			else {
				Bar.add("error", getMessage(res.code, res.args));
				Bar.showAll();
			}
		});
	}
};

function _empty(val) {
	return ( !isset(val) || $.trim(val) == "" );
};


