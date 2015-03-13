<?php

namespace Utils;

use Laravel\View;
use Laravel\Config;
use Laravel\Bundle;
use Laravel\Event;
use Laravel\Log;

class MailService {

	/**
	 * メール送信を行ないます。
	 *
	 * @param string $to       宛先アドレス（文字列中"<", ">" がある場合は、"ユーザー名 <メールアドレス>"と解釈する）
	 * @param string $subject  件名
	 * @param string $template テンプレートID
	 * @param array  $params   テンプレートに埋め込むパラメータ(連想配列)
	 * @param string $from     From (falseに該当する値の場合は、application.php の email.from を使用。ユーザー名については $to と同じ)
	 * @param string $cc       Cc (falseに該当する値の場合は、CCを設定しない。ユーザー名については $to と同じ。,区切りで複数指定可能)
	 */
	public static function send_mail($to, $subject, $template, $params, $from = NULL, $cc = NULL) {
		// メール送信バンドルの起動
		Bundle::start('swiftmailer');
		
		// メール本文の作成
		$body = View::make($template, $params)->render();
		$body = self::convert_chars($body);
		
		// 設定値の取得
		$host        = Config::get('email.host');
		$port        = Config::get('email.port');
		$user        = Config::get('email.user');
		$pass        = Config::get('email.pass');
		$encode      = Config::get('email.encode');
		$bcc         = Config::get('email.bcc');
		$return_path = Config::get('email.return');
		if ( !$from ) {
			$from = Config::get('email.from');
		}

		// swiftmailerのインスタンス作成
		$smtp = \Swift_SmtpTransport::newInstance($host, $port)
			->setUsername($user)
			->setPassword($pass);

		$mailer = \Swift_Mailer::newInstance($smtp);

		// 文字エンコード変換
		$from    = self::parse_address($from, $encode);
		$to      = self::parse_address($to, $encode);
		$subject = mb_encode_mimeheader($subject, $encode, 'B', ''); // Base64中に不要な改行コードを挿入しない
		$body    = mb_convert_encoding($body, $encode, 'UTF-8');

		// メッセージ作成
		$message = \Swift_Message::newInstance();
		$message
			->setMaxLineLength(0)
			->setCharset($encode)
			->setEncoder(\Swift_Encoding::get7BitEncoding())
			->setSubject($subject)
			->setFrom($from)
			->setTo($to)
			->setReturnPath($return_path)
			->setBody($body, 'text/plain');

		if ( $bcc ) {
			$ccs = explode(',', $bcc);
			foreach ( $ccs as $addr ) {
				$ret = self::parse_address($addr, $encode);
				if ( is_array($ret) ) {
					foreach ( $ret as $k => $v ) {
						$message->addBcc($k, $v);
					}
				}
				else {
					$message->addBcc($ret);
				}
			}
		}

		if ( $cc ) {
			$ccs = explode(',', $cc);
			foreach ( $ccs as $addr ) {
				$ret = self::parse_address($addr, $encode);
				if ( is_array($ret) ) {
					foreach ( $ret as $k => $v ) {
						$message->addCc($k, $v);
					}
				}
				else {
					$message->addCc($ret);
				}
			}
		}

		// メール送信
		$mailer->send($message);
	}

	/**
	 * メールアドレスを解析します。
	 * 引数に <> を含む場合は、array('メールアドレス' => 'ユーザー名')に変換します。
	 *
	 * 例）
	 * テスト２ <test02@ssw-pile.jp>'
	 * ↓
	 * return array (
	 *     'test02@ssw-pile.jp' => 'テスト２'
	 * );
	 * ※文字エンコード指定の場合は「テスト２」箇所を適切にエンコード変換
	 *
	 * @param string $email  メールアドレス
	 * @param string $encode 文字エンコード
	 * @return array メールアドレスの配列
	 */
	public static function parse_address($email, $encode = '') {
		// ユーザー名分割
		$start = strpos($email, '<');
		$end   = strpos($email, '>');
		if ( $start !== FALSE && $end !== FALSE ) {
			$user = substr($email, 0, $start);
			$user = trim($user);
			
			if ( $user ) {
				$user = self::convert_chars($user);
			}
			
			if ( $encode ) {
				$user = mb_encode_mimeheader($user, $encode, 'B', '');
			}

			$addr = substr($email, $start + 1, $end - $start - 1);
			return array($addr => $user);
		}

		return trim($email);
	}
	
	
	public static function convert_chars($subject) {
		$subject = preg_replace('/©/',      '(C)',      $subject);
		$subject = preg_replace('/®/',      '(R)',      $subject);
		$subject = preg_replace('/①/',     '(1)',      $subject);
		$subject = preg_replace('/②/',     '(2)',      $subject);
		$subject = preg_replace('/③/',     '(3)',      $subject);
		$subject = preg_replace('/④/',     '(4)',      $subject);
		$subject = preg_replace('/⑤/',     '(5)',      $subject);
		$subject = preg_replace('/⑥/',     '(6)',      $subject);
		$subject = preg_replace('/⑦/',     '(7)',      $subject);
		$subject = preg_replace('/⑧/',     '(8)',      $subject);
		$subject = preg_replace('/⑨/',     '(9)',      $subject);
		$subject = preg_replace('/⑩/',     '(10)',     $subject);
		$subject = preg_replace('/⑪/',     '(11)',     $subject);
		$subject = preg_replace('/⑫/',     '(12)',     $subject);
		$subject = preg_replace('/⑬/',     '(13)',     $subject);
		$subject = preg_replace('/⑭/',     '(14)',     $subject);
		$subject = preg_replace('/⑮/',     '(15)',     $subject);
		$subject = preg_replace('/⑯/',     '(16)',     $subject);
		$subject = preg_replace('/⑰/',     '(17)',     $subject);
		$subject = preg_replace('/⑱/',     '(18)',     $subject);
		$subject = preg_replace('/⑲/',     '(19)',     $subject);
		$subject = preg_replace('/⑳/',     '(20)',     $subject);
		$subject = preg_replace('/⑴/',     '(1)',      $subject);
		$subject = preg_replace('/⑵/',     '(2)',      $subject);
		$subject = preg_replace('/⑶/',     '(3)',      $subject);
		$subject = preg_replace('/⑷/',     '(4)',      $subject);
		$subject = preg_replace('/⑸/',     '(5)',      $subject);
		$subject = preg_replace('/⑹/',     '(6)',      $subject);
		$subject = preg_replace('/⑺/',     '(7)',      $subject);
		$subject = preg_replace('/⑻/',     '(8)',      $subject);
		$subject = preg_replace('/⑼/',     '(9)',      $subject);
		$subject = preg_replace('/⑽/',     '(10)',     $subject);
		$subject = preg_replace('/⑾/',     '(11)',     $subject);
		$subject = preg_replace('/⑿/',     '(12)',     $subject);
		$subject = preg_replace('/⒀/',     '(13)',     $subject);
		$subject = preg_replace('/⒁/',     '(14)',     $subject);
		$subject = preg_replace('/⒂/',     '(15)',     $subject);
		$subject = preg_replace('/⒃/',     '(16)',     $subject);
		$subject = preg_replace('/⒄/',     '(17)',     $subject);
		$subject = preg_replace('/⒅/',     '(18)',     $subject);
		$subject = preg_replace('/⒆/',     '(19)',     $subject);
		$subject = preg_replace('/⒇/',     '(20)',     $subject);
		$subject = preg_replace('/⒈/',     '1.',       $subject);
		$subject = preg_replace('/⒉/',     '2.',       $subject);
		$subject = preg_replace('/⒊/',     '3.',       $subject);
		$subject = preg_replace('/⒋/',     '4.',       $subject);
		$subject = preg_replace('/⒌/',     '5.',       $subject);
		$subject = preg_replace('/⒍/',     '6.',       $subject);
		$subject = preg_replace('/⒎/',     '7.',       $subject);
		$subject = preg_replace('/⒏/',     '8.',       $subject);
		$subject = preg_replace('/⒐/',     '9.',       $subject);
		$subject = preg_replace('/⒑/',     '10.',      $subject);
		$subject = preg_replace('/⒒/',     '11.',      $subject);
		$subject = preg_replace('/⒓/',     '12.',      $subject);
		$subject = preg_replace('/⒔/',     '13.',      $subject);
		$subject = preg_replace('/⒕/',     '14.',      $subject);
		$subject = preg_replace('/⒖/',     '15.',      $subject);
		$subject = preg_replace('/⒗/',     '16.',      $subject);
		$subject = preg_replace('/⒘/',     '17.',      $subject);
		$subject = preg_replace('/⒙/',     '18.',      $subject);
		$subject = preg_replace('/⒚/',     '19.',      $subject);
		$subject = preg_replace('/⒛/',     '20.',      $subject);
		$subject = preg_replace('/⒜/',     '(a)',      $subject);
		$subject = preg_replace('/⒝/',     '(b)',      $subject);
		$subject = preg_replace('/⒞/',     '(c)',      $subject);
		$subject = preg_replace('/⒟/',     '(d)',      $subject);
		$subject = preg_replace('/⒠/',     '(e)',      $subject);
		$subject = preg_replace('/⒡/',     '(f)',      $subject);
		$subject = preg_replace('/⒢/',     '(g)',      $subject);
		$subject = preg_replace('/⒣/',     '(h)',      $subject);
		$subject = preg_replace('/⒤/',     '(i)',      $subject);
		$subject = preg_replace('/⒥/',     '(j)',      $subject);
		$subject = preg_replace('/⒦/',     '(k)',      $subject);
		$subject = preg_replace('/⒧/',     '(l)',      $subject);
		$subject = preg_replace('/⒨/',     '(m)',      $subject);
		$subject = preg_replace('/⒩/',     '(n)',      $subject);
		$subject = preg_replace('/⒪/',     '(o)',      $subject);
		$subject = preg_replace('/⒫/',     '(p)',      $subject);
		$subject = preg_replace('/⒬/',     '(q)',      $subject);
		$subject = preg_replace('/⒭/',     '(r)',      $subject);
		$subject = preg_replace('/⒮/',     '(s)',      $subject);
		$subject = preg_replace('/⒯/',     '(t)',      $subject);
		$subject = preg_replace('/⒰/',     '(u)',      $subject);
		$subject = preg_replace('/⒱/',     '(v)',      $subject);
		$subject = preg_replace('/⒲/',     '(w)',      $subject);
		$subject = preg_replace('/⒳/',     '(x)',      $subject);
		$subject = preg_replace('/⒴/',     '(y)',      $subject);
		$subject = preg_replace('/⒵/',     '(z)',      $subject);
		$subject = preg_replace('/Ⓐ/',     '(A)',      $subject);
		$subject = preg_replace('/Ⓑ/',     '(B)',      $subject);
		$subject = preg_replace('/Ⓒ/',     '(C)',      $subject);
		$subject = preg_replace('/Ⓓ/',     '(D)',      $subject);
		$subject = preg_replace('/Ⓔ/',     '(E)',      $subject);
		$subject = preg_replace('/Ⓕ/',     '(F)',      $subject);
		$subject = preg_replace('/Ⓖ/',     '(G)',      $subject);
		$subject = preg_replace('/Ⓗ/',     '(H)',      $subject);
		$subject = preg_replace('/Ⓘ/',     '(I)',      $subject);
		$subject = preg_replace('/Ⓙ/',     '(J)',      $subject);
		$subject = preg_replace('/Ⓚ/',     '(K)',      $subject);
		$subject = preg_replace('/Ⓛ/',     '(L)',      $subject);
		$subject = preg_replace('/Ⓜ/',     '(M)',      $subject);
		$subject = preg_replace('/Ⓝ/',     '(N)',      $subject);
		$subject = preg_replace('/Ⓞ/',     '(O)',      $subject);
		$subject = preg_replace('/Ⓟ/',     '(P)',      $subject);
		$subject = preg_replace('/Ⓠ/',     '(Q)',      $subject);
		$subject = preg_replace('/Ⓡ/',     '(R)',      $subject);
		$subject = preg_replace('/Ⓢ/',     '(S)',      $subject);
		$subject = preg_replace('/Ⓣ/',     '(T)',      $subject);
		$subject = preg_replace('/Ⓤ/',     '(U)',      $subject);
		$subject = preg_replace('/Ⓥ/',     '(V)',      $subject);
		$subject = preg_replace('/Ⓦ/',     '(W)',      $subject);
		$subject = preg_replace('/Ⓧ/',     '(X)',      $subject);
		$subject = preg_replace('/Ⓨ/',     '(Y)',      $subject);
		$subject = preg_replace('/Ⓩ/',     '(z)',      $subject);
		$subject = preg_replace('/ⓐ/',     '(a)',      $subject);
		$subject = preg_replace('/ⓑ/',     '(b)',      $subject);
		$subject = preg_replace('/ⓒ/',     '(c)',      $subject);
		$subject = preg_replace('/ⓓ/',     '(d)',      $subject);
		$subject = preg_replace('/ⓔ/',     '(e)',      $subject);
		$subject = preg_replace('/ⓕ/',     '(f)',      $subject);
		$subject = preg_replace('/ⓖ/',     '(g)',      $subject);
		$subject = preg_replace('/ⓗ/',     '(h)',      $subject);
		$subject = preg_replace('/ⓘ/',     '(i)',      $subject);
		$subject = preg_replace('/ⓙ/',     '(j)',      $subject);
		$subject = preg_replace('/ⓚ/',     '(k)',      $subject);
		$subject = preg_replace('/ⓛ/',     '(l)',      $subject);
		$subject = preg_replace('/ⓜ/',     '(m)',      $subject);
		$subject = preg_replace('/ⓝ/',     '(n)',      $subject);
		$subject = preg_replace('/ⓞ/',     '(o)',      $subject);
		$subject = preg_replace('/ⓟ/',     '(p)',      $subject);
		$subject = preg_replace('/ⓠ/',     '(q)',      $subject);
		$subject = preg_replace('/ⓡ/',     '(r)',      $subject);
		$subject = preg_replace('/ⓢ/',     '(s)',      $subject);
		$subject = preg_replace('/ⓣ/',     '(t)',      $subject);
		$subject = preg_replace('/ⓤ/',     '(u)',      $subject);
		$subject = preg_replace('/ⓥ/',     '(v)',      $subject);
		$subject = preg_replace('/ⓦ/',     '(w)',      $subject);
		$subject = preg_replace('/ⓧ/',     '(x)',      $subject);
		$subject = preg_replace('/ⓨ/',     '(y)',      $subject);
		$subject = preg_replace('/ⓩ/',     '(z)',      $subject);
		$subject = preg_replace('/⓪/',     '(0)',      $subject);
		$subject = preg_replace('/⓫/',     '(11)',     $subject);
		$subject = preg_replace('/⓬/',     '(12)',     $subject);
		$subject = preg_replace('/⓭/',     '(13)',     $subject);
		$subject = preg_replace('/⓮/',     '(14)',     $subject);
		$subject = preg_replace('/⓯/',     '(15)',     $subject);
		$subject = preg_replace('/⓰/',     '(16)',     $subject);
		$subject = preg_replace('/⓱/',     '(17)',     $subject);
		$subject = preg_replace('/⓲/',     '(18)',     $subject);
		$subject = preg_replace('/⓳/',     '(19)',     $subject);
		$subject = preg_replace('/⓴/',     '(20)',     $subject);
		$subject = preg_replace('/⓵/',     '(1)',      $subject);
		$subject = preg_replace('/⓶/',     '(2)',      $subject);
		$subject = preg_replace('/⓷/',     '(3)',      $subject);
		$subject = preg_replace('/⓸/',     '(4)',      $subject);
		$subject = preg_replace('/⓹/',     '(5)',      $subject);
		$subject = preg_replace('/⓺/',     '(6)',      $subject);
		$subject = preg_replace('/⓻/',     '(7)',      $subject);
		$subject = preg_replace('/⓼/',     '(8)',      $subject);
		$subject = preg_replace('/⓽/',     '(9)',      $subject);
		$subject = preg_replace('/⓾/',     '(10)',     $subject);
		$subject = preg_replace('/❶/',     '(1)',      $subject);
		$subject = preg_replace('/❷/',     '(2)',      $subject);
		$subject = preg_replace('/❸/',     '(3)',      $subject);
		$subject = preg_replace('/❹/',     '(4)',      $subject);
		$subject = preg_replace('/❺/',     '(5)',      $subject);
		$subject = preg_replace('/❻/',     '(6)',      $subject);
		$subject = preg_replace('/❼/',     '(7)',      $subject);
		$subject = preg_replace('/❽/',     '(8)',      $subject);
		$subject = preg_replace('/❾/',     '(9)',      $subject);
		$subject = preg_replace('/❿/',     '(10)',     $subject);
		$subject = preg_replace('/➀/',     '(1)',      $subject);
		$subject = preg_replace('/➁/',     '(2)',      $subject);
		$subject = preg_replace('/➂/',     '(3)',      $subject);
		$subject = preg_replace('/➃/',     '(4)',      $subject);
		$subject = preg_replace('/➄/',     '(5)',      $subject);
		$subject = preg_replace('/➅/',     '(6)',      $subject);
		$subject = preg_replace('/➆/',     '(7)',      $subject);
		$subject = preg_replace('/➇/',     '(8)',      $subject);
		$subject = preg_replace('/➈/',     '(9)',      $subject);
		$subject = preg_replace('/➉/',     '(10)',     $subject);
		$subject = preg_replace('/➊/',     '(1)',      $subject);
		$subject = preg_replace('/➋/',     '(2)',      $subject);
		$subject = preg_replace('/➌/',     '(3)',      $subject);
		$subject = preg_replace('/➍/',     '(4)',      $subject);
		$subject = preg_replace('/➎/',     '(5)',      $subject);
		$subject = preg_replace('/➏/',     '(6)',      $subject);
		$subject = preg_replace('/➐/',     '(7)',      $subject);
		$subject = preg_replace('/➑/',     '(8)',      $subject);
		$subject = preg_replace('/➒/',     '(9)',      $subject);
		$subject = preg_replace('/➓/',     '(10)',     $subject);
		$subject = preg_replace('/㈠/',     '(一)',     $subject);
		$subject = preg_replace('/㈡/',     '(二)',     $subject);
		$subject = preg_replace('/㈢/',     '(三)',     $subject);
		$subject = preg_replace('/㈣/',     '(四)',     $subject);
		$subject = preg_replace('/㈤/',     '(五)',     $subject);
		$subject = preg_replace('/㈥/',     '(六)',     $subject);
		$subject = preg_replace('/㈦/',     '(七)',     $subject);
		$subject = preg_replace('/㈧/',     '(八)',     $subject);
		$subject = preg_replace('/㈨/',     '(九)',     $subject);
		$subject = preg_replace('/㈩/',     '(十)',     $subject);
		$subject = preg_replace('/㈪/',     '(月)',     $subject);
		$subject = preg_replace('/㈫/',     '(火)',     $subject);
		$subject = preg_replace('/㈬/',     '(水)',     $subject);
		$subject = preg_replace('/㈭/',     '(木)',     $subject);
		$subject = preg_replace('/㈮/',     '(金)',     $subject);
		$subject = preg_replace('/㈯/',     '(土)',     $subject);
		$subject = preg_replace('/㈰/',     '(日)',     $subject);
		$subject = preg_replace('/㈱/',     '(株)',     $subject);
		$subject = preg_replace('/㈲/',     '(有)',     $subject);
		$subject = preg_replace('/㈳/',     '(社)',     $subject);
		$subject = preg_replace('/㈴/',     '(名)',     $subject);
		$subject = preg_replace('/㈵/',     '(特)',     $subject);
		$subject = preg_replace('/㈶/',     '(財)',     $subject);
		$subject = preg_replace('/㈷/',     '(祝)',     $subject);
		$subject = preg_replace('/㈸/',     '(労)',     $subject);
		$subject = preg_replace('/㈹/',     '(代)',     $subject);
		$subject = preg_replace('/㈺/',     '(呼)',     $subject);
		$subject = preg_replace('/㈻/',     '(学)',     $subject);
		$subject = preg_replace('/㈼/',     '(監)',     $subject);
		$subject = preg_replace('/㈽/',     '(企)',     $subject);
		$subject = preg_replace('/㈾/',     '(資)',     $subject);
		$subject = preg_replace('/㈿/',     '(協)',     $subject);
		$subject = preg_replace('/㉀/',     '(祭)',     $subject);
		$subject = preg_replace('/㉁/',     '(休)',     $subject);
		$subject = preg_replace('/㉂/',     '(自)',     $subject);
		$subject = preg_replace('/㉃/',     '(至)',     $subject);
		$subject = preg_replace('/㉑/',     '(21)',     $subject);
		$subject = preg_replace('/㉒/',     '(22)',     $subject);
		$subject = preg_replace('/㉓/',     '(23)',     $subject);
		$subject = preg_replace('/㉔/',     '(24)',     $subject);
		$subject = preg_replace('/㉕/',     '(25)',     $subject);
		$subject = preg_replace('/㉖/',     '(26)',     $subject);
		$subject = preg_replace('/㉗/',     '(27)',     $subject);
		$subject = preg_replace('/㉘/',     '(28)',     $subject);
		$subject = preg_replace('/㉙/',     '(29)',     $subject);
		$subject = preg_replace('/㉚/',     '(30)',     $subject);
		$subject = preg_replace('/㉛/',     '(31)',     $subject);
		$subject = preg_replace('/㉜/',     '(32)',     $subject);
		$subject = preg_replace('/㉝/',     '(33)',     $subject);
		$subject = preg_replace('/㉞/',     '(34)',     $subject);
		$subject = preg_replace('/㉟/',     '(35)',     $subject);
		$subject = preg_replace('/㊀/',     '(一)',     $subject);
		$subject = preg_replace('/㊁/',     '(二)',     $subject);
		$subject = preg_replace('/㊂/',     '(三)',     $subject);
		$subject = preg_replace('/㊃/',     '(四)',     $subject);
		$subject = preg_replace('/㊄/',     '(五)',     $subject);
		$subject = preg_replace('/㊅/',     '(六)',     $subject);
		$subject = preg_replace('/㊆/',     '(七)',     $subject);
		$subject = preg_replace('/㊇/',     '(八)',     $subject);
		$subject = preg_replace('/㊈/',     '(九)',     $subject);
		$subject = preg_replace('/㊉/',     '(十)',     $subject);
		$subject = preg_replace('/㊊/',     '(月)',     $subject);
		$subject = preg_replace('/㊋/',     '(火)',     $subject);
		$subject = preg_replace('/㊌/',     '(水)',     $subject);
		$subject = preg_replace('/㊍/',     '(木)',     $subject);
		$subject = preg_replace('/㊎/',     '(金)',     $subject);
		$subject = preg_replace('/㊏/',     '(土)',     $subject);
		$subject = preg_replace('/㊐/',     '(日)',     $subject);
		$subject = preg_replace('/㊑/',     '(株)',     $subject);
		$subject = preg_replace('/㊒/',     '(有)',     $subject);
		$subject = preg_replace('/㊓/',     '(社)',     $subject);
		$subject = preg_replace('/㊔/',     '(名)',     $subject);
		$subject = preg_replace('/㊕/',     '(特)',     $subject);
		$subject = preg_replace('/㊖/',     '(財)',     $subject);
		$subject = preg_replace('/㊗/',     '(祝)',     $subject);
		$subject = preg_replace('/㊘/',     '(労)',     $subject);
		$subject = preg_replace('/㊙/',     '(秘)',     $subject);
		$subject = preg_replace('/㊚/',     '(男)',     $subject);
		$subject = preg_replace('/㊛/',     '(女)',     $subject);
		$subject = preg_replace('/㊜/',     '(適)',     $subject);
		$subject = preg_replace('/㊝/',     '(優)',     $subject);
		$subject = preg_replace('/㊞/',     '(印)',     $subject);
		$subject = preg_replace('/㊟/',     '(注)',     $subject);
		$subject = preg_replace('/㊠/',     '(項)',     $subject);
		$subject = preg_replace('/㊡/',     '(休)',     $subject);
		$subject = preg_replace('/㊢/',     '(写)',     $subject);
		$subject = preg_replace('/㊣/',     '(正)',     $subject);
		$subject = preg_replace('/㊤/',     '(上)',     $subject);
		$subject = preg_replace('/㊥/',     '(中)',     $subject);
		$subject = preg_replace('/㊦/',     '(下)',     $subject);
		$subject = preg_replace('/㊧/',     '(左)',     $subject);
		$subject = preg_replace('/㊨/',     '(右)',     $subject);
		$subject = preg_replace('/㊩/',     '(医)',     $subject);
		$subject = preg_replace('/㊪/',     '(宗)',     $subject);
		$subject = preg_replace('/㊫/',     '(学)',     $subject);
		$subject = preg_replace('/㊬/',     '(監)',     $subject);
		$subject = preg_replace('/㊭/',     '(企)',     $subject);
		$subject = preg_replace('/㊮/',     '(資)',     $subject);
		$subject = preg_replace('/㊯/',     '(協)',     $subject);
		$subject = preg_replace('/㊰/',     '(夜)',     $subject);
		$subject = preg_replace('/㊱/',     '(36)',     $subject);
		$subject = preg_replace('/㊲/',     '(37)',     $subject);
		$subject = preg_replace('/㊳/',     '(38)',     $subject);
		$subject = preg_replace('/㊴/',     '(39)',     $subject);
		$subject = preg_replace('/㊵/',     '(40)',     $subject);
		$subject = preg_replace('/㊶/',     '(41)',     $subject);
		$subject = preg_replace('/㊷/',     '(42)',     $subject);
		$subject = preg_replace('/㊸/',     '(43)',     $subject);
		$subject = preg_replace('/㊹/',     '(44)',     $subject);
		$subject = preg_replace('/㊺/',     '(45)',     $subject);
		$subject = preg_replace('/㊻/',     '(46)',     $subject);
		$subject = preg_replace('/㊼/',     '(47)',     $subject);
		$subject = preg_replace('/㊽/',     '(48)',     $subject);
		$subject = preg_replace('/㊾/',     '(49)',     $subject);
		$subject = preg_replace('/㊿/',     '(50)',     $subject);
		$subject = preg_replace('/㋀/',     '1月',      $subject);
		$subject = preg_replace('/㋁/',     '2月',      $subject);
		$subject = preg_replace('/㋂/',     '3月',      $subject);
		$subject = preg_replace('/㋃/',     '4月',      $subject);
		$subject = preg_replace('/㋄/',     '5月',      $subject);
		$subject = preg_replace('/㋅/',     '6月',      $subject);
		$subject = preg_replace('/㋆/',     '7月',      $subject);
		$subject = preg_replace('/㋇/',     '8月',      $subject);
		$subject = preg_replace('/㋈/',     '9月',      $subject);
		$subject = preg_replace('/㋉/',     '10月',     $subject);
		$subject = preg_replace('/㋊/',     '11月',     $subject);
		$subject = preg_replace('/㋋/',     '12月',     $subject);
		$subject = preg_replace('/㋐/',     '(ア)',     $subject);
		$subject = preg_replace('/㋑/',     '(イ)',     $subject);
		$subject = preg_replace('/㋒/',     '(ウ)',     $subject);
		$subject = preg_replace('/㋓/',     '(エ)',     $subject);
		$subject = preg_replace('/㋔/',     '(オ)',     $subject);
		$subject = preg_replace('/㋕/',     '(カ)',     $subject);
		$subject = preg_replace('/㋖/',     '(キ)',     $subject);
		$subject = preg_replace('/㋗/',     '(ク)',     $subject);
		$subject = preg_replace('/㋘/',     '(ケ)',     $subject);
		$subject = preg_replace('/㋙/',     '(コ)',     $subject);
		$subject = preg_replace('/㋚/',     '(サ)',     $subject);
		$subject = preg_replace('/㋛/',     '(シ)',     $subject);
		$subject = preg_replace('/㋜/',     '(ス)',     $subject);
		$subject = preg_replace('/㋝/',     '(セ)',     $subject);
		$subject = preg_replace('/㋞/',     '(ソ)',     $subject);
		$subject = preg_replace('/㋟/',     '(タ)',     $subject);
		$subject = preg_replace('/㋠/',     '(チ)',     $subject);
		$subject = preg_replace('/㋡/',     '(ツ)',     $subject);
		$subject = preg_replace('/㋢/',     '(テ)',     $subject);
		$subject = preg_replace('/㋣/',     '(ト)',     $subject);
		$subject = preg_replace('/㋤/',     '(ナ)',     $subject);
		$subject = preg_replace('/㋥/',     '(ニ)',     $subject);
		$subject = preg_replace('/㋦/',     '(ヌ)',     $subject);
		$subject = preg_replace('/㋧/',     '(ネ)',     $subject);
		$subject = preg_replace('/㋨/',     '(ノ)',     $subject);
		$subject = preg_replace('/㋩/',     '(ハ)',     $subject);
		$subject = preg_replace('/㋪/',     '(ヒ)',     $subject);
		$subject = preg_replace('/㋫/',     '(フ)',     $subject);
		$subject = preg_replace('/㋬/',     '(ヘ)',     $subject);
		$subject = preg_replace('/㋭/',     '(ホ)',     $subject);
		$subject = preg_replace('/㋮/',     '(マ)',     $subject);
		$subject = preg_replace('/㋯/',     '(ミ)',     $subject);
		$subject = preg_replace('/㋰/',     '(ム)',     $subject);
		$subject = preg_replace('/㋱/',     '(メ)',     $subject);
		$subject = preg_replace('/㋲/',     '(モ)',     $subject);
		$subject = preg_replace('/㋳/',     '(ヤ)',     $subject);
		$subject = preg_replace('/㋴/',     '(ユ)',     $subject);
		$subject = preg_replace('/㋵/',     '(ヨ)',     $subject);
		$subject = preg_replace('/㋶/',     '(ラ)',     $subject);
		$subject = preg_replace('/㋷/',     '(リ)',     $subject);
		$subject = preg_replace('/㋸/',     '(ル)',     $subject);
		$subject = preg_replace('/㋹/',     '(レ)',     $subject);
		$subject = preg_replace('/㋺/',     '(ロ)',     $subject);
		$subject = preg_replace('/㋻/',     '(ワ)',     $subject);
		$subject = preg_replace('/㋼/',     '(ヰ)',     $subject);
		$subject = preg_replace('/㋽/',     '(ヱ)',     $subject);
		$subject = preg_replace('/㋾/',     '(ヲ)',     $subject);
		$subject = preg_replace('/㍻/',     '平成',     $subject);
		$subject = preg_replace('/㍼/',     '昭和',     $subject);
		$subject = preg_replace('/㍽/',     '大正',     $subject);
		$subject = preg_replace('/㍾/',     '明治',     $subject);
		$subject = preg_replace('/㍿/',     '株式会社', $subject);
		$subject = preg_replace('/㎅/',     'KB',       $subject);
		$subject = preg_replace('/㎆/',     'MB',       $subject);
		$subject = preg_replace('/㎇/',     'GB',       $subject);
		$subject = preg_replace('/㎜/',     'mm',       $subject);
		$subject = preg_replace('/㎝/',     'cm',       $subject);
		$subject = preg_replace('/㎞/',     'km',       $subject);
		$subject = preg_replace('/㎟/',     'mm^2',     $subject);
		$subject = preg_replace('/㎠/',     'cm^2',     $subject);
		$subject = preg_replace('/㎡/',     'm^2',      $subject);
		$subject = preg_replace('/㎢/',     'km^2',     $subject);
		$subject = preg_replace('/㎣/',     'mm^3',     $subject);
		$subject = preg_replace('/㎤/',     'cm^3',     $subject);
		$subject = preg_replace('/㎥/',     'm^3',      $subject);
		$subject = preg_replace('/㎦/',     'km^3',     $subject);
		$subject = preg_replace('/㎧/',     'm/s',      $subject);
		$subject = preg_replace('/㎨/',     'm/s^2',    $subject);
		$subject = preg_replace('/ｦ/',      'ヲ',       $subject);
		$subject = preg_replace('/ｧ/',      'ァ',       $subject);
		$subject = preg_replace('/ｨ/',      'ィ',       $subject);
		$subject = preg_replace('/ｩ/',      'ゥ',       $subject);
		$subject = preg_replace('/ｪ/',      'ェ',       $subject);
		$subject = preg_replace('/ｫ/',      'ォ',       $subject);
		$subject = preg_replace('/ｬ/',      'ャ',       $subject);
		$subject = preg_replace('/ｭ/',      'ュ',       $subject);
		$subject = preg_replace('/ｮ/',      'ョ',       $subject);
		$subject = preg_replace('/ｯ/',      'ッ',       $subject);
		$subject = preg_replace('/ｰ/',      'ー',       $subject);
		$subject = preg_replace('/ｱ/',      'ア',       $subject);
		$subject = preg_replace('/ｲ/',      'イ',       $subject);
		$subject = preg_replace('/ｳ/',      'ウ',       $subject);
		$subject = preg_replace('/ｴ/',      'エ',       $subject);
		$subject = preg_replace('/ｵ/',      'オ',       $subject);
		$subject = preg_replace('/ｶﾞ/',     'ガ',       $subject);
		$subject = preg_replace('/ｷﾞ/',     'ギ',       $subject);
		$subject = preg_replace('/ｸﾞ/',     'グ',       $subject);
		$subject = preg_replace('/ｹﾞ/',     'ゲ',       $subject);
		$subject = preg_replace('/ｺﾞ/',     'ゴ',       $subject);
		$subject = preg_replace('/ｶ/',      'カ',       $subject);
		$subject = preg_replace('/ｷ/',      'キ',       $subject);
		$subject = preg_replace('/ｸ/',      'ク',       $subject);
		$subject = preg_replace('/ｹ/',      'ケ',       $subject);
		$subject = preg_replace('/ｺ/',      'コ',       $subject);
		$subject = preg_replace('/ｻﾞ/',     'ザ',       $subject);
		$subject = preg_replace('/ｼﾞ/',     'ジ',       $subject);
		$subject = preg_replace('/ｽﾞ/',     'ズ',       $subject);
		$subject = preg_replace('/ｾﾞ/',     'ゼ',       $subject);
		$subject = preg_replace('/ｿﾞ/',     'ゾ',       $subject);
		$subject = preg_replace('/ｻ/',      'サ',       $subject);
		$subject = preg_replace('/ｼ/',      'シ',       $subject);
		$subject = preg_replace('/ｽ/',      'ス',       $subject);
		$subject = preg_replace('/ｾ/',      'セ',       $subject);
		$subject = preg_replace('/ｿ/',      'ソ',       $subject);
		$subject = preg_replace('/ﾀﾞ/',     'ダ',       $subject);
		$subject = preg_replace('/ﾁﾞ/',     'ヂ',       $subject);
		$subject = preg_replace('/ﾂﾞ/',     'ヅ',       $subject);
		$subject = preg_replace('/ﾃﾞ/',     'デ',       $subject);
		$subject = preg_replace('/ﾄﾞ/',     'ド',       $subject);
		$subject = preg_replace('/ﾀ/',      'タ',       $subject);
		$subject = preg_replace('/ﾁ/',      'チ',       $subject);
		$subject = preg_replace('/ﾂ/',      'ツ',       $subject);
		$subject = preg_replace('/ﾃ/',      'テ',       $subject);
		$subject = preg_replace('/ﾄ/',      'ト',       $subject);
		$subject = preg_replace('/ﾅ/',      'ナ',       $subject);
		$subject = preg_replace('/ﾆ/',      'ニ',       $subject);
		$subject = preg_replace('/ﾇ/',      'ヌ',       $subject);
		$subject = preg_replace('/ﾈ/',      'ネ',       $subject);
		$subject = preg_replace('/ﾉ/',      'ノ',       $subject);
		$subject = preg_replace('/ﾊﾞ/',     'バ',       $subject);
		$subject = preg_replace('/ﾋﾞ/',     'ビ',       $subject);
		$subject = preg_replace('/ﾌﾞ/',     'ブ',       $subject);
		$subject = preg_replace('/ﾍﾞ/',     'ベ',       $subject);
		$subject = preg_replace('/ﾎﾞ/',     'ボ',       $subject);
		$subject = preg_replace('/ﾊﾟ/',     'パ',       $subject);
		$subject = preg_replace('/ﾋﾟ/',     'ピ',       $subject);
		$subject = preg_replace('/ﾌﾟ/',     'プ',       $subject);
		$subject = preg_replace('/ﾍﾟ/',     'ペ',       $subject);
		$subject = preg_replace('/ﾎﾟ/',     'ポ',       $subject);
		$subject = preg_replace('/ﾊ/',      'ハ',       $subject);
		$subject = preg_replace('/ﾋ/',      'ヒ',       $subject);
		$subject = preg_replace('/ﾌ/',      'フ',       $subject);
		$subject = preg_replace('/ﾍ/',      'ヘ',       $subject);
		$subject = preg_replace('/ﾎ/',      'ホ',       $subject);
		$subject = preg_replace('/ﾏ/',      'マ',       $subject);
		$subject = preg_replace('/ﾐ/',      'ミ',       $subject);
		$subject = preg_replace('/ﾑ/',      'ム',       $subject);
		$subject = preg_replace('/ﾒ/',      'メ',       $subject);
		$subject = preg_replace('/ﾓ/',      'モ',       $subject);
		$subject = preg_replace('/ﾔ/',      'ヤ',       $subject);
		$subject = preg_replace('/ﾕ/',      'ユ',       $subject);
		$subject = preg_replace('/ﾖ/',      'ヨ',       $subject);
		$subject = preg_replace('/ﾗ/',      'ラ',       $subject);
		$subject = preg_replace('/ﾘ/',      'リ',       $subject);
		$subject = preg_replace('/ﾙ/',      'ル',       $subject);
		$subject = preg_replace('/ﾚ/',      'レ',       $subject);
		$subject = preg_replace('/ﾛ/',      'ロ',       $subject);
		$subject = preg_replace('/ﾜ/',      'ワ',       $subject);
		$subject = preg_replace('/ﾝ/',      'ン',       $subject);
		
		return $subject;
	}
}
