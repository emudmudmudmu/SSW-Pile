<?php

/**
 * 例外とログ出力日本語化
 *
 * ソースファイルを書き換え、例外とログ出力を日本語で表示するようにする。
 *
 * laravelフォルダー下のコアファイルを読み、メッセージ部分のみ日本語に変換し、
 * laravel-jaフォルダーに生成ファイルを出力する。
 *
 * 実行：
 * 　１．コマンドラインから：artisan japanize
 * 　２．生成されたlaravel-jaフォルダーの中のソースがエラーになっていないかチェック
 * 　３．laravelフォルダーをlaravel-enなどにリネーム
 * 　４．laravel-jaフォルダーをlaravelへリネーム
 */
class Japanize_Task extends Task
{
	/**
	 * 英日変換配列
	 *
	 * @var array
	 */
	private static $reps = array(
		// 例外メッセージの生成 Errorクラス
		'Error rendering view: [' => 'エラーレンダリングビュー:[',
		'<h2>Unhandled Exception</h2>' => '<h2>処理されていない例外</h2>',
		'<h3>Message:</h3>' => '<h3>メッセージ:</h3>',
		'<h3>Location:</h3>' => '<h3>場所:</h3>',
		'<pre>".$file." on line ".$exception->getLine()."</pre>"' => '<pre>".$file."(".$exception->getLine()."行目)</pre>"',
		'<h3>Stack Trace:</h3>' => '<h3>スタックトレース:</h3>',
		// ログメッセージの生成 Logクラス
		'$e->getMessage().\' in \'.$e->getFile().\' on line \'.$e->getLine()' => '$e->getMessage().\'(ファイル:\'.$e->getFile().\' \'.$e->getLine().\'行目)\'',
		// 例外メッセージ
		'Asset [$asset] is dependent on itself.' => 'アセット[$asset]が、自分自身に依存しています。',
		'Assets [$asset] and [$dependency] have a circular dependency.' => 'アセット[$asset]と[$dependency]が回帰的に依存しています。',
		'Auth driver {$driver} is not supported.' => '認証ドライバー{$driver}はサポートされていません。',
		'Bundle [$bundle] has not been installed.' => 'バンドル[$bundle]はインストールされていません。',
		'Cache driver {$driver} is not supported.' => 'キャッシュドライバー{$driver}はサポートされていません。',
		'Attempting to set secure cookie over HTTP.' => 'HTTPを使用しているのにセキュアクッキーを設定しようとしています。',
		'Decryption error. Padding is invalid.' => '解読エラー。パディングが無効です。',
		'Method [$method] is not defined on the Query class.' => 'メソッド[$method]はクエリークラスで定義されていません。',
		'Schema operations not supported for [$driver].' => 'ドライバー[$driver]はスキーマ操作をサポートしていません。',
		'Database connection is not defined for [$connection].' => '[$connection]のデータベース接続は定義されていません。',
		'Database driver [$driver] is not supported.' => 'データベースドライバー[$driver]はサポートされていません。',
		'Method [$method] does not exist.' => 'メソッド[$method]は存在していません。',
		'Resolution target [$type] is not instantiable.' => '解決対象の[$type]はインスタンス化できません。',
		'Unresolvable dependency resolving [$parameter].' => '[$parameter]の依存性が解決できません。',
		'Could not establish memcached connection.' => 'Memcached接続が確立できません。',
		'A session driver must be set before setting flash data.' => 'フラッシュデーターを使用する前に、セッションドライバーを設定してください。',
		'Redis database [$name] is not defined.' => 'Redisデータベース[$name]は定義されていません。',
		'Redis error: ' => 'Redisエラー:',
		"Unknown Redis response: " => '未知のRedisレスポンス:',
		'Error making Redis connection: {$error} - {$message}' => 'Redis接続でのエラー: {$error} - {$message}',
		'Session driver [$driver] is not supported.' => 'セッションドライバー[$driver]はサポートされていません。',
		'A driver must be set before using the session.' => 'セッションを使用する前にドライバーを設定してください。',
		'Invalid random string type [$type].' => '不正なランダム文字列タイプ:[$type]',
		'Error creating URL for undefined route [$name].' => '未定義なルート[$name]のためURL生成でエラー発生',
		'Method [$method] does not exist.' => 'メソッド[$method]は存在していません。',
		'View [$view] doesn\'t exist.' => 'ビュー[$view]は存在していません。',
		'Method [$method] is not defined on the View class.' => 'メソッド[$method]はViewクラスで定義されていません。',
		// コマンドラインのタスク
		// コマンドラインは設定を行わないと日本語の表示ができない場合が多いため
		// コメントにしてあります。

		/* 	'Sorry, I can\'t find that task.' => 'このタスクを見つけられませんでした。',
		  'Sorry, I can\'t find that method!' => 'このメソッドを見つけられませんでした。',
		  'You forgot to provide the task name.' => 'タスク名の指定が行われていません。',
		  'There is no bundle named [$bundle].' => '[$bundle]というバンドル名が見つかりませんでした。',
		  "The bundle API is not responding." => 'バンドルAPIの反応がありません。',
		  "Error downloading the requested bundle." => 'リクエストしたバンドルのダウンロードでエラーです。',
		  'I need to know what to name the migration.' => 'マイグレーション名を指定してください。',
		  'Please specify a request method and URI.'=>'メソッドとURIを指定してください。', */
	);

	public function run()
	{
		self::japanize(path('sys'), path('sys').'../laravel-ja');
	}

	/**
	 * 日本語化本体
	 * 再帰的に実行する
	 *
	 * @param type $dirname
	 */
	private static function japanize($dirname, $destpath)
	{
		if(! is_dir($destpath)) mkdir($destpath, 0777, true);

		$items = new FilesystemIterator($dirname, FilesystemIterator::SKIP_DOTS);

		foreach ( $items as $item ) {
			$dest = $destpath . DS . $item->getFilename();
			if ( $item->isDir() ) {
				if (! is_dir($dest)) mkdir($dest, 0777, true);
				self::japanize($item->getRealPath(), $dest);
			}
			else {
				if ( ends_with($item->getRealPath(), '.php') ) {
					$content = File::get($item->getRealPath());
					$ja_code = strtr($content, self::$reps);
					File::put($dest, $ja_code);
				} else {
					copy($item->getRealPath(), $dest);
				}
			}
		}
	}

}