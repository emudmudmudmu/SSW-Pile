/**
 * 帳票出力画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 211 $
 * $Date: 2013-10-12 00:19:12 +0900 (2013/10/12 (土)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		// 通知バー消去
		Bar.clear();

		var e_s_yyyy = $("#e_s_yyyy");
		var e_s_mm = $("#e_s_mm");
		var e_s_dd = $("#e_s_dd");
		var e_e_yyyy = $("#e_e_yyyy");
		var e_e_mm = $("#e_e_mm");
		var e_e_dd = $("#e_e_dd");

		if ( $.trim(e_s_yyyy.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始年"]) );
		}
		else if (!$.isNumeric(e_s_yyyy.val())) {
			Bar.add("error", getMessage("CC006", ["開始年"]) );
		}
		if ( $.trim(e_s_mm.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始月"]) );
		}
		else if (!$.isNumeric(e_s_mm.val())) {
			Bar.add("error", getMessage("CC006", ["開始月"]) );
		}
		if ( $.trim(e_s_dd.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["開始日"]) );
		}
		else if (!$.isNumeric(e_s_dd.val())) {
			Bar.add("error", getMessage("CC006", ["開始日"]) );
		}

		if ( $.trim(e_e_yyyy.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了年"]) );
		}
		else if (!$.isNumeric(e_e_yyyy.val())) {
			Bar.add("error", getMessage("CC006", ["終了年"]) );
		}
		if ( $.trim(e_e_mm.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了月"]) );
		}
		else if (!$.isNumeric(e_e_mm.val())) {
			Bar.add("error", getMessage("CC006", ["終了月"]) );
		}
		if ( $.trim(e_e_dd.val()) == "" ) {
			Bar.add("error", getMessage("CC001", ["終了日"]) );
		}
		else if (!$.isNumeric(e_e_dd.val())) {
			Bar.add("error", getMessage("CC006", ["終了日"]) );
		}

		// エラーメッセージ
		if ( Bar.hasMessages() ) {
			Bar.showAll();
			return false;
		}

		// 検索処理
		q( base + "office/performance_search.json", {
			e_s_yyyy: e_s_yyyy.val(),
			e_s_mm:   e_s_mm.val(),
			e_s_dd:   e_s_dd.val(),
			e_e_yyyy: e_e_yyyy.val(),
			e_e_mm:   e_e_mm.val(),
			e_e_dd:   e_e_dd.val()
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
		window.open(base + "office/construction.csv");
	});
	
	// PDFボタン
	$(document).on("click", "#pdf_button", function (e) {
/*
		Bar.add("warn", getMessage("MZ005", ["実績表PDF", "施工実績件数表"]));
		Bar.showAll();
*/		
		var form = $("#searchF").get(0);
		form.action = base + "ConstructionResult.pdf";
		form.target = "_blank";
		form.submit();
		
		form.action = "";
		form.target = "_self";
		return false;
	});
});



function _empty(val) {
	return ( !isset(val) || $.trim(val) == "" );
};

