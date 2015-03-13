/**
 * 単価設定画面用JS
 *
 * $Author: murayama $
 * $Rev: 26 $
 * $Date: 2013-09-19 09:19:24 +0900 (2013/09/19 (木)) $
 */
$(document).ready(function(e) {
	// 工法使用料単価設定　新規登録ボタン
	$(document).on("click", "#licensefees_new_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var l_start_date = $("#l_start_date");
		var l_end_date = $("#l_end_date");
		var l_licensefees_price1 = $("#l_licensefees_price1");
		var l_licensefees_price2 = $("#l_licensefees_price2");
		var l_licensefees_price3 = $("#l_licensefees_price3");

		if ( $.trim(l_start_date.val()) == "" ) {
			Bar.add("error", getMessage("SU001", ["適用開始日"]) );
		}

		if ( $.trim(l_licensefees_price1.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price1.val())) {
				Bar.add("error", getMessage("SU003") );
			}
		}
		if ( $.trim(l_licensefees_price2.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price2.val())) {
				Bar.add("error", getMessage("SU004") );
			}
		}

		if ( $.trim(l_licensefees_price3.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price3.val())) {
				Bar.add("error", getMessage("SU005") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SU020")) ){
			return false;
		}

		// 新規登録処理
		q( base + "office/system_unit_licensefees_new.json", {
			l_start_date:			l_start_date.val(),
			l_end_date  :			l_end_date.val(),
			l_licensefees_price1:	l_licensefees_price1.val(),
			l_licensefees_price2:	l_licensefees_price2.val(),
			l_licensefees_price3:	l_licensefees_price3.val()
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

	// 工法使用料単価設定　更新ボタン
	$(document).on("click", "#licensefees_update_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var licensefee_id = document.getElementById('licensefee_id').value;

		var l_start_date = $("#l_start_date_"+licensefee_id);
		var l_end_date = $("#l_end_date_"+licensefee_id);
		var l_licensefees_price1 = $("#l_licensefees_price1_"+licensefee_id);
		var l_licensefees_price2 = $("#l_licensefees_price2_"+licensefee_id);
		var l_licensefees_price3 = $("#l_licensefees_price3_"+licensefee_id);

		if ( $.trim(l_start_date.val()) == "" ) {
			Bar.add("error", getMessage("SU001", ["適用開始日"]) );
		}

		if ( $.trim(l_licensefees_price1.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price1.val())) {
				Bar.add("error", getMessage("SU003") );
			}
		}
		if ( $.trim(l_licensefees_price2.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price2.val())) {
				Bar.add("error", getMessage("SU004") );
			}
		}

		if ( $.trim(l_licensefees_price3.val()) != "" ) {
			if (!$.isNumeric(l_licensefees_price3.val())) {
				Bar.add("error", getMessage("SU005") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SU021")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_unit_licensefees_update.json", {
			licensefee_id:			licensefee_id,
			l_start_date:			l_start_date.val(),
			l_end_date  :			l_end_date.val(),
			l_licensefees_price1:	l_licensefees_price1.val(),
			l_licensefees_price2:	l_licensefees_price2.val(),
			l_licensefees_price3:	l_licensefees_price3.val()
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

	// 工法使用料単価設定　削除ボタン
	$(document).on("click", "#licensefees_delete_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var licensefee_id = document.getElementById('licensefee_id').value;

		// 確認メッセージ
		if (!window.confirm(getMessage("SU022")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_unit_licensefees_delete.json", {
			licensefee_id:			licensefee_id
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



	// パーツ単価設定　新規登録ボタン
	$(document).on("click", "#item_new_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var i_start_date = $("#i_start_date");
		var i_end_date = $("#i_end_date");
		var i_item_size = $("#i_item_size");
		var i_item_price1 = $("#i_item_price1");
		var i_item_sprice1 = $("#i_item_sprice1");
		var i_item_price2 = $("#i_item_price2");
		var i_item_sprice2 = $("#i_item_sprice2");

		if ( $.trim(i_start_date.val()) == "" ) {
			Bar.add("error", getMessage("SU061", ["適用開始日"]) );
		}

		if ( $.trim(i_item_price1.val()) != "" ) {
			if (!$.isNumeric(i_item_price1.val())) {
				Bar.add("error", getMessage("SU064") );
			}
		}
		if ( $.trim(i_item_sprice1.val()) != "" ) {
			if (!$.isNumeric(i_item_sprice1.val())) {
				Bar.add("error", getMessage("SU065") );
			}
		}
		if ( $.trim(i_item_price2.val()) != "" ) {
			if (!$.isNumeric(i_item_price2.val())) {
				Bar.add("error", getMessage("SU066") );
			}
		}
		if ( $.trim(i_item_sprice2.val()) != "" ) {
			if (!$.isNumeric(i_item_sprice2.val())) {
				Bar.add("error", getMessage("SU067") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SU080")) ){
			return false;
		}

		// 新規登録処理
		q( base + "office/system_unit_item_new.json", {
			i_start_date:			i_start_date.val(),
			i_end_date  :			i_end_date.val(),
			i_item_size:			i_item_size.val(),
			i_item_price1:			i_item_price1.val(),
			i_item_sprice1:			i_item_sprice1.val(),
			i_item_price2:			i_item_price2.val(),
			i_item_sprice2:			i_item_sprice2.val()
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

	// パーツ単価設定　更新ボタン
	$(document).on("click", "#item_update_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var item_id = document.getElementById('item_id').value;

		var i_start_date = $("#i_start_date_"+item_id);
		var i_end_date = $("#i_end_date_"+item_id);
		var i_item_size = $("#i_item_size_"+item_id);
		var i_item_price1 = $("#i_item_price1_"+item_id);
		var i_item_sprice1 = $("#i_item_sprice1_"+item_id);
		var i_item_price2 = $("#i_item_price2_"+item_id);
		var i_item_sprice2 = $("#i_item_sprice2_"+item_id);

		if ( $.trim(i_start_date.val()) == "" ) {
			Bar.add("error", getMessage("SU061", ["適用開始日"]) );
		}

		if ( $.trim(i_item_price1.val()) != "" ) {
			if (!$.isNumeric(i_item_price1.val())) {
				Bar.add("error", getMessage("SU064") );
			}
		}
		if ( $.trim(i_item_sprice1.val()) != "" ) {
			if (!$.isNumeric(i_item_sprice1.val())) {
				Bar.add("error", getMessage("SU065") );
			}
		}
		if ( $.trim(i_item_price2.val()) != "" ) {
			if (!$.isNumeric(i_item_price2.val())) {
				Bar.add("error", getMessage("SU066") );
			}
		}
		if ( $.trim(i_item_sprice2.val()) != "" ) {
			if (!$.isNumeric(i_item_sprice2.val())) {
				Bar.add("error", getMessage("SU067") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SU081")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_unit_item_update.json", {
			item_id:				item_id,
			i_start_date:			i_start_date.val(),
			i_end_date  :			i_end_date.val(),
			i_item_size:			i_item_size.val(),
			i_item_price1:			i_item_price1.val(),
			i_item_sprice1:			i_item_sprice1.val(),
			i_item_price2:			i_item_price2.val(),
			i_item_sprice2:			i_item_sprice2.val()
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

	// パーツ単価設定　削除ボタン
	$(document).on("click", "#item_delete_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var item_id = document.getElementById('item_id').value;

		// 確認メッセージ
		if (!window.confirm(getMessage("SU082")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_unit_item_delete.json", {
			item_id:				item_id
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

