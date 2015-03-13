<?php
use Laravel\View;
use Laravel\Input;
use Laravel\Response;
/**
 * ルーティング定義
 *
 * $Author: mizoguchi $
 * $Rev: 211 $
 * $Date: 2013-10-12 00:19:12 +0900 (2013/10/12 (土)) $
 */
/*--------------------------------------------------------------------
 URLによる権限チェック
--------------------------------------------------------------------*/
require_once 'auth_filters.php';


/*--------------------------------------------------------------------
 指定施工会社
--------------------------------------------------------------------*/
/* TOP/協会からのお知らせ */
Route::get( 'construction/top.html',                                'construction.top@index');
Route::get( 'construction/(:num)/top_detail.html',                  'construction.top@detail');

/* 指定施工会社名簿 */
Route::get( 'construction/roster.html',                             'construction.roster@index');
Route::get( 'construction/(:num)/roster_detail.html',               'construction.roster@detail');

/* 物件照会 */
Route::get( 'construction/thing_query.html',                        'construction.const@query');            // [GET ] 物件情報検索初期表示
Route::post('construction/thing_query.html',                        'construction.const@do_query');         // [POST] 物件情報検索実行
Route::get( 'construction/(:any)/(:num)/thing_detail.html',         'construction.const@detail');           // [GET ] 物件情報変更初期表示

/* 様式ダウンロード */
// 静的ページなので直接実装
Route::get( 'construction/style_download.html', function () {
	return View::make('construction/style_download');
});

/* 物件登録 */
Route::get( 'construction/thing_register.html',                     'construction.const@index');            // [GET ] 登録フォーム
Route::post('construction/get_max_seq.json',                        'construction.const@seq');              // [POST] 年度内通番の取得[AJAX]
Route::post('construction/get_engineers.json',                      'construction.const@engineer');         // [POST] 施工管理技術者の取得[AJAX]
Route::post('construction/get_architects.json',                     'construction.const@architect');        // [POST] 設計担当者の取得[AJAX]
Route::post('construction/thing_check.json',                        'construction.const@check');            // [POST] 登録内容の入力チェック[AJAX]
Route::get( 'construction/thing_(:any)_check.html',                 'construction.const@confirm');          // [GET ] 確認画面表示(登録/編集の両用)
Route::get( 'construction/thing_register_finish.html',              'construction.const@complete');         // [GET ] 完了画面表示(物件情報登録)
Route::get( 'construction/thing_search.html',                       'construction.const@search');           // [GET ] 物件情報検索初期表示
Route::post('construction/thing_search.html',                       'construction.const@do_search');        // [POST] 物件情報検索実行
Route::get( 'construction/(:any)/(:num)/thing_change.html',         'construction.const@change');           // [GET ] 物件情報変更初期表示
Route::get( 'construction/thing_change_finish.html',                'construction.const@change_complete');  // [GET ] 完了画面表示(物件情報編集)

/* パーツ発注 */
Route::get( 'construction/parts_order.html',                        'construction.order@index');      // [GET ] パーツ発注画面
Route::post('construction/order_check.json',                        'construction.order@check');      // [POST] 注文内容の入力チェック[AJAX]
Route::get( 'construction/parts_order_check.html',                  'construction.order@confirm');    // [GET ] 確認画面表示
Route::get( 'construction/parts_order_finish.html',                 'construction.order@complete');   // [GET ] 完了画面表示

/* パーツ発注履歴 */
Route::get( 'construction/parts_order_history.html',                'construction.parts@index');            // [GET ] 検索フォーム初期表示
Route::get( 'construction/(:num)/parts_order_history_detail.html',  'construction.parts@detail');           // [GET ] 受注情報編集初期表示

/* 受取請求履歴 */
Route::get( 'construction/receipt_billing_history.html',            'construction.bill@index');  // [GET ] 受取請求履歴一覧画面表示
Route::get( 'construction/(:num)/receipt_billing_detail.html',      'construction.bill@detail'); // [GET ] 受取請求詳細

/* 会員登録情報変更 */
Route::get( 'construction/member_change.html',                      'construction.member@index');
Route::post('construction/member_check.json',                       'construction.member@check');
Route::get( 'construction/member_change_check.html',                'construction.member@confirm');
Route::get( 'construction/member_change_finish.html',               'construction.member@finish');


/*--------------------------------------------------------------------
 事務局
--------------------------------------------------------------------*/
Route::get('office/top.html', 'office.top@index');

/* 会員管理 */
Route::get( 'office/member_register.html',                          'office.member@index');           // [GET ] 登録フォーム
Route::post('office/member_check/(:any).json',                      'office.member@check');           // [POST] 登録内容の入力チェック[AJAX]
Route::get( 'office/member_(:any)_check.html',                      'office.member@confirm');         // [GET ] 確認画面表示(登録/編集の両用)
Route::get( 'office/member_register_finish.html',                   'office.member@complete');        // [GET ] 完了画面表示(会員情報登録)
Route::get( 'office/member_change_search.html',                     'office.member@search');          // [GET ] 会員情報検索初期表示
Route::get( 'office/member_(:any).html',                            'office.member@list');            // [GET ] 会員情報一覧(全件,前方一致検索)
Route::get( 'office/(:num)/member_change.html',                     'office.member@change');          // [GET ] 会員情報変更初期表示
Route::get( 'office/member_change_finish.html',                     'office.member@change_complete'); // [GET ] 完了画面表示(会員情報編集)

/* 先端パーツ受注管理 */
Route::get( 'office/order_search.html',                             'office.order@index');            // [GET ] 検索フォーム初期表示
Route::post('office/order_search.html',                             'office.order@search');           // [POST] 受注情報検索実行
Route::get( 'office/(:num)/order_detail.html',                      'office.order@change');           // [GET ] 受注情報編集初期表示
Route::post('office/order_check.json',                              'office.order@check');            // [POST] 受注情報編集入力チェック[AJAX]
Route::get( 'office/order_detail_check.html',                       'office.order@confirm');          // [GET ] 確認画面表示
Route::get( 'office/(:num)/order_detail_finish.html',               'office.order@complete');         // [GET ] 完了画面表示
Route::get( 'office/parts_price_search.html',                       'office.order@pay_search');       // [GET ] パーツ代金精算画面初期表示
Route::post('office/parts_price_search_result.html',                'office.order@pay');              // [POST] パーツ代金精算画面
Route::get( 'office/clearing_(:num).csv',                           'office.order@clearing');         // [GET ] パーツ代金精算CSV出力

/* 物件管理 */
Route::get( 'office/thing_register.html',                           'office.const@index');            // [GET ] 登録フォーム
Route::post('office/get_max_seq.json',                              'office.const@seq');              // [POST] 年度内通番の取得[AJAX]
Route::post('office/get_engineers.json',                            'office.const@engineer');         // [POST] 施工管理技術者の取得[AJAX]
Route::post('office/get_architects.json',                           'office.const@architect');        // [POST] 設計担当者の取得[AJAX]
Route::post('office/thing_check.json',                              'office.const@check');            // [POST] 登録内容の入力チェック[AJAX]
Route::get( 'office/thing_(:any)_check.html',                       'office.const@confirm');          // [GET ] 確認画面表示(登録/編集の両用)
Route::get( 'office/thing_register_finish.html',                    'office.const@complete');         // [GET ] 完了画面表示(物件情報登録)
Route::get( 'office/thing_search.html',                             'office.const@search');           // [GET ] 物件情報検索初期表示
Route::post('office/thing_search.html',                             'office.const@do_search');        // [POST] 物件情報検索実行
Route::get( 'office/(:any)/(:num)/thing_change.html',               'office.const@change');           // [GET ] 物件情報変更初期表示
Route::get( 'office/thing_change_finish.html',                      'office.const@change_complete');  // [GET ] 完了画面表示(物件情報編集)

/* 請求管理 */
Route::get( 'office/request_search.html',                           'office.bill@index');          // [GET ] 検索フォーム初期表示
Route::post('office/request_search.html',                           'office.bill@search');         // [POST] 請求情報検索実行
Route::get( 'office/(:num)/(:any)/request_result.html',             'office.bill@result');         // [GET ] 請求情報確認
Route::post('office/(:num)/(:any)/create_bill.json',                'office.bill@create_bill');    // [POST] 請求情報作成[AJAX]
Route::post('office/bill_payment.json',                             'office.bill@payment');        // [POST] 入金日登録[AJAX]
Route::post('office/bill_reissue.json',                             'office.bill@reissue');        // [POST] 請求書再発行[AJAX]
Route::post('office/bill_combined.json',                            'office.bill@combined');       // [POST] 一括請求書発行[AJAX]

/* 集計処理 */
Route::get( 'office/aggregate_search.html',                         'office.aggregate@index');       // [GET ] 会社別集計検索画面
Route::post('office/aggregate_search.json',                         'office.aggregate@search');
Route::get( 'office/aggregate_search_result.html',                  'office.aggregate@result');
Route::get( 'office/aggregate.csv',                                 'office.aggregate@csv');

/* 実績表出力 */
Route::get( 'office/performance_search.html',                       'office.performance@index');     // [GET ] 帳票出力検索画面
Route::post('office/performance_search.json',                       'office.performance@search');
Route::get( 'office/performance_search_result.html',                'office.performance@result');
Route::get( 'office/construction.csv',                              'office.performance@csv');


/* 協会情報 */
Route::get( 'office/society_register.html',                         'office.society_register@index');
Route::post('office/society_register_check.json',                   'office.society_register@check');
Route::get( 'office/society_register_check.html',                   'office.society_register@confirm');
Route::post('office/society_register_finish.json',                  'office.society_register@finish');
Route::get( 'office/society_register_finish.html',                  'office.society_register@complete');

/* システム設定 */
/* 単価設定 */
Route::get( 'office/system_unit.html',                              'office.system_unit@index');
Route::post('office/system_unit_licensefees_new.json',              'office.system_unit@licensefees_new');
Route::post('office/system_unit_licensefees_update.json',           'office.system_unit@licensefees_update');
Route::post('office/system_unit_licensefees_delete.json',           'office.system_unit@licensefees_delete');
Route::post('office/system_unit_item_new.json',                     'office.system_unit@item_new');
Route::post('office/system_unit_item_update.json',                  'office.system_unit@item_update');
Route::post('office/system_unit_item_delete.json',                  'office.system_unit@item_delete');

/* 消費税設定 */
Route::get( 'office/system_tax.html',                               'office.system_tax@index');
Route::post('office/system_tax_new.json',                           'office.system_tax@new');
Route::post('office/system_tax_update.json',                        'office.system_tax@update');
Route::post('office/system_tax_delete.json',                        'office.system_tax@delete');

/* 材種設定 */
Route::get( 'office/system_material.html',                          'office.system_material@index');
Route::post('office/system_material_new.json',                      'office.system_material@new');
Route::post('office/system_material_update.json',                   'office.system_material@update');
Route::post('office/system_material_delete.json',                   'office.system_material@delete');

/* 施工管理技術者設定 */
Route::get( 'office/system_engineer.html',                          'office.system_engineer@index');
Route::post('office/system_engineer_new.json',                      'office.system_engineer@new');
Route::post('office/system_engineer_update.json',                   'office.system_engineer@update');
Route::post('office/system_engineer_delete.json',                   'office.system_engineer@delete');

/* 設計担当者設定 */
Route::get( 'office/system_architect.html',                         'office.system_architect@index');
Route::post('office/system_architect_new.json',                     'office.system_architect@new');
Route::post('office/system_architect_update.json',                  'office.system_architect@update');
Route::post('office/system_architect_delete.json',                  'office.system_architect@delete');

/* 事務局からのお知らせ */
Route::get( 'office/news.html',                                     'office.news@index');
Route::post('office/news_check.json',                               'office.news@check');
Route::get( 'office/news_check.html',                               'office.news@confirm');
Route::post('office/news_finish.json',                              'office.news@finish');
Route::get( 'office/news_finish.html',                              'office.news@complete');
Route::post('office/news_delete.json',                              'office.news@delete');
Route::get( 'office/news_delete_finish.html',                       'office.news@delete_complete');


/*--------------------------------------------------------------------
 パーツ出荷担当
--------------------------------------------------------------------*/
Route::get('parts/top.html', 'parts.top@index');

/* 先端パーツ受注管理 */
Route::get( 'parts/slip_search.html',                               'parts.slip@index');            // [GET ] 検索フォーム初期表示
Route::post('parts/slip_search.html',                               'parts.slip@search');           // [POST] 受注情報検索実行
Route::get( 'parts/(:num)/slip_detail.html',                        'parts.slip@change');           // [GET ] 受注情報編集初期表示
Route::post('parts/slip_check.json',                                'parts.slip@check');            // [POST] 受注情報編集入力チェック[AJAX]
Route::get( 'parts/slip_detail_finish.html',                        'parts.slip@complete');         // [GET ] 受注情報変更


/*--------------------------------------------------------------------
 帳票印刷(例外的に、権限制御はコントローラーで行なう)
--------------------------------------------------------------------*/
Route::get( 'Bill_(:num).pdf',                                      'print@bill');       // [GET ] 請求書印刷
Route::get( 'Receipt_(:num).pdf',                                   'print@receipt');    // [GET ] 領収書印刷
Route::get( 'Slip_(:num).pdf',                                      'print@slip');       // [GET ] 納品書印刷
Route::get( 'Inst_(:num).pdf',                                      'print@inst');       // [GET ] 受注伝票兼出荷指示書印刷
Route::post('ConstructionResult.pdf',                               'print@cr');         // [POST] 施工実績件数表


/*--------------------------------------------------------------------
 認証関連
 --------------------------------------------------------------------*/
Route::post('login.json',                                           'home@login');
Route::any( 'index.html',                                           'home@index');
Route::any( 'login.html',                                           'home@index');
Route::any( 'logout.html',                                          'home@logout');


/*--------------------------------------------------------------------
 全体
 --------------------------------------------------------------------*/
Route::any( 'jig.html',                                             'jig@index');
Route::any( 'hash.json',                                            'jig@hash');
Route::any( 'pdftest.pdf',                                          'jig@pdf');
Route::get( 'js/definitions.js',                                    'global@definitions');
Route::any( '/',                                                    'home@index'); // 最後に定義する事


/**
 * テンプレートコンポーザー
 *
 * テンプレートが生成される場合に呼び出される
 * セッションにメッセージが存在する場合、
 * ビューに変数として渡す。
 *
 * これにより、ビューとリダイレクト両方で
 * ->with('message', 'メッセージ内容')の形で
 * メッセージを指定できる
 *
 */
include_once path('app') . 'composer.php';

/*
  |--------------------------------------------------------------------------
  | Application 404 & 500 Error Handlers
  |--------------------------------------------------------------------------
  |
  | To centralize and simplify 404 handling, Laravel uses an awesome event
  | system to retrieve the response. Feel free to modify this function to
  | your tastes and the needs of your application.
  |
  | Similarly, we use an event to handle the display of 500 level errors
  | within the application. These errors are fired when there is an
  | uncaught exception thrown in the application.
  |
 */

Event::listen('404', function () {
	return Response::make(View::make('common.404'), 404);
});

Event::listen('500', function () {
	return Response::make(View::make('common.500'), 500);
});

/*
  |--------------------------------------------------------------------------
  | Route Filters
  |--------------------------------------------------------------------------
  |
  | Filters provide a convenient method for attaching functionality to your
  | routes. The built-in before and after filters are called before and
  | after every request to your application, and you may even create
  | other filters that can be attached to individual routes.
  |
  | Let's walk through an example...
  |
  | First, define a filter:
  |
  |		Route::filter('filter', function()
  |		{
  |			return 'Filtered!';
  |		});
  |
  | Next, attach the filter to a route:
  |
  |		Router::register('GET /', array('before' => 'filter', function()
  |		{
  |			return 'Hello World!';
  |		}));
  |
 */

Route::filter('before', function () {
	// アプリケーションに対する全てのリクエストの前に行うコードをここに記述
	Log::_uri__(Request::uri());
	$input = Input::all();
	foreach ($input as $k => $v) {
		$tab = str_repeat(' ', 24 - strlen($k));
		if ( is_array($v) ) {
			Log::_param("{$k}{$tab}:" . preg_replace('#\n#', '', var_export($v, true)) );
		}
		else {
			Log::_param("{$k}{$tab}:[{$v}]");
		}
	}

	// クッキー、ブラウザ設定から表示言語を決定する
	// LaravelはURLのドメイン名直後に言語コードを
	// 指定することにより、Langクラスで使用する
	// デフォルト言語を切り替えられる。
	//
	// 例）sample.com/en/top 英語表示へ
	//
	// これは多少ダサいので、以下の動作をさせる
	//  1 ) 明確に指定された場合は、クッキーに保存し
	//      以降はその言語で表示
	//  2 ) 指定されていない場合は、ブラウザの言語設定
	//      から表示言語を決定

	$default = Config::get('language.fallback', 'en');

	if ( Cookie::has('language') ) {
		Config::set('application.language', Cookie::get('language'));
	}
	elseif ( !isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) { // IEは未設定を許す
		Config::set('application.language', $default);
	}
	else {
		// 最初にデフォルト言語をセットしておく
		Config::set('application.language', $default);

		// ブラウザの言語設定を読み込む
		$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

		// $langs[言語コード] = プライオリティの形式に変換
		$langs = array();
		foreach ( $lang as $key => $code_priority ) {
			if ( preg_match('/(.+);q=([0-9\.]+)/', $code_priority, $matched) === 1 ) {
				$langs[$matched[1]] = $matched[2];
			}
			else {
				$langs[$code_priority] = '1'; // デフォルト値
			}
		}
		// プライオリティ（配列の値）でソート
		arsort($langs);

		// サポート言語として存在するかチェック
		foreach ( $langs as $code => $priority ) {
			if ( in_array($code, Config::get('language.support')) ) {
				Config::set('application.language', $code);
				break;
			}
		}
	}
});

Route::filter('after', function ($response) {
	// アプリケーションに対する全てのリクエストの後に行うコードをここに記述
	// 例えばコンテンツ出力中の'Laravel'を'FuelPHP'に変換したければ
	// if ( in_array($response->status(), array('200'))) {
	// 	$response->content = str_replace('Laravel', 'FuelPHP', $response->content);
	// }
});

Route::filter('csrf', function () {
	if ( Request::forged() ) return Response::error('500');
});
