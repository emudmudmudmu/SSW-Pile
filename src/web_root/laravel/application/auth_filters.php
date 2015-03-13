<?php
use Laravel\Redirect;
use Laravel\Log;
use Laravel\View;

/* 指定施工会社専用ページ */
Route::filter('pattern: construction/*', 'construction');
Route::filter('construction', function () {
	$user = Session::get(KEY_USER);
	if ( !$user ) {
		// ログインしていない場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ003");
		return Redirect::to_action('home@index');
	}
	if ( $user->auth_type != AUTH_MEMBER && $user->auth_type != AUTH_SYSADMIN  ) {
		// 一般権限またはシステム管理者権限以外の場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ004");
		return Redirect::to_action('home@index');
	}
});


/* 事務局専用ページ */
Route::filter('pattern: office/*', 'office');
Route::filter('office', function () {
	$user = Session::get(KEY_USER);
	if ( !$user ) {
		// ログインしていない場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ003");
		return Redirect::to_action('home@index');
	}
	if ( $user->auth_type != AUTH_MANAGER && $user->auth_type != AUTH_SYSADMIN  ) {
		// 理事権限またはシステム管理者権限以外の場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ004");
		return Redirect::to_action('home@index');
	}
});

/* 出荷担当専用ページ */
Route::filter('pattern: parts/*', 'parts');
Route::filter('parts', function () {
	$user = Session::get(KEY_USER);
	if ( !$user ) {
		// ログインしていない場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ003");
		return Redirect::to_action('home@index');
	}
	if ( $user->auth_type != AUTH_SHIPPING && $user->auth_type != AUTH_SYSADMIN  ) {
		// 出荷担当権限またはシステム管理者権限以外の場合
		Session::put(KEY_WARN, TRUE);
		Session::put(KEY_MSG_CODE, "MZ004");
		return Redirect::to_action('home@index');
	}
});