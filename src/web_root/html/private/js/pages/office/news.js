/**
 * 事務局からのお知らせ画面用JS
 *
 * $Author: murayama $
 * $Rev: 26 $
 * $Date: 2013-09-20 09:19:24 +0900 (2013/09/19 (木)) $
 */
$(document).ready(function(e) {
	// 追加ボタン
	$(document).on("click", "#reg_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var news_date = $("#news_date");
		var news_title = $("#news_title");
		var news_content = $("#news_content");

		if ( $.trim(news_date.val()) == "" ) {
			Bar.add("error", getMessage("NW001", ["日付"]) );
		}
		if ( $.trim(news_title.val()) == "" ) {
			Bar.add("error", getMessage("NW001", ["タイトル"]) );
		}


		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 新規登録処理
		q( base + "office/news_check.json", {
			news_date:		news_date.val(),
			news_title:		news_title.val(),
			news_content:	news_content.val()
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

	// 新規登録ボタン
	$(document).on("click", "#new", function (e) {
		var news_date = $("#news_date");
		var news_title = $("#news_title");
		var news_content = $("#news_content");

		// 通知バー消去
		Bar.clear();

		// 新規登録処理
		q( base + "office/news_finish.json", {
			news_date:		news_date.val(),
			news_title:		news_title.val(),
			news_content:	news_content.val()
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
		var news_id = document.getElementById('news_id').value;

		// 通知バー消去
		Bar.clear();

		// 確認メッセージ
		if (!window.confirm(getMessage("NW022")) ){
			return false;
		}

		// 削除処理
		q( base + "office/news_delete.json", {
			news_id:		news_id
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

