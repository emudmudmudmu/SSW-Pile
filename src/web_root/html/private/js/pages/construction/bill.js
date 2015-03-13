/**
 * 受取請求履歴関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 190 $
 * $Date: 2013-10-08 14:06:23 +0900 (2013/10/08 (火)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	// 請求書印刷ボタン
	$(document).on("click", "#invoice_print", function (e) {
		window.open(base + "Bill_" + pad5(key) + ".pdf");
	});

	// 領収書印刷ボタン
	$(document).on("click", "#receipt_printing", function (e) {
		window.open(base + "Receipt_" + pad5(key) + ".pdf");
	});

});


