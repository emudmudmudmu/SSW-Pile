/**
 * 全画面共通JS
 *
 * $Author: mizoguchi $
 * $Rev: 196 $
 * $Date: 2013-10-08 20:55:58 +0900 (2013/10/08 (火)) $
 */
// ドキュメント中（または他JS）で定義する変数
// base         … ベースURL

// 色定数
var COLOR_FOCUS = "#ff9";
var COLOR_INPUT = "#fff";

$(document).ready(function(e) {
	if (typeof preInit == "function") {
		preInit();
	}
	
	// 初期描画処理
	setTimeout("init()", 10);
});

/**
 * ページの初期描画の続きを行ないます。
 */
function init() {
	// IE6 バグ対策
	if (lte6) {
		// select要素のz-index不具合対策
		// Easy UI の panel に対して設定
		$(".panel").bgiframe();
	}
	
	// フォームのフォーカス時背景色(モダンブラウザはCSSで対応)
	if (oldBrowser) {
		applyFocusEvent("input");
		applyFocusEvent("select");
		applyFocusEvent("textarea");
	}
	
	// 描画処理
	setTimeout("reRendering()", 10);
};

/**
 * レイアウト変更時等に再描画が必要な処理を行ないます。
 */
function reRendering() {
	
	/* ボックスの高さ揃え */
	// ".private" は min-height が指定されているので、
	// 比較対象には加えない。変数 h は height プロパティに設定
	// されるので、height < min-height の場合は min-height が優先
	var h = Math.max($(".private_body").height(), $(".sidebar").height());
	$(".private").height(h + 160);
	
	if (typeof afterRendering == "function") {
		afterRendering();
	}
};

/**
 * 背景色を変更するフォーカスイベントハンドラを追加する関数です。
 *
 * @param {String} elem 適用対象の要素名（例："input"）
 */
function applyFocusEvent(elem) {
	$(document).on("focus", elem, function() {
		$(this).css("background-color", COLOR_FOCUS);
	});
	$(document).on("blur", elem, function(e) {
		$(this).css("background-color", COLOR_INPUT);
	});
};

/**
 * 自分自身を閉じる関数です。
 * 単純な window.close() では Chrome がウィンドウを閉じないのでこれを使います。
 */
function winClose(){
//	if (online)
//		q(base + "browser_close.json", {}).done( function(res) {});
	var nvua = navigator.userAgent;
	if(nvua.indexOf('MSIE') >= 0){
		if(nvua.indexOf('MSIE 5.0') == -1) {
			top.opener = '';
		}
		window.close();
	}
	else {
		top.name = 'CLOSE_WINDOW';
		wid = window.open('','CLOSE_WINDOW');
		top.close();
	}
}

/**
 * デバッグ用のログ出力(コンソール出力)を行なう関数です。
 */
var debugStatus = {count:0};
function debugLog(message) {
	if (typeof console != "undefined") {
		if (arguments.length == 1)
			console.log(message);
		else if (arguments.length == 2)
			console.log(message, arguments[1]);
		else
			console.log(arguments);
	}
	else {
		if (80 < window.status.length) {
			debugStatus.count++;
			window.status = "[" + debugStatus.count + "times]";
		}
		window.status += message + "|";
	}
}

/**
 * Ajax POSTリクエストを送ります。(ただし同期型)
 */
function q(url, data) {
	var jqxhr = $.ajax(url, {
			type: "POST",
			data: data,
			dataType: "json",
			async: false,
			error: function(jqXHR, textStatus, errorThrown) {
				$.messager.alert("通信エラー", getMessage("MZ001", [textStatus, errorThrown]), "error");
			}
		});
	return jqxhr;
};

function scrollTop() {
	$('html,body').animate({ scrollTop: 0 });
};

function in_array( needle, haystack ) {
	if ( Array.isArray(haystack) ) {
		for ( var i = 0; i < haystack.length; i++ ) {
			if ( haystack[i] == needle ) {
				return true;
			}
		}
	}
	return false;
}

function pad2(val) {
	var str = "" + val;
	var pad = "00";
	return pad.substring(0, pad.length - str.length) + str;
}

function pad3(val) {
	var str = "" + val;
	var pad = "000";
	return pad.substring(0, pad.length - str.length) + str;
}

function pad4(val) {
	var str = "" + val;
	var pad = "0000";
	return pad.substring(0, pad.length - str.length) + str;
}

function pad5(val) {
	var str = "" + val;
	var pad = "00000";
	return pad.substring(0, pad.length - str.length) + str;
}

function numberFormat (num){
	return num.toString().replace(/([\d]+?)(?=(?:\d{3})+$)/g, function(t){ return t + ','; });
}

// 自然数チェック
function isNatural(val) {
	if ( $.trim(val) != "" ) {
		return (val.replace(/[0-9]/g, "").length == 0);
	}
	// 引数が空の場合はチェックOKとする
	return true;
}

// 小数点数チェック
function isDecimal(val, whole, decimal) {
	if ( $.trim(val) != "" ) {
		var reg = new RegExp("^[0-9]{1," + (whole - decimal) + "}\\.[0-9]{1," + decimal + "}$");
		console.log("exec:" + reg.exec(val));
		return ( reg.exec(val) == val );
	}
	// 引数が空の場合はチェックOKとする
	return true;
}


/**
 * イベントトリガークラス
 * http://blog.livedoor.jp/techblog/archives/65221145.html
 */
var Trigger = (function () {

	function isArray (obj) {
		return Object.prototype.toString.call(obj) == "[object Array]";
	}

	function each (obj, callback) {
		for ( var i in obj ) {
			callback(i, obj[i], obj);
		}
	}

	function map (ary, callback, thisObject) {
		var res = [];
		each(ary, function (i, val) {
			res[i] = callback.call(thisObject,val,i,this);
		});
		return res;
	}

	function every (ary, callback, thisObject) {
		for(var i=0,len=ary.length;i<len;i++) {
			if(!callback.call(thisObject,ary[i],i,ary)) return false;
		}
		return true;
	}

	var tid = null;

	function COND (statement, callback) {
		if ( tid != null ) clearInterval(tid), tid = null;
		tid = setInterval(function () {
			(isArray( statement ) ? every(statement, function (_cond) {
				 return _cond();
			}) : statement()) && ( clearInterval(tid), tid = null, setTimeout(callback, 0) );
		}, 200);
	};

	var events = {
		allAnimated: function () {
			return !$.timers.length;
		},
		ajaxFullComplete: function () {
			return !$.active;
		}

	}, triggers = {
		multi: function (names, callback) {
			return COND
			(
				isArray( names ) ? map(names, function (name) {
					return events[name] || function () { return true; };
				}) : names, callback
			);
		},
		util: {
			regist: function (name, statement) {
				triggers[name] = function (callback) {
					COND(statement, callback);
				};
				return {
					after: function (callback) {
						triggers[name](callback);
					}
				};
			}
		}
	};

	each(events, function (name, statement) {
		triggers.util.regist.apply(null, arguments);
	});

	return triggers;

})();