/**
 * 材種設定画面用JS
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

		var material_code = $("#material_code");
		var material_name = $("#material_name");

		if ( $.trim(material_code.val()) == "" ) {
			Bar.add("error", getMessage("SM001", ["材種コード"]) );
		}
		else {
			if (!$.isNumeric(material_code.val())) {
				Bar.add("error", getMessage("SM003", ["材種コード"]) );
			}
		}

		if ( $.trim(material_name.val()) == "" ) {
			Bar.add("error", getMessage("SM001", ["名称"]) );
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SM020")) ){
			return false;
		}

		// 新規登録処理
		q( base + "office/system_material_new.json", {
			material_code:		material_code.val(),
			material_name:		material_name.val()
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
		var material_id = document.getElementById('material_id').value;

		var material_code = $("#material_code_"+material_id);
		var material_name = $("#material_name_"+material_id);

		if ( $.trim(material_code.val()) == "" ) {
			Bar.add("error", getMessage("SM001", ["材種コード"]) );
		}
		else {
			if (!$.isNumeric(material_code.val())) {
				Bar.add("error", getMessage("SM003", ["材種コード"]) );
			}
		}

		if ( $.trim(material_name.val()) == "" ) {
			Bar.add("error", getMessage("SM001", ["名称"]) );
		}


		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 確認メッセージ
		if (!window.confirm(getMessage("SM021")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_material_update.json", {
			material_id:		material_id,
			material_code:		material_code.val(),
			material_name:		material_name.val()
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
		var material_id = document.getElementById('material_id').value;

		// 確認メッセージ
		if (!window.confirm(getMessage("SM022")) ){
			return false;
		}

		// 更新登録処理
		q( base + "office/system_material_delete.json", {
			material_id:		material_id
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

