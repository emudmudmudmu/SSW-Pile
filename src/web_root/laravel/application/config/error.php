<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Ignored Error Levels
	|--------------------------------------------------------------------------
	|
	| Here you simply specify the error levels that should be ignored by the
	| Laravel error handler. These levels will still be logged; however, no
	| information about about them will be displayed.
	|
	*/

	//	エラー出力させないエラーレベルを指定

	'ignore' => array(),

	/*
	|--------------------------------------------------------------------------
	| Error Detail
	|--------------------------------------------------------------------------
	|
	| Detailed error messages contain information about the file in which an
	| error occurs, as well as a PHP stack trace containing the call stack.
	| You'll want them when you're trying to debug your application.
	|
	| If your application is in production, you'll want to turn off the error
	| details for enhanced security and user experience since the exception
	| stack trace could contain sensitive information.
	|
	*/

	// 実働環境であるから、エラー発生時にメッセージを表示しない。
	// 発生時にはデフォルトの500ページが表示される。
	// エラーの内容はログされる。

	'detail' => false,

	/*
	|--------------------------------------------------------------------------
	| Error Logging
	|--------------------------------------------------------------------------
	|
	| When error logging is enabled, the "logger" Closure defined below will
	| be called for every error in your application. You are free to log the
	| errors however you want. Enjoy the flexibility.
	|
	*/

	'log' => true,

	/*
	|--------------------------------------------------------------------------
	| Error Logger
	|--------------------------------------------------------------------------
	|
	| Because of the various ways of managing error logging, you get complete
	| flexibility to manage error logging as you see fit. This function will
	| be called anytime an error occurs within your application and error
	| logging is enabled.
	|
	| You may log the error message however you like; however, a simple log
	| solution has been setup for you which will log all error messages to
	| text files within the application storage directory.
	|
	*/

	'logger' => function($exception)
	{
		// 設定ファイルにエラーがある場合、Logクラスのエイリアスが
		// その時点で定義されておらずエラーになることがある。
		// それを防ぐため、完全修飾名に変更

		\Laravel\Log::exception($exception);
	},

);