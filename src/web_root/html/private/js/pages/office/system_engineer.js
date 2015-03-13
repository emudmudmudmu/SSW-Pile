/**
 * 施工管理技術者設定画面用JS
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

		var name = $("#name");
		var company_code = $("#company_code");
		var certificated = $("#certificated");

		if ( $.trim(name.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["氏名"]) );
		}

		if ( $.trim(company_code.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["所属"]) );
		}

		if ( $.trim(certificated.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["認定日"]) );
		}


		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SE020")) ){
			return false;
		}

		// 新規登録処理
		q( base + "office/system_engineer_new.json", {
			name:				name.val(),
			company_code:		company_code.val(),
			certificated:		certificated.val()
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
		var no = document.getElementById('no').value;

		var name = $("#name_"+no);
		var company_code = $("#company_code_"+no);
		var certificated = $("#certificated_"+no);

		if ( $.trim(name.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["氏名"]) );
		}

		if ( $.trim(company_code.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["所属"]) );
		}

		if ( $.trim(certificated.val()) == "" ) {
			Bar.add("error", getMessage("SE001", ["認定日"]) );
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SE021")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_engineer_update.json", {
			no:					no,
			name:				name.val(),
			company_code:		company_code.val(),
			certificated:		certificated.val()
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
		var no = document.getElementById('no').value;

		// 確認メッセージ
		if (!window.confirm(getMessage("SE022")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_engineer_delete.json", {
			no:					no
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

