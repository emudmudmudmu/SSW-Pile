/**
 * パーツ受注管理関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 196 $
 * $Date: 2013-10-08 20:55:58 +0900 (2013/10/08 (火)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		search();
	});

	// 明細・運賃編集による合計金額の変更
	$(document).on("keyup", ".quantity", function(e) {
		// 再計算
		_reCalcMeisai(this, e);
		_reCalcTotal(e);
	});
	$(document).on("keyup", "#shipping_fee", function(e) {
		// 再計算
		_reCalcTotal(e);
	});

	// 入力確認
	$(document).on("click", "#order_detail_button", function(e) {
		_input_check();
	});

	// 変更ボタン
	$(document).on("click", "#reg_button", function(e) {
		window.location.href = base + "office/" + $("#o_no").val() + "/order_detail_finish.html";
	});

	// (パーツ代金精算の)検索ボタン
	$(document).on("click", "#pay_search_button", function(e) {
		paySearch();
	});

	// CSVボタン
	$(document).on("click", "#csv_button", function (e) {
		var nen  = $("#bill_nen").text();
		var tuki = $("#bill_tuki").text();
		var ym = pad4(nen) + pad2(tuki);
		window.open(base + "office/clearing_" + ym + ".csv");
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
	// Cookieから値を設定する要素IDの配列
	var readOnlyFields =
	   [
		"order_no",
		"item_total",
		"tax",
		"rate",
		"total",
		"bill_no",
		"payment_date",
		"cancel_date",
	];
	// 明細分
	$(".quantity").each(function (idx, elem) {
		var line = idx + 1;
		readOnlyFields.push("price_" + line);
		readOnlyFields.push("sprice_" + line);
		readOnlyFields.push("subtotal_" + line);
	});
	// 値をCookieから設定
	for ( var i = 0; i < readOnlyFields.length; i++ ) {
		setValue(readOnlyFields[i], $.cookie("o_" + readOnlyFields[i]));
	}
}

function _reCalcMeisai(elem, event) {
	// 数値以外の場合は再計算しない
	if ( !(48 <= event.keyCode && event.keyCode <=  57)
	  && !(96 <= event.keyCode && event.keyCode <= 105)
	  && event.keyCode != 46
	  && event.keyCode != 8
	) {
		return;
	}

	// 行番号の取得
	var t = $(elem);
	var line = t.attr("id").replace(/[^0-9]/g, "");
	// 販売単価
	var sprice = $("#sprice_" + line).html().replace(/[^0-9]/g, "");
	// パーツ金額
	var subtotal = sprice * t.val();
	setValue("subtotal_" + line, "\\" + numberFormat(subtotal));
}

function _reCalcTotal(event) {
	// 数値以外の場合は再計算しない
	if ( !(48 <= event.keyCode && event.keyCode <=  57)
	  && !(96 <= event.keyCode && event.keyCode <= 105)
	  && event.keyCode != 46
	  && event.keyCode != 8
	) {
		return;
	}
	// パーツ金額合計
	var item_total = 0;
	$(".quantity").each(function (idx, elem) {
		item_total += parseInt( $("#subtotal_" + (idx + 1)).html().replace(/[^0-9]/g, "") );
	});
	setValue("item_total", "\\" + numberFormat(item_total));

	// 消費税
	var total = item_total + parseInt( _empty_to_zero($("#shipping_fee").val()) );
	var tax   = parseInt(Math.floor(total * parseInt( $("#rate").html() ) / 100));
	setValue("tax", "\\" + numberFormat(tax) );

	// 精算金額合計
	setValue("total", "\\" + numberFormat(total + tax) );
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
	// 整形(半角数値以外は除外)
	$("input[type=text]").each(function (idx, elem) {
		var t = $(this);
		var v = t.val();
		if ( !isset(v) ) {
			v = "";
		}
		t.val( v.replace(/[^0-9]/g, "") );
	});

	// 検索画面なので、例外的にフォーム送信を行なう
	$("#searchF").submit();
}

/**
 * (パーツ代金精算の)検索ボタン押下時の処理を行ないます。
 */
function paySearch() {
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

	var form = $("#searchF").get(0);

	if ( form.yyyy.value == "" ) {
		Bar.add("error", getMessage("MC001", ["精算年月（年）"]));
	}
	else if ( form.yyyy.value < 1000 ) {
		Bar.add("error", getMessage("MC011", ["精算年月（年）", "4桁の半角数字"]));
	}
	if ( form.mm.value == "" ) {
		Bar.add("error", getMessage("MC001", ["精算年月（月）"]));
	}
	else if ( form.mm.value < 1 || 12 < form.mm.value ) {
		Bar.add("error", getMessage("MC012", ["精算年月（月）", "1", "12"]));
	}

	if ( Bar.hasMessages() ) {
		Bar.showAll();
		return;
	}

	// 検索画面なので、例外的にフォーム送信を行なう
	$("#searchF").submit();
}

function _input_check() {
	Bar.clear();

	/* 入力値を集約 */
	var form = $("#regF").get(0);

	var data = {
		order_no         : $("#order_no").html(),
		order_company    : $("#order_company").val(),
		order_status     : $("#order_status").val(),
		order_date       : _concat_ymd("o"),
		shipping_company : $("#shipping_company").val(),
		shipping_name    : $("#shipping_name").val(),
		shipping_address : $("#shipping_address").val(),
		shipping_person  : $("#shipping_person").val(),
		email            : $("#email").val(),
		tel              : $("#tel").val(),
		fax              : $("#fax").val(),
		arrival_date     : _concat_ymd("d"),
		shipping_agent   : $("#shipping_agent").val(),
		shipping_fee     : $("#shipping_fee").val(),
		rate             : $("#rate").html(),
		shipping_date    : _concat_ymd("s"),
		delivery_date    : _concat_ymd("a"),
		agent_tel        : $("#agent_tel").val(),
		agent_inqno      : $("#agent_inqno").val(),
		bill_no          : $("#bill_no").html(),
		payment_date     : $("#payment_date").html(),
		cancel_date      : $("#cancel_date").html(),
		meisai           : []
	};
	// 明細
	$(".quantity").each(function (idx, elem) {
		var line = idx + 1;
		data.meisai.push({
			item_size    : $("#item_size_" + line).val(),
			quantity     : $("#quantity_" + line).val(),
			item_type    : $("#item_type_" + line).val(),
			price        : $("#price_" + line).html(),
			sprice       : $("#sprice_" + line).html().replace(/[^0-9]/g, "")
		});
	});


	/* 必須チェック */
	// 注文日
	if ( _empty( data.order_date ) || data.order_date == "warn" ) {
		Bar.add("error", getMessage("MC001", ["注文日"]));
	}
	// 納入場所
	if ( _empty( data.shipping_name ) ) {
		Bar.add("error", getMessage("MC001", ["納入場所"]));
	}
	// 納入先住所
	if ( _empty( data.shipping_address ) ) {
		Bar.add("error", getMessage("MC001", ["納入先住所"]));
	}
	// 納入希望日
	if ( _empty( data.arrival_date ) || data.arrival_date == "warn" ) {
		Bar.add("error", getMessage("MC001", ["納入希望日"]));
	}

	for (var i = 0; i < data.meisai.length; i++) {
		var prefix = "";
		if ( 1 < data.meisai.length ) {
			prefix = "受注内容（" + (i + 1) + "）の";
		}

		/* 必須チェック */
		// 先端翼径
		if ( _empty( data.meisai[i].item_size ) ) {
			Bar.add("error", getMessage("MC001", [prefix + "先端翼径"]));
		}
		// 本数
		if ( _empty( data.meisai[i].quantity ) ) {
			Bar.add("error", getMessage("MC001", [prefix + "本数"]));
		}
		/* 数値チェック */
		// 本数
		if ( !isNatural( data.meisai[i].quantity ) ) {
			Bar.add("error", getMessage("MC008", [prefix + "本数"]));
		}
	}

	/* 数値チェック */
	// 運送会社
	if ( _empty( data.shipping_agent ) ) {
		Bar.add("error", getMessage("MC001", ["運送会社"]));
	}
	// 運賃
	if ( _empty( data.shipping_fee ) ) {
		Bar.add("error", getMessage("MC001", ["運賃"]));
	}
	else if ( !isNatural( data.shipping_fee ) ) {
		Bar.add("error", getMessage("MC008", ["運賃"]));
	}

	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		q(base + "office/order_check.json", data).done(function (res) {
			if ( res.status == "OK" ) {
				window.location.href = base + "office/order_detail_check.html";
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

function _concat_ymd(prefix) {
	var form = $("#regF").get(0);
	if ( !_empty(form[prefix + "_yyyy"].value)
	  && !_empty(form[prefix + "_mm"].value)
	  && !_empty(form[prefix + "_dd"].value)
		) {
		return form[prefix + "_yyyy"].value + "-"
			 + form[prefix + "_mm"].value + "-"
		     + form[prefix + "_dd"].value;
	}
	if ( !_empty(form[prefix + "_yyyy"].value)
	  || !_empty(form[prefix + "_mm"].value)
	  || !_empty(form[prefix + "_dd"].value)
		) {
		// いずれかに入力がある場合はマークを付けて
		// 確認画面に警告を表示
		return "warn";
	}
	return "";
};
