/**
 * 協会基本情報画面用JS
 *
 * $Author: murayama $
 * $Rev: 26 $
 * $Date: 2013-09-20 09:19:24 +0900 (2013/09/19 (木)) $
 */
$(document).ready(function(e) {
	// 更新ボタン
	$(document).on("click", "#submit", function (e) {
		// 通知バー消去
		Bar.clear();
		var association = $("#association");
		var comany = $("#comany");
		var zip1 = $("#zip1");
		var zip2 = $("#zip2");
		var address = $("#address");
		var tel = $("#tel");
		var fax = $("#fax");
		var tanto = $("#tanto");
		var email = $("#email");
		var syukka = $("#syukka");
		var bank_name1 = $("#bank_name1");
		var bank_name2 = $("#bank_name2");
		var bank_sybt = $("#bank_sybt");
		var bank_no = $("#bank_no");
		var bank_meigi = $("#bank_meigi");

		if ( $.trim(association.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["名称"]) );
		}
		if ( $.trim(comany.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["会社名"]) );
		}
		if ( $.trim(zip1.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["郵便番号1"]) );
		}
		else if (!$.isNumeric(zip1.val()) || zip1.val().length > 3) {
			Bar.add("error", getMessage("SR002", ["郵便番号1"]) );
		}

		if ( $.trim(zip2.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["郵便番号2"]) );
		}
		else if (!$.isNumeric(zip2.val()) || zip2.val().length > 4) {
			Bar.add("error", getMessage("SR002", ["郵便番号2"]) );
		}

		if ( $.trim(address.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["住所"]) );
		}
		if ( $.trim(tel.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["電話番号"]) );
		}
		if ( $.trim(fax.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["FAX番号"]) );
		}
		if ( $.trim(tanto.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["担当"]) );
		}
		if ( $.trim(email.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["E-Mail"]) );
		}
		if ( $.trim(syukka.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["パーツ出荷場所　会社名"]) );
		}
		if ( $.trim(bank_name1.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["銀行名"]) );
		}
		if ( $.trim(bank_name2.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["支店名"]) );
		}
		if ( $.trim(bank_sybt.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["預金種別"]) );
		}
		if ( $.trim(bank_no.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["口座番号"]) );
		}
		else if (!$.isNumeric(bank_no.val())) {
			Bar.add("error", getMessage("SR002", ["口座番号"]) );
		}

		if ( $.trim(bank_meigi.val()) == "" ) {
			Bar.add("error", getMessage("SR001", ["名義人"]) );
		}



		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 新規登録処理
		q( base + "office/society_register_check.json", {
			association:		association.val(),
			comany:				comany.val(),
			zip1:				zip1.val(),
			zip2:				zip2.val(),
			address:			address.val(),
			tel:				tel.val(),
			fax:				fax.val(),
			tanto:				tanto.val(),
			email:				email.val(),
			syukka:				syukka.val(),
			bank_name1:			bank_name1.val(),
			bank_name2:			bank_name2.val(),
			bank_sybt:			bank_sybt.val(),
			bank_no:			bank_no.val(),
			bank_meigi:			bank_meigi.val()
		} ).done( function (res) {
			if ( res.status == "OK" ) {
				window.location.href = res.url;
			}
			else {
				Bar.add("error", getMessage(res.code, res.args) );
				Bar.showAll();
			}
		});
	});

	// 更新ボタン
	$(document).on("click", "#update", function (e) {
		var association = $("#association");
		var comany = $("#comany");
		var zip1 = $("#zip1");
		var zip2 = $("#zip2");
		var address = $("#address");
		var tel = $("#tel");
		var fax = $("#fax");
		var tanto = $("#tanto");
		var email = $("#email");
		var syukka = $("#syukka");
		var bank_name1 = $("#bank_name1");
		var bank_name2 = $("#bank_name2");
		var bank_sybt = $("#bank_sybt");
		var bank_no = $("#bank_no");
		var bank_meigi = $("#bank_meigi");

		// 通知バー消去
		Bar.clear();

		// 新規登録処理
		q( base + "office/society_register_finish.json", {
			association:		association.val(),
			comany:				comany.val(),
			zip1:				zip1.val(),
			zip2:				zip2.val(),
			address:			address.val(),
			tel:				tel.val(),
			fax:				fax.val(),
			tanto:				tanto.val(),
			email:				email.val(),
			syukka:				syukka.val(),
			bank_name1:			bank_name1.val(),
			bank_name2:			bank_name2.val(),
			bank_sybt:			bank_sybt.val(),
			bank_no:			bank_no.val(),
			bank_meigi:			bank_meigi.val()
		} ).done( function (res) {
			if ( res.status == "OK" ) {
				window.location.href = res.url;
			}
			else {
				Bar.add("error", getMessage(res.code, res.args) );
				Bar.showAll();
			}
		});
	});

});

