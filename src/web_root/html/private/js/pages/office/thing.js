/**
 * 物件管理関連画面用JS
 *
 * $Author: mizoguchi $
 * $Rev: 204 $
 * $Date: 2013-10-11 00:16:22 +0900 (2013/10/11 (金)) $
 */
// ※定数値は js/definitions.js で定義

$(document).ready(function(e) {
	resetConstructionNo();
	reloadEngineers();
	reloadArchitects();

	// 年度の書き換え
	$(document).on("keyup", "#year", function (e) {
		resetConstructionNo();
	});

	// 施工会社の選択変更
	$(document).on("change", "#constructor", function (e) {
		resetConstructionNo();
		reloadEngineers();
	});

	// 施工管理技術者の選択変更
	$(document).on("change", "#engineer", function (e) {
		// 値をクッキーに保存(戻るボタン対策)
		$.cookie("c_engineer", $(this).val());
	});

	// 設計会社の選択変更
	$(document).on("change", "#design_company", function (e) {
		reloadArchitects();
	});

	// 設計担当者の選択変更
	$(document).on("change", "#architect", function (e) {
		// 値をクッキーに保存(戻るボタン対策)
		$.cookie("c_architect", $(this).val());
	});

	// 入力確認ボタン
	$(document).on("click", "#thing_register_button", function (e) {
		_input_check(true);
	});

	// 登録ボタン
	$(document).on("click", "#reg_button", function (e) {
		window.location.href = base + "office/thing_register_finish.html";
	});

	// 検索ボタン
	$(document).on("click", "#search_button", function (e) {
		search();
	});

	// 入力確認ボタン
	$(document).on("click", "#thing_change_button", function (e) {
		_input_check(false);
	});

	// 変更ボタン
	$(document).on("click", "#update_button", function (e) {
		window.location.href = base + "office/thing_change_finish.html";
	});
});

/**
 * 検索ボタン押下時の処理を行ないます。
 */
function search() {
	// 整形(半角数値以外は除外)
	$("input[type=text]").each(function (idx, elem) {
		var t = $(this);
		var v = t.val();
		if ( !isset(v) ) {
			v = "";
		}
		t.val( v.replace(/[^0-9]/g, "") );
	});

	// 検索画面なので、例外的にフォーム送信を行なう
	$("#searchF").submit();
}

/**
 * 認識番号を更新します。
 */
function resetConstructionNo() {
	var form = $("#regF").get(0);
	var companyCode = $("#constructor").val();
	if ( $.trim(companyCode) == "" ) {
		return;
	}
	var nendo = form.year.value;
	var no = "W" + companyCode + "-" + nendo + "-";
	q(base + "office/get_max_seq.json", {
		company_code : companyCode,
		nendo        : nendo
	}).done(function (res) {
		if (res.status == "OK") {
			var seq = "1" + pad3(res.seq);
			no += seq;
			$("#company_seq").text(seq);
		}
		$("#constructionNo").text(no);
	});
}

/**
 * 施工管理技術者のプルダウンメニューを入れ替えます。
 */
function reloadEngineers() {
	var companyCode = $("#constructor").val();
	var select = $("#engineer");
	if ( !isset(select.get(0)) ) {
		return;
	}
	q(base + "office/get_engineers.json", {
		company_code : companyCode
	}).done(function (res) {
		if (res.status == "OK") {
			var html = '<option value=""></option>';
			for ( var i = 0; i < res.engineers.length; i++ ) {
				var egnr = res.engineers[i];
				html += '<option value="' + egnr.no + '">' + egnr.name + '</option>';
			}
			select.html(html);
			if ( isset($.cookie("c_engineer")) ) {
				select.val($.cookie("c_engineer"));
			}
		}
	});
}

/**
 * 設計担当者のプルダウンメニューを入れ替えます。
 */
function reloadArchitects() {
	var companyCode = $("#design_company").val();
	var select = $("#architect");
	if ( !isset(select.get(0)) ) {
		return;
	}
	q(base + "office/get_architects.json", {
		company_code : companyCode
	}).done(function (res) {
		if (res.status == "OK") {
			var html = '<option value=""></option>';
			for ( var i = 0; i < res.architects.length; i++ ) {
				var arch = res.architects[i];
				html += '<option value="' + arch.no + '">' + arch.name + '</option>';
			}
			select.html(html);
			if ( isset($.cookie("c_architect")) ) {
				select.val($.cookie("c_architect"));
			}
		}
	});
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
	if ( !_empty(form[prefix + "day_yyyy"].value)
	  || !_empty(form[prefix + "day_mm"].value)
	  || !_empty(form[prefix + "day_dd"].value)
		) {
		// いずれかに入力がある場合はマークを付けて
		// 確認画面に警告を表示
		return "warn";
	}
	return "";
}

function _set_ymd(prefix, ymd) {
	if ( ymd.length != 10 || !ymd) {
		return;
	}
	var y = ymd.substring(0, 4);
	var m = ymd.substring(5, 7);
	var d = ymd.substring(8, 10);

	var form = $("#regF").get(0);
	form[prefix + "day_yyyy"].value = y;
	form[prefix + "day_mm"].value   = m;
	form[prefix + "day_dd"].value   = d;
}

function _input_check(regist) {
	Bar.clear();

	/* 入力値を集約 */
	var form = $("#regF").get(0);

	var data = {
		construction_company    : $("#constructor").val(),
		nendo                   : form.year.value,
		architect_company       : $("#design_company").val(),
		architect               : $("#architect").val(),
		engineer                : $("#engineer").val(),
		order_company           : $("#contractee").val(),
		construction_name       : form.construction_name.value,
		construction_address    : form.work_place.value,
		construction_start_date : _concat_ymd("s"),
		complete_date           : _concat_ymd("e"),
		report_date             : _concat_ymd("a"),
		amount                  : form.number.value,
		material_id             : $("#material_id").val(),
		sybt                    : $("#sybt").val(),
		sybt2                   : $("#sybt2").val(),
		kouzou                  : $("#kozo").val(),
		yoto                    : form.use.value,
		kiso                    : $("#kiso").val(),
		floor                   : form.floor.value,
		height                  : form.height.value,
		nokidake                : form.hotels_high.value,
		totalarea               : form.area.value,
		depth                   : form.depth_construction.value,
		status                  : $("#status").val()
	};
	// 変更画面の場合
	if ( !regist ) {
		data.construction_no  = form.construction_no.value;
		data.construction_eda = form.construction_eda.value;
	}

	/* 必須チェック */
	// 識別年度
	if ( _empty( data.nendo ) ) {
		Bar.add("error", getMessage("MC001", ["識別年度"]));
	}
	// 会社コード
	if ( _empty( data.construction_company ) ) {
		Bar.add("error", getMessage("MC002", ["施工会社名"]));
	}
	// ステータスが「完了」の場合は完工日(実績表出力で必要)
	if ( data.status == CONSTRUCT_COMPLETE && (_empty(data.complete_date) || data.complete_date == "warn") ) {
		Bar.add("error", getMessage("MC002", ["完工日"]));
	}

	/* 数値チェック */
	// 年度
	if ( !isNatural( data.nendo ) ) {
		Bar.add("error", getMessage("MC008", ["年度"]));
	}
	// 打設本数
	if ( !isNatural( data.amount ) ) {
		Bar.add("error", getMessage("MC008", ["打設本数"]));
	}
	// 階数
	if ( !isNatural( data.floor ) ) {
		Bar.add("error", getMessage("MC008", ["階数"]));
	}
	// 高さ(m)
	if ( !isDecimal(data.height, 5, 2) ) {
		Bar.add("error", getMessage("MC009", ["高さ(m)", (5 - 2), 2]));
	}
	// 軒高(m)
	if ( !isDecimal(data.nokidake, 5, 2) ) {
		Bar.add("error", getMessage("MC009", ["軒高(m)", (5 - 2), 2]));
	}
	// 延べ面積(㎡)
	if ( !isDecimal(data.totalarea, 6, 2) ) {
		Bar.add("error", getMessage("MC009", ["延べ面積(㎡)", (6 - 2), 2]));
	}
	// 最大施工深さ(m)
	if ( !isDecimal(data.depth, 5, 2) ) {
		Bar.add("error", getMessage("MC009", ["最大施工深さ(m)", (5 - 2), 2]));
	}


	if ( Bar.hasMessages() ) {
		Bar.showAll();
	}
	else {
		q(base + "office/thing_check.json", data).done(function (res) {
			if ( res.status == "OK" ) {
				window.location.href = base + "office/thing_" + (regist ? "register" : "change") + "_check.html";
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

