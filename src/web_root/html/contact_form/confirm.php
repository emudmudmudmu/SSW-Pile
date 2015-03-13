<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="contact.css" rel="stylesheet" media="all" />

<div id="contact_body"><!-- InstanceBeginEditable name="EditRegion6" -->
<br />

<p>■下記内容を確認し、良ければ送信ボタンを押してください。</p>

<form action="index.php" method="post">
<input type="hidden"name="act" value="done" />
<input type="hidden" name="ssid" value="<?php echo _h(session_id())?>" />

<table class="confirm">
	<tr>
		<th>お名前</th>
		<td><?php eh('name')?></td>
	</tr>
	<tr>
		<th>フリガナ</th>
		<td><?php eh('kana')?></td>
	</tr>
	<tr>
		<th>会社名</th>
		<td><?php eh('c_name')?></td>
	</tr>
	<tr>
		<th>住所</th>
		<td>
			<p>〒 <?php eh('yubin1')?>-<?php eh('yubin2')?><br />
			<?php eh('address1')?><br />
			<?php eh('address2')?><br />
			</p>
		</td>
	</tr>
	<tr>
		<th>e-mail</th>
		<td><?php eh('email1')?></td>
	</tr>
	<tr>
		<th>TEL</th>
		<td><?php eh('tel')?></td>
	</tr>
	<tr>
		<th>お問い合わせ内容</th>
		<td>
			<p style="margin-top: 20px; margin-bottom: 20px;">
			<?php echo nl2br(_h($vars['body'])); ?></p>
		</td>
	</tr>
</table>
<div class="btn">
  <input type="submit" name="back_to_input" value="戻 る" onclick="formSubmit()" class="back" />
  <input type="submit" value="送 信" id="sendform" onclick="formSubmit()" class="sbmt" /></div>
</form>
</div>

