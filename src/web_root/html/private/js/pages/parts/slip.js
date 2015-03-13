/**
 * パーツ出荷担当関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 104 $
 * $Date: 2013-09-29 22:06:59 +0900 (2013/09/29 (日)) $
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
		window.location.href = base + "parts/" + $("#o_no").val() + "/slip_detail_finish.html";
	});
	
	// 納品書ダウンロードボタン
	$(document).on("click", "#delivery_slip", function(e) {
		window.open( base + "Slip_" + pad5(key) + ".pdf" );
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

	// 請求金額合計
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

function _input_check() {
	Bar.clear();

	/* 入力値を集約 */
	var data = {
		order_no         : $("#order_no").html(),
		shipping_company : $("#shipping_company").val(),
		shipping_name    : $("#shipping_name").val(),
		shipping_address : $("#shipping_address").val(),
		shipping_person  : $("#shipping_person").val(),
		email            : $("#email").val(),
		tel              : $("#tel").val(),
		fax              : $("#fax").val(),
		shipping_agent   : $("#shipping_agent").val(),
		shipping_fee     : $("#shipping_fee").val(),
		shipping_date    : _concat_ymd("s"),
		delivery_date    : _concat_ymd("a"),
		agent_tel        : $("#agent_tel").val(),
		agent_inqno      : $("#agent_inqno").val(),
		meisai           : []
	};
	// 明細
	$(".quantity").each(function (idx, elem) {
		var line = idx + 1;
		data.meisai.push({
			item_size    : $("#item_size_" + line).val(),
			quantity     : $("#quantity_" + line).val(),
			item_type    : $("#item_type_" + line).val(),
		});
	});


	/* 必須チェック */
	// 納入会社(メール送信に使用)
	if ( _empty( data.shipping_company ) ) {
		Bar.add("error", getMessage("MC001", ["納入会社"]));
	}
	// 納入場所
	if ( _empty( data.shipping_name ) ) {
		Bar.add("error", getMessage("MC001", ["納入場所"]));
	}
	// 納入先住所
	if ( _empty( data.shipping_address ) ) {
		Bar.add("error", getMessage("MC001", ["納入先住所"]));
	}
	// 納入先担当者(メール送信に使用)
	if ( _empty( data.shipping_person ) ) {
		Bar.add("error", getMessage("MC001", ["納入先担当者"]));
	}
	// 納入先メールアドレス(メール送信に使用)
	if ( _empty( data.shipping_person ) ) {
		Bar.add("error", getMessage("MC001", ["納入先メールアドレス"]));
	}
	// 運送会社
	if ( _empty( data.shipping_agent ) ) {
		Bar.add("error", getMessage("MC001", ["運送会社"]));
	}
	// 運賃
	if ( _empty( data.shipping_fee ) ) {
		Bar.add("error", getMessage("MC001", ["運賃"]));
	}
	// 出荷日
	if ( _empty( data.shipping_date ) || data.shipping_date == "warn" ) {
		Bar.add("error", getMessage("MC001", ["出荷日"]));
	}
	// 到着予定日
	if ( _empty( data.delivery_date ) || data.delivery_date == "warn" ) {
		Bar.add("error", getMessage("MC001", ["到着予定日"]));
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
	// 運賃
	if ( !isNatural( data.shipping_fee ) ) {
		Bar.add("error", getMessage("MC008", ["運賃"]));
	}

	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		q(base + "parts/slip_check.json", data).done(function (res) {
			if ( res.status == "OK" ) {
				if ( confirm(getMessage("MC204", ["出荷済に変更"])) ) {
					window.location.href = base + "parts/slip_detail_finish.html";
				}
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
