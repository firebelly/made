<?php
// Ran outta time, redirect to home
header("HTTP/1.1 301 Moved Permanently");
header("Location: ".get_bloginfo('url').'/page-not-found/');
exit();
?>