<?php

// delete the blank before and after the string 前後の空白を削除
function trim_space($str) {
    return preg_replace('/\A[　\s]*|[　\s]*\z/u', '', $str);
}


// initialization, get $POST data
function get_post_data($key) {
    // initialization and then get the string after isset()
    $str = '';
    if(isset($_POST[$key]) === TRUE) {
      $str = $_POST[$key];
      $str = trim_space($str);
    }
    return $str;
}

// check date format 
function check_date($date, $format = 'Y-m-d') {
    if ($date ==='') {
        return true;
    }

    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// check number format
function check_number($str) {
    $integer_regex = '/[0-9]+/';
    if (preg_match($integer_regex, $str) !== 1) {
        return false;
    }
    return true;
}

// get the parameters from the form
$date = get_post_data('date');

if (!check_date($date)) {
    header("Location: page.php?error=Please enter valid date");
    return;
};

$url = get_post_data('url');
$username = get_post_data('username');
$server_name = get_post_data('server_name');

if (!check_number($server_name)  && ($server_name !=='')) {
    header("Location: page.php?error=Please enter valid server number");
    return;
};

$entry_number = get_post_data('entry_number');

if (!check_number($entry_number) && ($entry_number !=='')) {
    header("Location: page.php?error=Please enter valid entry number");
    return;
};

// save the parameters to the cookies
setcookie("search_date", $date);
setcookie("search_url", $url);
setcookie("search_username", $username);
setcookie("search_server_name", $server_name);
setcookie("search_entry_number", $entry_number);

header("Location: page.php");
?>