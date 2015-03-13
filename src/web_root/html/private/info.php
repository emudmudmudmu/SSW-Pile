<?php
$allows = array(
	'114.173.74.247',
	'210.199.18.214'
);

if ( !in_array($_SERVER['REMOTE_ADDR'], $allows) ) {
	header("HTTP/1.0 404 Not Found");
	return;
}

phpinfo();