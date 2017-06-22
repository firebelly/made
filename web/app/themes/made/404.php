<?php
// Redirect to page-not-found if it exists, otherwise home
header("HTTP/1.1 301 Moved Permanently");
$page_not_found_page = get_page_by_path('page-not-found',OBJECT);
if( isset($page_not_found_page) ) {
  header("Location: ".get_bloginfo('url').'/page-not-found/');
} else {
  header("Location: ".get_bloginfo('url'));
}
exit();
?>