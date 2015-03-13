$Author: mizoguchi $
$Rev: 18 $
$Date: 2013-09-18 15:56:50 +0900 (2013/09/18 (水)) $

■注意
  
  次のファイル・ディレクトリは、Webサーバーと同期を取らない事。
  （WinSCP等で、除外ファイル・ディレクトリに指定しておく）
  
  .settings/              Eclipseで使用
  .buildpath              Eclipseで使用
  .project                Eclipseで使用
  README.txt              このファイル
  log/                    ログなので上書きしない
  trash/                  ゴミ箱なので上書きしない
  
  
■主なディレクトリ
  
  _smartrelease_except/   不明。ホスティングが使用？
  
  conf/                   「お問い合わせ」フォーム用のPHP設定を格納
  
  html/                   公開環境ドキュメントルート
  
    private/              物件管理システムの公開ドキュメントルート
    css/                  一部、物件管理システムで使用するCSSあり
    images/private        一部、物件管理システムで使用する画像あり
  
  laravel/                物件管理システム本体（配下の構造についてはLaravel3のドキュメントを参照の事）
  
  log/                    Webサーバーのログ格納場所
  
  trash/                  WinSCP でリモートファイル・ディレクトリを削除した際に移動先になるゴミ箱
  
