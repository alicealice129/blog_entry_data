<?php

function get_data($key) {
    // initialization and then get the string after isset()
    $str = '';
    if(isset($_GET[$key]) === TRUE) {
      $str = $_GET[$key];
    }
    return $str;
}

include('vendor/smarty/smarty/libs/Smarty.class.php');
include('models/blog_entry.php');

// create object
$smarty = new Smarty;

$error = empty($_GET['error']) ? '' : $_GET['error'];

// get cookies
if (isset($_COOKIE['search_date'])) {
	$date = $_COOKIE['search_date'];
} else {
	$date = '';
}
if (isset($_COOKIE['search_url'])) {
	$url = $_COOKIE['search_url'];
} else {
	$url = '';
}
if (isset($_COOKIE['search_username'])) {
	$username = $_COOKIE['search_username'];
} else {
	$username = '';
}
if (isset($_COOKIE['search_server_name'])) {
	$server_name = $_COOKIE['search_server_name'];
} else {
	$server_name = '';
}
if (isset($_COOKIE['search_entry_number'])) {
	$entry_number = $_COOKIE['search_entry_number'];
} else {
	$entry_number = '';
}

// page handling
try {
    // get the page number
    $page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
    $smarty->assign('page', $page);
    // count the blog entries
    $nb_element = count_blog_entries($date, $url, $username, $server_name, $entry_number);
    $smarty->assign('nb_element', $nb_element);

    // calculate the offset for database query 
    $element_per_page = 10;
    $nb_page = intdiv(($nb_element-1),$element_per_page)+1;
    $smarty->assign('nb_page', $nb_page);

    $offset = $page*$element_per_page-($element_per_page);
    // get the blog entries from database
    $entries = get_blog_entries($date, $url, $username, $server_name, $entry_number, $element_per_page, $offset);
    
    // format data for frontend
    $headers = array(
        "date" => "日付(UTC)",
        "url" => "URL",
        "title" => "タイトル",
        "description" => "description",
    );

    $smarty->assign('entries', $entries);
    $smarty->assign('headers', $headers);

} catch (Exception $e) {
    echo '<pre>';
    echo 'Line: ' . $e->getLine() . '<br>';
    echo 'File: ' . $e->getFile() . '<br>';
    echo 'Error message: ' . $e->getMessage() . '<br>';
    echo '</pre>';
}

$smarty->assign('error', $error);
$smarty->assign('date', $date);
$smarty->assign('url', $url);
$smarty->assign('username', $username);
$smarty->assign('server_name', $server_name);
$smarty->assign('entry_number', $entry_number);

// display it
$smarty->display('../templates/page.tpl');
?>