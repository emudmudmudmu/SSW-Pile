/**
 * 消費税設定画面用JS
 *
 * $Author: murayama $
 * $Rev: 26 $
 * $Date: 2013-09-20 09:19:24 +0900 (2013/09/19 (木)) $
 */
$(document).ready(function(e) {
	// 新規登録ボタン
	$(document).on("click", "#new_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var start_date = $("#start_date");
		var end_date = $("#end_date");
		var rate = $("#rate");

		if ( $.trim(start_date.val()) == "" ) {
			Bar.add("error", getMessage("ST001", ["開始日"]) );
		}

		if ( $.trim(rate.val()) == "" ) {
			Bar.add("error", getMessage("ST001", ["税率"]) );
		}
		else {
			if (!$.isNumeric(rate.val())) {
				Bar.add("error", getMessage("ST003") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("ST020")) ){
			return false;
		}

		// 新規登録処理
		q( base + "office/system_tax_new.json", {
			start_date:			start_date.val(),
			end_date  :			end_date.val(),
			rate:				rate.val()
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
	$(document).on("click", "#update_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var ctax_id = document.getElementById('ctax_id').value;

		var start_date = $("#start_date_"+ctax_id);
		var end_date = $("#end_date_"+ctax_id);
		var rate = $("#rate_"+ctax_id);

		if ( $.trim(start_date.val()) == "" ) {
			Bar.add("error", getMessage("ST001", ["開始日"]) );
		}

		if ( $.trim(rate.val()) == "" ) {
			Bar.add("error", getMessage("ST001", ["税率"]) );
		}
		else {
			if (!$.isNumeric(rate.val())) {
				Bar.add("error", getMessage("ST003") );
			}
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("ST021")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_tax_update.json", {
			ctax_id:			ctax_id,
			start_date:			start_date.val(),
			end_date  :			end_date.val(),
			rate:				rate.val()
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

	// 削除ボタン
	$(document).on("click", "#delete_button", function (e) {
		// 通知バー消去
		Bar.clear();
		var ctax_id = document.getElementById('ctax_id').value;

		// 確認メッセージ
		if (!window.confirm(getMessage("ST022")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_tax_delete.json", {
			ctax_id:			ctax_id
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

