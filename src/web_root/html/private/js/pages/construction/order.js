/**
 * パーツ発注関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 136 $
 * $Date: 2013-10-03 04:05:55 +0900 (2013/10/03 (木)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 単価の初期表示
	$(".item").each(function (idx, elm) {
		_set_sprice(this);
	});
	
	// 先端翼径選択肢
	$(document).on("change", ".item", function (e) {
		_set_sprice(this);
	});
	
	// 注文確認ボタン
	$(document).on("click", "#order_check_button", function (e) {
		_input_check();
	});
	
	// 発注ボタン
	$(document).on("click", "#order_button", function (e) {
		window.location.href = base + "construction/parts_order_finish.html";
	});
});

/**
 * 単価に販売価格を設定します。
 *
 * @param elem 先端翼径選択肢オブジェクト
 */
function _set_sprice(elem) {
	var t = $(elem);
	// 行数を取得
	var line = t.attr("id").replace(/item/, "");
	
	// 設定を取得
	var item = null;
	for ( var i = 0; i < items.length; i++ ) {
		if ( t.val() == items[i].id ) {
			item = items[i];
			break;
		}
	}
	// 単価を表示
	var target = $("#sprice" + line);
	if ( item != null ) {
		target.html("\\" + numberFormat(item.sprice) );
	}
	else {
		target.html("");
	}
}

function _concat_ymd(prefix) {
	var form = $("#regF").get(0);
	if ( !_empty(form[prefix + "day_yyyy"].value)
	  && !_empty(form[prefix + "day_mm"].value)
	  && !_empty(form[prefix + "day_dd"].value)
		) {
		return form[prefix + "day_yyyy"].value + "-"
			 + form[prefix + "day_mm"].value + "-"
		     + form[prefix + "day_dd"].value;
	}
	return "";
}



function _input_check() {
	Bar.clear();
	
	/* 入力値を集約 */
	var form = $("#regF").get(0);
	
	var orders = [];
	$(".quantity").each(function (idx, elem) {
		var t = $(this);
		if ( $.trim(t.val()) != "" ) {
			orders.push({
				item_id  : $("#item" + (idx + 1)).val(),
				item_type: $("#item_type" + (idx + 1)).val(),
				quantity : $.trim(t.val())
			});
		}
	});
	
	var data = {
		shipping_company    : form.shipping_company.value,
		shipping_name       : form.shipping_name.value,
		shipping_address    : form.shipping_address.value,
		shipping_person     : form.shipping_person.value,
		email               : form.email.value,
		tel                 : form.tel.value,
		fax                 : form.fax.value,
		arrival_date        : _concat_ymd(""),
		"orders"            : orders
	};
	
	
	/* 必須チェック */
	// 納入会社
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
	// 納入先担当者
	if ( _empty( data.shipping_person ) ) {
		Bar.add("error", getMessage("MC001", ["納入先担当者"]));
	}
	// 納入先メールアドレス
	if ( _empty( data.email ) ) {
		Bar.add("error", getMessage("MC001", ["納入先メールアドレス"]));
	}
	// 納入先電話番号
	if ( _empty( data.tel ) ) {
		Bar.add("error", getMessage("MC001", ["電話番号"]));
	}
	// 納入希望日
	if ( _empty( data.arrival_date ) ) {
		Bar.add("error", getMessage("MC001", ["納入希望日"]));
	}
	// 本数
	if ( data.orders.length == 0 ) {
		Bar.add("error", getMessage("MC001", ["本数"]));
	}
	
	/* 数値チェック */
	// 本数
	for (var i = 0; i < data.orders.length; i++) {
		if ( !isNatural(data.orders[i].quantity) ) {
			Bar.add("error", getMessage("MC008", ["本数"]));
			break;
		}
	}
	
	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		q(base + "construction/order_check.json", data).done(function (res) {
			if ( res.status == "OK" ) {
				window.location.href = base + "construction/parts_order_check.html";
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

