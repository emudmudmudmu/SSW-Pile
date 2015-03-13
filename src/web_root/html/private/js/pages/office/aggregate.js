/**
 * 集計画面用JS
 *
 * $Author: murayama $
 * $Rev: 192 $
 * $Date: 2013-10-08 15:41:04 +0900 (2013/10/08 (火)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var s_yyyy = $("#s_yyyy");
		var s_mm = $("#s_mm");
		var s_dd = $("#s_dd");
		var e_yyyy = $("#e_yyyy");
		var e_mm = $("#e_mm");
		var e_dd = $("#e_dd");

		if ( $.trim(s_yyyy.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始年"]) );
		}
		else if (!$.isNumeric(s_yyyy.val())) {
			Bar.add("error", getMessage("CC006", ["開始年"]) );
		}
		if ( $.trim(s_mm.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始月"]) );
		}
		else if (!$.isNumeric(s_mm.val())) {
			Bar.add("error", getMessage("CC006", ["開始月"]) );
		}
		if ( $.trim(s_dd.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始日"]) );
		}
		else if (!$.isNumeric(s_dd.val())) {
			Bar.add("error", getMessage("CC006", ["開始日"]) );
		}

		if ( $.trim(e_yyyy.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了年"]) );
		}
		else if (!$.isNumeric(e_yyyy.val())) {
			Bar.add("error", getMessage("CC006", ["終了年"]) );
		}
		if ( $.trim(e_mm.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了月"]) );
		}
		else if (!$.isNumeric(e_mm.val())) {
			Bar.add("error", getMessage("CC006", ["終了月"]) );
		}
		if ( $.trim(e_dd.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了日"]) );
		}
		else if (!$.isNumeric(e_dd.val())) {
			Bar.add("error", getMessage("CC006", ["終了日"]) );
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 検索処理
		q( base + "office/aggregate_search.json", {
			s_yyyy: s_yyyy.val(),
			s_mm:   s_mm.val(),
			s_dd:   s_dd.val(),
			e_yyyy: e_yyyy.val(),
			e_mm:   e_mm.val(),
			e_dd:   e_dd.val()
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


	// CSVボタン
	$(document).on("click", "#csv_button", function (e) {
		// 通知バー消去
		Bar.clear();

		// 確認メッセージ
		if (!window.confirm(getMessage("AG001")) ){
			return false;
		}

		// CSV出力処理
		window.open(base + "office/aggregate.csv");
	});

});



function _empty(val) {
	return ( !isset(val) || $.trim(val) == "" );
};

