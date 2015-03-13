<?php

return array(
	
	
	/*
	  |--------------------------------------------------------------------------
	  | Application Version
	  |--------------------------------------------------------------------------
	  | 
	  | アプリケーションのリリースバージョンもしくはリビジョンを記載する
	 */

	'version'   => '215',
	/*
	  |--------------------------------------------------------------------------
	  | Application URL
	  |--------------------------------------------------------------------------
	  |
	  | The URL used to access your application without a trailing slash. The URL
	  | does not have to be set. If it isn't we'll try our best to guess the URL
	  | of your application.
	  |
	 */

	// URLは自動的に求められるが、サーバーの設定ミスがあるとトラブルが起きることもある。
	// そのため、本番環境では、生成されるURLのベースを指定したほうが、より安全

	'url'       => '/private/',
	/*
	  |--------------------------------------------------------------------------
	  | Asset URL
	  |--------------------------------------------------------------------------
	  |
	  | The base URL used for your application's asset files. This is useful if
	  | you are serving your assets through a different server or a CDN. If it
	  | is not set, we'll default to the application URL above.
	  |
	 */

	// CDNを用い、画像などのアセットを他のサーバーから読み込むときに設定する。

	'asset_url' => '',
	/*
	  |--------------------------------------------------------------------------
	  | Application Index
	  |--------------------------------------------------------------------------
	  |
	  | If you are including the "index.php" in your URLs, you can ignore this.
	  | However, if you are using mod_rewrite to get cleaner URLs, just set
	  | this option to an empty string and we'll take care of the rest.
	  |
	 */

	// ApacheのModRewriteが動作する場合、index.phpをURLから省ける
	// その場合は、indexを空文字列にする。
	// ModRewirteが動作しない場合は、'index.php'を指定する。

	'index'     => '',
	/*
	  |--------------------------------------------------------------------------
	  | Application Key
	  |--------------------------------------------------------------------------
	  |
	  | This key is used by the encryption and cookie classes to generate secure
	  | encrypted strings and hashes. It is extremely important that this key
	  | remain secret and should not be shared with anyone. Make it about 32
	  | characters of random gibberish.
	  |
	 */

	// 32文字のでたらめな文字列。数字と英字で構成すること。

	'key'       => 'Pu8BztfwndzVUYLYnmxzY9Tnbdat92jW',
	/*
	  |--------------------------------------------------------------------------
	  | Profiler Toolbar
	  |--------------------------------------------------------------------------
	  |
	  | Laravel includes a beautiful profiler toolbar that gives you a heads
	  | up display of the queries and logs performed by your application.
	  | This is wonderful for development, but, of course, you should
	  | disable the toolbar for production applications..
	  |
	 */

	// プロファイラーの表示、実働環境なので当然表示しない。

	'profiler'  => false,
	/*
	  |--------------------------------------------------------------------------
	  | Application Character Encoding
	  |--------------------------------------------------------------------------
	  |
	  | The default character encoding used by your application. This encoding
	  | will be used by the Str, Text, Form, and any other classes that need
	  | to know what type of encoding to use for your awesome application.
	  |
	 */

	'encoding'  => 'UTF-8',
	/*
	  |--------------------------------------------------------------------------
	  | Application Language
	  |--------------------------------------------------------------------------
	  |
	  | The default language of your application. This language will be used by
	  | Lang library as the default language when doing string localization.
	  |
	 */

	'language'  => 'ja',
	/*
	  |--------------------------------------------------------------------------
	  | Supported Languages
	  |--------------------------------------------------------------------------
	  |
	  | These languages may also be supported by your application. If a request
	  | enters your application with a URI beginning with one of these values
	  | the default language will automatically be set to that language.
	  |
	 */
	/*
	 * この配列は、URLの中でドメイン名直後に言語コードを指定し、切り換える場合に
	 * 使用する。例えばsample.com/en/topで英語表示sample.com/ja/topで日本語表示と
	 * なる。この機能で使用する言語コードを配列で指定する。
	 * この機能を使用しない場合、空配列のままにしておく。
	 */

	'languages' => array(),
	/*
	  |--------------------------------------------------------------------------
	  | SSL Link Generation
	  |--------------------------------------------------------------------------
	  |
	  | Many sites use SSL to protect their users data. However, you may not be
	  | able to use SSL on your development machine, meaning all HTTPS will be
	  | broken during development.
	  |
	  | For this reason, you may wish to disable the generation of HTTPS links
	  | throughout your application. This option does just that. All attempts
	  | to generate HTTPS links will generate regular HTTP links instead.
	  |
	 */

	'ssl'       => true,
	/*
	  |--------------------------------------------------------------------------
	  | Application Timezone
	  |--------------------------------------------------------------------------
	  |
	  | The default timezone of your application. The timezone will be used when
	  | Laravel needs a date, such as when writing to a log file or travelling
	  | to a distant star at warp speed.
	  |
	 */

	'timezone'  => 'Asia/Tokyo',
	/*
	  |--------------------------------------------------------------------------
	  | Class Aliases
	  |--------------------------------------------------------------------------
	  |
	  | Here, you can specify any class aliases that you would like registered
	  | when Laravel loads. Aliases are lazy-loaded, so feel free to add!
	  |
	  | Aliases make it more convenient to use namespaced classes. Instead of
	  | referring to the class using its full namespace, you may simply use
	  | the alias defined here.
	  |
	 */

	// クラスのエイリアス
	// Laravelではクラスのエイリアスはユーザーが自由に定義できる

	'aliases'   => array(
		'Auth'          => 'Laravel\\Auth',
		'Authenticator' => 'Laravel\\Auth\\Drivers\\Driver',
		'Asset'         => 'Laravel\\Asset',
		'Autoloader'    => 'Laravel\\Autoloader',
		'Blade'         => 'Laravel\\Blade',
		'Bundle'        => 'Laravel\\Bundle',
		'Cache'         => 'Laravel\\Cache',
		'Config'        => 'Laravel\\Config',
		'Controller'    => 'Laravel\\Routing\\Controller',
		'Cookie'        => 'Laravel\\Cookie',
		'Crypter'       => 'Laravel\\Crypter',
		'DB'            => 'Laravel\\Database',
		'Eloquent'      => 'Laravel\\Database\\Eloquent\\Model',
		'Event'         => 'Laravel\\Event',
		'File'          => 'Laravel\\File',
		'Filter'        => 'Laravel\\Routing\\Filter',
		'Form'          => 'Laravel\\Form',
		'Functions'     => 'Masters\\Functions',
		'Hash'          => 'Laravel\\Hash',
		'HTML'          => 'Laravel\\HTML',
		'Input'         => 'Laravel\\Input',
		'IoC'           => 'Laravel\\IoC',
		'Lang'          => 'Laravel\\Lang',
		'Log'           => 'Laravel\\Log',
		'Memcached'     => 'Laravel\\Memcached',
		'Paginator'     => 'Laravel\\Paginator',
		'Profiler'      => 'Laravel\\Profiling\\Profiler',
		'URL'           => 'Laravel\\URL',
		'Redirect'      => 'Laravel\\Redirect',
		'Redis'         => 'Laravel\\Redis',
		'Request'       => 'Laravel\\Request',
		'Response'      => 'Laravel\\Response',
		'Route'         => 'Laravel\\Routing\\Route',
		'Router'        => 'Laravel\\Routing\\Router',
		'Schema'        => 'Laravel\\Database\\Schema',
		'Section'       => 'Laravel\\Section',
		'Session'       => 'Laravel\\Session',
		'Str'           => 'Laravel\\Str',
		'Task'          => 'Laravel\\CLI\\Tasks\\Task',
		'URI'           => 'Laravel\\URI',
		'Validator'     => 'Laravel\\Validator',
		'View'          => 'Laravel\\View',
	),

);
