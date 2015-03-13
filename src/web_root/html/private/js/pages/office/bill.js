/**
 * 請求管理関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 190 $
 * $Date: 2013-10-08 14:06:23 +0900 (2013/10/08 (火)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	
	// 個別で請求書発行後に「戻る」で戻った際は、
	// フォーム再送信を行なう
	var form = $("#searchF").get(0);
	if ( isset(window.reload_flg) && form ) {
		form.submit();
	}
	
	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		search();
	});
	
	// 請求処理ボタン
	$(document).on("click", "#print_button", function (e) {
		bill(this);
	});
	
	// 入金ボタン
	$(document).on("click", "#payment_button", function (e) {
		payment(this);
	});
	
	
	// 一括請求処理ボタン
	$(document).on("click", "#batch_button", function (e) {
		Bar.clear();
		var target = $(this).attr("data-target");
		if ( target == "" ) {
			Bar.add("warn", getMessage("MC301"));
			Bar.showAll();
			return;
		}
		if ( confirm(getMessage("MC302")) ) {
			q(base + "office/bill_combined.json", {target: target}).done(function (res) {
				if ( res.status == "OK" ) {
					// 再読み込み
					$("#searchF").submit();
				}
			});
		}
	});
	
	// 領収書印刷ボタン
	$(document).on("click", "#receipt_button", function (e) {
		window.open(base + "Receipt_" + pad5(key) + ".pdf");
	});
	
	// 変更ボタン
	$(document).on("click", "#reprint_button", function (e) {
		$("#tr_payment").hide();
		$("#tr_bill_date1").hide();
		$("#tr_bill_date2").fadeIn();
		$("#payment_button").hide();
		$("#receipt_button").hide();
		$("#print_button").removeClass("print").addClass("batch").addClass("reissue");
		Bar.add("info", getMessage("MC203", ["請求書発行日", "請求処理ボタン", "請求書"]));
		Bar.showAll();
	});
	
});


/**
 * 表示項目(入力フォームではないもの)に値を設定します。
 * JavaScriptで設定した値は「戻る」ボタン押下時に値が復元されないため、
 * Cookieを利用します。
 */
function setValue(id, value) {
	$.cookie("o_" + id, value);
	$("#" + id).html($.cookie("o_" + id));
}

function afterRendering() {
}

function _empty_to_zero(val) {
	if ( !isset(val) || val.replace(/[^0-9]/g, "") == "" ) {
		return 0;
	}
	return val.replace(/[^0-9]/g, "");
}

/**
 * 検索ボタン押下時の処理を行ないます。
 */
function search() {
	Bar.clear();
	
	// 整形(半角数値以外は除外)
	$("input[type=text]").each(function (idx, elem) {
		var t = $(this);
		var v = t.val();
		if ( !isset(v) ) {
			v = "";
		}
		t.val( v.replace(/[^0-9]/g, "") );
	});
	
	// 桁数・範囲チェック
	var form = $("#searchF").get(0);
	if ( form.yyyy.value != "" && form.yyyy.value < 1000 ) {
		Bar.add("error", getMessage("MC011", ["請求年月（年）", "4桁の半角数字"]));
	}
	if ( form.mm.value != "" ) {
		if ( form.yyyy.value == "" ) {
			Bar.add("error", getMessage("MC001", ["請求年月（月）に入力がありますので、請求年月（年）"]));
		}
		else if ( form.mm.value < 1 || 12 < form.mm.value ) {
			Bar.add("error", getMessage("MC012", ["請求年月（月）", "1", "12"]));
		}
	}
	
	if ( Bar.hasMessages() ) {
		Bar.showAll();
		return;
	}
	
	// 検索画面なので、例外的にフォーム送信を行なう
	$("#searchF").submit();
}

function bill(elem) {
	Bar.clear();
	
	var t = $(elem);
	
	
	// 請求書再発行処理
	if ( t.hasClass("reissue") ) {
		/* 入力値を集約 */
		var data = {
			bill_date     : _concat_ymd("b_"),
			bill_no       : key,
		};
		
		/* 必須チェック */
		// 請求書発行日
		if ( _empty( data.bill_date ) ) {
			Bar.add("error", getMessage("MC001", ["請求書発行日"]));
		}
		/* 数値チェック */
		else if ( data.bill_date == "not natural" ) {
			Bar.add("error", getMessage("MC008", ["請求書発行日"]));
		}
		
		if ( Bar.hasMessages() ) {
			Bar.showAll();
		}
		else {
			if ( confirm(getMessage("MC201", ["請求書の再発行"])) ) {
				q(base + "office/bill_reissue.json", data).done(function (res) {
					if ( res.status == "OK" ) {
						// 「検索結果へ戻る」のhistory.back() があるので、replace を使用する
						window.location.replace(base + "office/" + RESULT_TYPE_BILL + "/" + res.bill_no + "/request_result.html");
					}
					else {
						Bar.add("error", getMessage(res.code, res.args));
						Bar.showAll();
					}
				});
			}
		}
	}
	// 請求処理
	else if ( t.hasClass("batch") ) {
		if ( confirm(getMessage("MC201", ["請求情報の作成"])) ) {
			q(base + "office/" + type + "/" + key + "/create_bill.json").done(function (res) {
				if ( res.status == "OK" ) {
					// 「検索結果へ戻る」のhistory.back() があるので、replace を使用する
					window.location.replace(base + "office/" + RESULT_TYPE_BILL + "/" + res.bill_no + "/request_result.html");
				}
			});
		}
	}
	// 請求書印刷処理
	else {
		window.open(base + "Bill_" + pad5(key) + ".pdf");
	}
	
}

function payment(elem) {
	Bar.clear();
	
	/* 入力値を集約 */
	var data = {
		payment_date     : _concat_ymd(""),
		bill_no          : key,
	};
	
	/* 必須チェック */
	// 入金日
	if ( _empty( data.payment_date ) ) {
		Bar.add("error", getMessage("MC001", ["入金日"]));
	}
	/* 数値チェック */
	else if ( data.payment_date == "not natural" ) {
		Bar.add("error", getMessage("MC008", ["入金日"]));
	}
	
	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		if ( confirm(getMessage("MC201", ["入金処理"])) ) {
			q(base + "office/bill_payment.json", data).done(function (res) {
				if ( res.status == "OK" ) {
					window.location.reload();
				}
				else {
					Bar.add("error", getMessage(res.code, res.args));
					Bar.showAll();
				}
			});
		}
	}
};

function _empty(val) {
	return ( !isset(val) || $.trim(val) == "" );
};

function _concat_ymd(prefix) {
	var form = $("#regF").get(0);
	if ( !_empty(form[prefix + "yyyy"].value)
	  && !_empty(form[prefix + "mm"].value)
	  && !_empty(form[prefix + "dd"].value)
		) {
		
		if ( !isNatural(form[prefix + "yyyy"].value)
		  || !isNatural(form[prefix + "mm"].value)
		  || !isNatural(form[prefix + "dd"].value)) {
			return "not natural";
		}
		
		return form[prefix + "yyyy"].value + "-"
			 + form[prefix + "mm"].value + "-"
		     + form[prefix + "dd"].value;
	}
	return "";
};
