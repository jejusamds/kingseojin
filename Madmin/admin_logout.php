<?php
include $_SERVER['DOCUMENT_ROOT']."/inc/global.inc";

	$_SESSION = array();

	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}

	session_destroy();

echo "<script>parent.document.location='./';</script>";

?>