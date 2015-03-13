<?php
header("P3P: CP='UNI CUR OUR'");
//ini_set('display_errors', '1');
//error_reporting(0);
//error_reporting(E_ALL);
// --- 設定部 ------------------------------------------------------------------
// --- 必ず書き換える項目 ------------------------------------------------------
// --- メールの送信先 ----------------------------------------------------------
$sitename = 'SSW-Pile';
//$mail_to = 'wp@hp-shop.jp';
//$mail_to = 'sswpile@narajuki.jp';
$mail_to = 'info@ssw-pile.jp';
//$cc = 'wp@hp-shop.jp';
$return_path = '';
$subject = '【SSW-Pile】お問い合わせがありました';
$title = 'お問い合わせを送信しました';
$default_encode = 'UTF-8';

// --- 必要に応じて書き換える項目 ----------------------------------------------
// 送信完了ページを別ページにリダイレクトする場合はURLを書いてください。
// このスクリプトで送信完了ページを出力する場合は空欄です（デフォルト 空欄）
$redirect = '';

// --- プログラム部 ------------------------------------------------------------
// --- 初期化 ------------------------------------------------------------------
$prefArray = array('選択してください',
'北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
'東京都','神奈川県','埼玉県','千葉県','茨城県','栃木県','群馬県','山梨県',
'新潟県','長野県','富山県','石川県','福井県',
'愛知県','岐阜県','静岡県','三重県',
'大阪府','兵庫県','京都府','滋賀県','奈良県','和歌山県',
'鳥取県','島根県','岡山県','広島県','山口県',
'徳島県','香川県','愛媛県','高知県',
'福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'
);

session_start();

// magic quotes
if (get_magic_quotes_gpc()) {
	foreach($_POST as $key => $val) {
		$_POST[$key] = stripslashes($val);
	}
}


$vars = array();
$errors = array();

if (isset($_POST['act']) && $_POST['act'] == 'confirm'){
	$vars =& $_POST;
	convertVars();
	validateVars();
	if (count($errors) > 0) {
		$view = 'input';
	} else {
		$_SESSION = $vars;
		$view = 'confirm';
	}

} else if (isset($_POST['act']) && $_POST['act'] == 'done') {
	$vars =& $_SESSION;
	if (isset($_POST['back_to_input']) || $_POST['ssid'] != session_id()) {
		$view = 'input';
	} else {
		validateVars();
		if (count($errors) > 0) {
			$view = 'input';
		} else {
			$body = mb_convert_encoding(createBody(), 'JIS', $default_encode );

			$subject = '=?iso-2022-jp?B?' . base64_encode( mb_convert_encoding($subject, 'JIS', $default_encode ) ) . '?=';
			$headers = 'From: <' . $vars['email1'] . ">";

			if ($cc !='') {$to=$mail_to.','.$cc;}else{$to=$mail_to;}

			if ($return_path) {
				mail( $mail_to, $subject, $body, $headers, "-f {$return_path}");
			} else {
				mail( $mail_to, $subject, $body, $headers );
			}

			// --- Ccも送信
			if (isset($_POST['copy'])) mail( $_POST['email1'], $subject,$body, $headers );

// --- 自動返信
$body2 = mb_convert_encoding(createBodyReturn(), 'JIS', $default_encode );
$subject2 = '【'.$sitename.'】'.$title;
$subject2 = '=?iso-2022-jp?B?' . base64_encode( mb_convert_encoding($subject2, 'JIS', $default_encode ) ) . '?=';
$headers2 = 'From: <' . $mail_to . ">";
if ($return_path) {
  mail( $vars['email1'], $subject2, $body2, $headers2, "-f {$return_path}");
} else {
  mail( $vars['email1'], $subject2, $body2, $headers2 );
}


			$_SESSION = array();
			if ( $redirect != '' ) {
				header( "Location: $redirect" );
				exit;
			} else {
				$view = 'done';
			}
		}
	}
} else {
	$view = 'input';
	$vars = $_SESSION;
}
//-------------------------------------------------------------------------------------------------------------includeフォームhtml
?>
<?php if ($view == 'input'):?>
	<?php include('inputForm.php')?>
<?php elseif($view == 'confirm'):?>
	<?php include('confirm.php')?>
<?php else:?>
	<?php include('complete.html')?>
<?php endif;?>
<?php
//-------------------------------------------------------------------------------------------------------------function群
/**
 * 入力値の変換
 */
function convertVars() {
	global $vars;
	global $default_encode;

	$vars['kana'] = mb_convert_kana($vars['kana'], "KC", $default_encode);
//--コメント解放しない--	$vars['kana'] = mb_convert_kana($vars['kana'], "Hc", $default_encode);ひらがな
	$vars['yubin1'] = mb_convert_kana($vars['yubin1'], "n", $default_encode);
	$vars['yubin2'] = mb_convert_kana($vars['yubin2'], "n", $default_encode);
	$vars['email1'] = mb_convert_kana($vars['email1'], "a", $default_encode);
	$vars['email2'] = mb_convert_kana($vars['email2'], "a", $default_encode);
	$vars['tel'] = mb_convert_kana($vars['tel'], "a", $default_encode);
	//$vars['fax'] = mb_convert_kana($vars['fax'], "a", $default_encode);
	if ($vars['address1'] == '選択してください') {$vars['address1'] = '';}
}

function validateVars() {
	global $vars;
	global $errors;

/*
	if ($vars['item'] == '選択してください')
		$errors['item'] = 'お問い合わせ項目を選択してください';
*/
	if (!validateNotEmpty('name')) {
		$errors['name'] = 'お名前が入力されていません';
	}
	if (!validateNotEmpty('kana')) {
		$errors['kana'] = 'フリガナが入力されていません';
	} else if (!validateKana('kana')) {//else if (!validateHira('kana')) {
		$errors['kana'] = '全角カタカナで入力してください';
	}

	$vars['yubin'] = $vars['yubin1'] . '-' . $vars['yubin2'];
	if (validateNotEmpty('yubin') && !validateZip('yubin')) {
		$error['yubin'] = '郵便番号が正しくありません';
	}

	if (!validateNotEmpty('email1')) {
		$errors['email1'] = 'メールアドレスが入力されていません';
	} else if (!validateEmail('email1')) {
		$errors['email1'] = 'メールアドレスが正しくありません';
	} else if (!validateNotEmpty('email2')) {
		$errors['email2'] = 'メールアドレス(再入力)が入力されていません';
	} else if ($vars['email1'] != $vars['email2']) {
		$errors['email2'] = 'メールアドレスが一致しません。';
	}

	if (!validateNotEmpty('body')) {
		$errors['body'] = '問い合わせ内容が入力されていません';
	}

	if (validateNotEmpty('tel') && !validateTel('tel'))
		$errors['tel'] = '電話番号が正しくありません';
/*
	if (validateNotEmpty('fax') && !validateTel('fax'))
		$errors['fax'] = 'FAX番号が正しくありません';
*/

}




function _h($value) {
	global $default_encode;
	return htmlspecialchars($value, ENT_QUOTES, $default_encode);
}

/**
 * postで送られた値を出力
 * @param unknown_type $key
 */
function eh($key) {
	global $vars;
	global $default_encode;
	if (isset($vars[$key])) {
		echo htmlspecialchars($vars[$key], ENT_QUOTES, $default_encode);
	}
}

/**
 * checked出力
 * @param unknown_type $key
 * @param unknown_type $value
 */
function checked($key, $value) {
	global $vars;
	if (isset($vars[$key])) {
		if (is_array($vars[$key]) && in_array($value, $vars[$key])) {
			echo ' checked="checked"';
		} else if ($vars[$key] == $value) {
			echo ' checked="checked"';
		}
	}
}

/**
 * selected出力
 * @param unknown_type $key
 * @param unknown_type $value
 */
function selected($key, $value) {
	global $vars;
	if (isset($vars[$key]) && $vars[$key] == $value) {
		echo ' selected="selected"';
	}
}

/**
 * エラー出力
 * @param unknown_type $key
 */
function err($key) {
	global $errors;
	global $default_encode;
	if (isset($errors[$key])) {
		echo '<div class="error">' . htmlspecialchars($errors[$key], ENT_QUOTES, $default_encode) . '</div>';
	}
}


/**
 * 必須項目検証
 * @param string $key 項目キー
 */
function validateNotEmpty($key) {
	global $vars;
	if (empty($vars[$key])) {
		return false;
	}
	return true;
}

/**
 * メールアドレス検証
 * @param string $key 項目キー
 */
function validateEmail($key) {
	global $vars;
	$email = isset($vars[$key])? $vars[$key]: '';
	if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/", $email)) {
		return true;
	}
	return false;
}

/**
 * カタカナ検証
 * @param string $key
 */
function validateKana($key) {
	global $vars;
	global $default_encode;
	switch($default_encode){
		case 'EUC-JP':
		case 'euc-jp':
			$conv = "/^(\xa5[\xa1-\xf6]|\xa1[\xb3\xb4\xbc])+$/";
			break;
		case 'utf-8':
		case 'UTF-8':
			$conv = "/^[ァ-ヶー　]+$/u";
	}
	$value = isset($vars[$key])? $vars[$key]: '';
	if(preg_match($conv,$value)){
		return true;
	}
	return false;
}
/**
 * ひらがな検証
 * @param string $key
 */
function validateHira($key) {
	global $vars;
	global $default_encode;
	switch($default_encode){
		case 'EUC-JP':
		case 'euc-jp':
			$conv = "/^(\xa5[\xa1-\xf6]|\xa1[\xb3\xb4\xbc])+$/";
			break;
		case 'utf-8':
		case 'UTF-8':
			$conv = "/^[ぁ-ゞー　]+$/u";
	}
	$value = isset($vars[$key])? $vars[$key]: '';
	if(preg_match($conv,$value)){
		return true;
	}
	return false;
}

/**
 * 郵便番号検証
 * @param string $key
 */
function validateZip($key) {
	global $vars;
	$value = isset($vars[$key])? $vars[$key]: '';
	if (preg_match("/^\d{3}\-\d{4}$/", $value)) {
		return true;
	}
	return false;
}

/**
 * 電話番号検証
 * @param string $key
 */
function validateTel($key) {
	global $vars;
	$value = isset($vars[$key])? $vars[$key]: '';
	if (preg_match("/^\d{2,5}-?\d{1,4}-?\d{2,4}$/", $value)) {
		return true;
	}
	return false;
}


/**
 * メール本文を生成
 */
function createBody() {
	global $vars;

	$body = "

お名前　　　　　：{$vars['name']}
フリガナ　　　　：{$vars['kana']}

会社名　　　　　：{$vars['c_name']}

住所　　　　　　：〒　{$vars['yubin1']}-{$vars['yubin2']}
　　　　　　　　　{$vars['address1']}{$vars['address2']}

電話番号　　　　：{$vars['tel']}

e-mail　　　　　：{$vars['email1']}

お問い合わせ内容：
{$vars['body']}

―――――――――――――――――――――――――――――――――――
";

	return $body;
}
function createBodyReturn(){
  $body = createBody();
  $body = "
お問い合わせありがとうございました。
以下の内容にてメールを送信致しました

―――――――――――――――――――――――――――――――――――
".$body."


========================================

【SSW-Pile工法協会 事務局】
　株式会社奈良重機工事

	〒458-0023
　愛知県名古屋市緑区鴻仏目1-115

　TEL：052-877-8281
	E-mail sswpile@narajuki.jp

========================================


";

return $body;
}

?>