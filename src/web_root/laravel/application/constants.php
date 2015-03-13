<?php
/**
 * 定数定義
 *
 * $Author: mizoguchi $
 * $Rev: 116 $
 * $Date: 2013-10-01 07:54:55 +0900 (2013/10/01 (火)) $
 */
/* プロファイラ */
define('LARAVEL_MEMORY', memory_get_usage());

/* 一般 */
define('LF', "\n");
$___timestamp = microtime();
$___timestamp_str = explode(' ', $___timestamp);
define('REQUESTED_DATE',    date('Y-m-d H:i:s', $___timestamp_str[1]));
define('REQUESTED_DTM',     REQUESTED_DATE . substr($___timestamp_str[0], 1));

/* キャッシュ間隔設定 */
define('CACHE_INTERVAL_FUNCTIONS', 5);

/* セッションキー */
define('KEY_ERROR',     1); // 画面メッセージ(エラー)
define('KEY_WARN',      2); // 画面メッセージ(ワーニング)
define('KEY_INFO',      3); // 画面メッセージ(インフォ)
define('KEY_SUCCESS',   4); // 画面メッセージ(完了)
define('KEY_SCRIPT',    5); // 画面読み込み時に実行するJavaScriptコード
define('KEY_USER',      6); // ログインユーザー情報(sswm_userのレコード)
define('KEY_FORM',      7); // 入力フォームの一時保存用
define('KEY_MSG_CODE',  8); // 画面メッセージコード
define('KEY_ARGS',      9); // 画面メッセージ用引数

/* エラーコード(内部使用) */
define('ERR_DUPLICATE_LOGIN_ID', 99999001); // ログインIDに重複がある場合

/* 基本情報（sswm_basicinfo）のキー */
define('BI_ASSOCIATION_NAME',    1); // 協会名称
define('BI_ZIP1',                2); // 郵便番号1
define('BI_ZIP2',                3); // 郵便番号2
define('BI_ADDRESS',             4); // 住所
define('BI_ADMIN_NAME',          5); // 会社名
define('BI_TEL',                 6); // 電話番号
define('BI_FAX',                 7); // FAX番号
define('BI_PERSON',              8); // 担当
define('BI_EMAIL',               9); // メールアドレス
define('BI_SHIPPING_COMPANY',   10); // パーツ出荷場所
define('BI_BANK_NAME',          11); // 銀行名
define('BI_BANK_BRANCH_NAME',   12); // 支店名
define('BI_BANK_AC_TYPE',       13); // 預金種別
define('BI_BANK_AC_NUMBER',     14); // 口座番号
define('BI_BANK_AC_HOLDER',     15); // 名義人

/* 会社区分 */
define('COMPANY_MANAGER',       1); // 理事会社
define('COMPANY_JOINT',         2); // 共同開発会社
define('COMPANY_MEMBER',        3); // 指定施工会社

/* 権限 */
define('AUTH_MANAGER',          1); // 理事
define('AUTH_SHIPPING',         2); // 出荷担当
define('AUTH_MEMBER',           3); // 一般
define('AUTH_SYSADMIN',         9); // システム管理者

/* 物件ステータス */
define('CONSTRUCT_ESTIMATE',    0); // 見込み
define('CONSTRUCT_ORDERED',     1); // 受注済
define('CONSTRUCT_COMPLETE',    9); // 完了

/* 種別 */
define('SYBT_4GO',              1); // 四号建築物
define('SYBT_GAKKAI',           2); // 学会小規模指針
define('SYBT_OTHER',            3); // その他
define('SYBT_KOSAKU',           4); // 工作物

/* 種別2 */
define('SYBT2_YOHEKI',          1); // 擁壁
define('SYBT2_KOKOKU',          2); // 広告塔
define('SYBT2_TETU',            3); // 鉄塔
define('SYBT2_OTHER',           4); // その他

/* 構造 */
define('KOZO_MOKU',             1); // 木造
define('KOZO_S',                2); // S造
define('KOZO_RC',               3); // RC造
define('KOZO_KENCHI',           4); // 間知石造
define('KOZO_CB',               5); // CB造

/* 基礎形式 */
define('KISO_DOKURITU',         1); // 独立
define('KISO_BETA',             2); // べた
define('KISO_NUNO',             3); // 布

/* 品目 */
define('HINMOKU_LICENSE',       1); // 工法使用料
define('HINMOKU_ITEM',          2); // パーツ代金
define('HINMOKU_SHIPPING',      3); // 運賃

/* 仕様 */
define('WELDING_NORMAL',        1); // 正回転仕様
define('WELDING_REVERSE',       2); // 逆回転仕様
define('WELDING_NO',            3); // 接合なし

/* 受注ステータス */
define('ORDER_RECEIPT',         1); // 未出荷
define('ORDER_SHIPPED',         2); // 出荷済
define('ORDER_CANCELED',        9); // キャンセル

/* 請求情報の検索結果タイプ */
define('RESULT_TYPE_BILL',      1); // 請求テーブルからの検索結果
define('RESULT_TYPE_CONST',     2); // 物件テーブルからの検索結果
define('RESULT_TYPE_ORDER',     3); // 受注テーブルからの検索結果

/* 請求関連の文字定数 */
define('LABEL_LICENSEFEE', 'SSW-Pile工法使用料');
define('LABEL_PARTS',      'SSW-Pile先端翼');
define('LABEL_SHIPPING',   '運賃');