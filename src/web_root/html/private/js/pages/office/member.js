/**
 * 会員登録関連画面用JS
 *
 * $Author: murayama $
 * $Rev: 177 $
 * $Date: 2013-10-07 12:00:56 +0900 (2013/10/07 (月)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 理事会社用ログインアカウント・パーツ出荷担当用ログインアカウント
	// の表示・非表示
	var form = $("#regF").get(0);
	var divValue = 0;
	if ( form && form.division ) {
		for ( var i = 0; i < form.division.length; i++ ) {
			if ( form.division[i].checked ) {
				divValue = form.division[i].value;
			}
		}
	}
	entryChanged(divValue);


	$(document).on("change", "input[name=division]", function(e) {
		var t = $(this);
		if ( t.is(":checked") ) {
			entryChanged(t.val());
		}
	});

	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		var form = $("#searchF").get(0);
		var url = base + "office/";
		// 会社コード欄に入力が無い場合
		if ( _empty(form.company_code.value) ) {
			// 会社選択肢で選択があれば１件表示
			if ( 0 < form.company.selectedIndex ) {
				var code = form.company[form.company.selectedIndex].value;
				url += code + "/member_change.html";
			}
			// 無ければ全件表示
			else {
				url += "member_all.html";
			}
		}
		else {
			// 会社コードで前方一致検索
			url += "member_" + $.trim(form.company_code.value) + ".html";
		}
		// 画面遷移
		window.location.href = url;
	});

	// 登録ボタン
	$(document).on("click", "#reg_button", function (e) {
		window.location.href = base + "office/member_register_finish.html";
	});

	// 変更ボタン
	$(document).on("click", "#change_button", function (e) {
		window.location.href = base + "office/member_change_finish.html";
	});

	// 登録時・確認ボタン
	$(document).on("click", "#member_register_button", function (e) {
		_input_check(false);
	});

	// 編集時・確認ボタン
	$(document).on("click", "#member_change_button", function (e) {
		_input_check(true);
	});

});

function _input_check(all) {
	Bar.clear();

	/* 入力値を集約 */
	var form = $("#regF").get(0);
	// 会社区分
	var divValue = 0;
	for ( var i = 0; i < form.division.length; i++ ) {
		if ( form.division[i].checked ) {
			divValue = form.division[i].value;
		}
	}
	// 施工エリア
	var areas = [];
	for ( var i = 0; i < form.area.length; i++ ) {
		if ( form.area[i].checked ) {
			areas.push(form.area[i].value);
		}
	}

	var data = {
		company_code : form.company_code.value,
		company_type : divValue,
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
		member_id    : form.common_id.value,
		member_pswd  : form.common_pass.value,
		manager_id   : form.director_id.value,
		manager_pswd : form.director_pass.value,
		shipping_id  : form.parts_id.value,
		shipping_pswd: form.parts_pass.value
	};

	/* 必須チェック */

	// 会社コード
	if ( _empty( data.company_code ) ) {
		Bar.add("error", getMessage("MC001", ["会社コード"]));
	}
	else if ( data.company_code.length > 3 ) {
		Bar.add("error", getMessage("MC005", ["会社コード","3"]));
	}
	// 加入日
	if ( _empty( data.join_dateY ) || _empty( data.join_dateM ) || _empty( data.join_dateD ) ) {
		Bar.add("error", getMessage("MC001", ["加入日"]));
	}
	// 登録区分
	if ( data.company_type == 0 ) {
		Bar.add("error", getMessage("MC002", ["登録区分"]));
	}
	// 会社名
	if ( _empty( data.company_name ) ) {
		Bar.add("error", getMessage("MC001", ["会社名"]));
	}
	if ( all ) {
		// 代表者
		if ( _empty( data.ceo ) ) {
			Bar.add("error", getMessage("MC001", ["代表者"]));
		}
		// 郵便番号1
		if ( _empty( data.zip1 ) ) {
			Bar.add("error", getMessage("MC001", ["郵便番号1"]));
		}
		// 郵便番号2
		if ( _empty( data.zip2 ) ) {
			Bar.add("error", getMessage("MC001", ["郵便番号2"]));
		}
		// 住所
		if ( _empty( data.address ) ) {
			Bar.add("error", getMessage("MC001", ["住所"]));
		}
		// 電話番号
		if ( _empty( data.tel ) ) {
			Bar.add("error", getMessage("MC001", ["電話番号"]));
		}
		// FAX番号
		if ( _empty( data.fax ) ) {
			Bar.add("error", getMessage("MC001", ["FAX番号"]));
		}
	}
	// 連絡担当者(メール送信に必要)
	if ( _empty( data.tanto ) ) {
		Bar.add("error", getMessage("MC001", ["連絡担当者"]));
	}
	// E-Mail(メール送信に必要)
	if ( _empty( data.email ) ) {
		Bar.add("error", getMessage("MC001", ["E-Mail"]));
	}
	if ( all ) {
		// 施工エリア
		if ( data.areas.length == 0 ) {
			Bar.add("error", getMessage("MC002", ["施工エリア"]));
		}
	}


	// 一般アカウント
	if ( _empty( data.member_id ) ) {
		Bar.add("error", getMessage("MC001", ["【指定施工会社用ログインアカウント】のID"]));
	}
	if ( _empty( data.member_pswd ) ) {
		Bar.add("error", getMessage("MC001", ["【指定施工会社用ログインアカウント】のパスワード"]));
	}

	// 理事会社アカウント
	if ( divValue == COMPANY_MANAGER || divValue == COMPANY_JOINT ) {
		if ( _empty( data.manager_id ) ) {
			Bar.add("error", getMessage("MC001", ["【理事会社用ログインアカウント】のID"]));
		}
		if ( _empty( data.manager_pswd ) ) {
			Bar.add("error", getMessage("MC001", ["【理事会社用ログインアカウント】のパスワード"]));
		}
	}
	// パーツ出荷アカウント
	if ( divValue == COMPANY_MANAGER ) {
		if ( _empty( data.shipping_id ) ) {
			Bar.add("error", getMessage("MC001", ["【パーツ出荷担当用ログインアカウント】のID"]));
		}
		if ( _empty( data.shipping_pswd ) ) {
			Bar.add("error", getMessage("MC001", ["【パーツ出荷担当用ログインアカウント】のパスワード"]));
		}
	}

	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		q(base + "office/member_check/" + (all ? "no_check" : "check") + ".json", data).done(function (res) {
			if ( res.status == "OK" ) {
				window.location.href = base + "office/member_" + (all ? "change" : "register") + "_check.html";
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

function entryChanged(company_kbn) {
	if ( company_kbn == COMPANY_MANAGER ) {
		for ( var i = 1; i <= 6; i++ ) {
			$("#dr0" + i).show();
		}
	}
	else if (company_kbn == COMPANY_JOINT ) {
		for ( var i = 1; i <= 6; i++ ) {
			$("#dr0" + i).hide();
		}
		for ( var i = 1; i <= 3; i++ ) {
			$("#dr0" + i).show();
		}
	}
	else {
		for ( var i = 1; i <= 6; i++ ) {
			$("#dr0" + i).hide();
		}
	}
	return false;
};
