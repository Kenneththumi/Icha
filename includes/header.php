<?php
//ini_set("session.cookie_domain",substr($_SERVER[HTTP_HOST],3));
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

function redirect($url)
{
if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ". $url );
	}
}
?>