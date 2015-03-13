<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="contact.css" rel="stylesheet" media="all" />

<div id="contact_body"><!-- InstanceBeginEditable name="EditRegion6" -->
<br />
<script type="text/javascript">
function formSubmit() {
	var win = window.parent;
	win.location.hash='#mailform';
}
</script>

<p>■下記フォームにご入力のうえ、確認ボタンを押してください。<br />
（<font color="red">※</font>は必須項目です）</p>

<form action="index.php?" method="post">
<input type="hidden" name="act" value="confirm"/>
<table class="contact">
	<tr>
		<th>お名前<font color="red">※</font></th>
		<td>
			<input type="text" size="16" name="name" value="<?php eh('name')?>">
			<?php err('name')?>
		</td>
	</tr>
	<tr>
		<th>フリガナ<font color="red">※</font></th>
		<td>
			<input type="text" size="16" name="kana"value="<?php eh('kana')?>">
			<?php err('kana')?>
		</td>
	</tr>
	<tr>
		<th>会社名</th>
		<td>
			<input type="text" size="16" name="c_name" value="<?php eh('c_name')?>">
			<?php err('c_name')?>
		</td>
	</tr>
	<tr>
		<th>住所</th>
		<td>
			<input type="text" size="3" name="yubin1" value="<?php eh('yubin1')?>"> － <input type="text" size="4" name="yubin2" value="<?php eh('yubin2')?>">
			<select class="sel_font"name="address1">
			<?php foreach($prefArray as $pref):?>
			<option <?php selected('address1', $pref)?>><?php echo $pref?></option>
			<?php endforeach;?>
			</select> <div style="height:10px;">&nbsp;</div>
			<input type="text" size="45" name="address2"
			value="<?php eh('address2')?>"> <?php err('yubin')?>
			<?php err('address1')?> <?php err('address2')?>
		</td>
	</tr>
	<tr>
		<th>e-mail<font color="red">※</font></th>
		<td>
			<input type="text" size="35" name="email1"value="<?php eh('email1')?>">
			<?php err('email1')?>
		</td>
	</tr>
	<tr>
		<th>e-mail（確認）<font color="red">※</font></th>
		<td>
			<input type="text" size="35" name="email2"value="<?php eh('email2')?>">
			<?php err('email2')?>
		</td>
	</tr>
	<tr>
		<th>TEL</th>
		<td>
			<input type="text" size="35" name="tel"value="<?php eh('tel')?>">
			<?php err('tel')?>
		</td>
	</tr>
	<tr>
		<th>お問い合わせ内容<font color="red">※</font></th>
		<td>
			<textarea name="body" style="width: 450px; height: 150px;"><?php eh('body')?></textarea>
			<?php err('body')?>
		</td>
	</tr>
</table>
<div class="btn">
	<input type="reset" value="取 消" class="rset" />
	<input type="submit"value="確 認" class="confirm" id="sendform" onclick="formSubmit();"/>
</div>
	<!-- InstanceEndEditable --><!-- id "contact_body"--></div>
