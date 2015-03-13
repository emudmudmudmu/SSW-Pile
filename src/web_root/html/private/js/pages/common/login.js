/**
 * ログイン画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 26 $
 * $Date: 2013-09-19 09:19:24 +0900 (2013/09/19 (木)) $
 */
$(document).ready(function(e) {
	// ログインボタン
	$(document).on("click", "#submit_button", function (e) {
		// 通知バー消去
		Bar.clear();
		
		var id   = $("#login_id");
		var pswd = $("#passwd");
		
		if ( $.trim(id.val()) == "" ) {
			Bar.add("error", getMessage("MC001", ["ID"]) );
		}
		if ( $.trim(pswd.val()) == "" ) {
			Bar.add("error", getMessage("MC001", ["パスワード"]) );
		}
		
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}
		// ログイン処理
		q( base + "login.json", {
			login_id: id.val(),
			passwd  : pswd.val()
		} ).done( function (res) {
			if ( res.status == "OK" ) {
				window.location.href = res.url;
			}
			else {
				Bar.add("error", getMessage(res.message));
				Bar.showAll();
			}
		});
	});
});
