<?php

include("db_config.php");

// function to get the blog entries where statement
function get_blog_entries_where($date, $url, $username, $server_name, $entry_number) {
    $where_query = '';
    if ($date !== '') {
        $where_query = $where_query . " date between '{$date} 00:00:00' AND '{$date} 23:59:59' AND";
    }
    if ($url !== '') {
        $where_query = $where_query . " url like '%{$url}%' AND";
    }
    if ($username !== '') {
        $where_query = $where_query . " username like '%{$username}%' AND";
    }
    if ($server_name !== '') {
        $where_query = $where_query . " server_number like '{$server_name}' AND";
    }
    if ($entry_number !== '') {
        $where_query = $where_query . " entry_number >= '{$entry_number}' AND";
    }

    if ($where_query !== '') {
        $where_query = substr($where_query, 0, -4);
    }
    return $where_query;
}

// get the number of blog entries 
function count_blog_entries($date, $url, $username, $server_name, $entry_number) {
    // connect to database
    $conn = get_db_conn();
    // format the query
    $where_query = get_blog_entries_where($date, $url, $username, $server_name, $entry_number);

    $req = "SELECT COUNT(*) FROM BlogEntry";
    if ($where_query !== '') {
        $req = $req . " WHERE " . $where_query;
    } 
    $req = $req . ";";
    
    // get the number of the element to the query
    $nb_element = $conn->query($req);
    $nb_element = $nb_element->fetch_row();
    $nb_element = $nb_element[0];

    return $nb_element;
}

// get the blog entries from database
function get_blog_entries($date, $url, $username, $server_name, $entry_number, $element_per_page, $offset) {
    // connect to database
    $conn = get_db_conn();

    // parse the where query
    $where_query = get_blog_entries_where($date, $url, $username, $server_name, $entry_number);
    $req = "SELECT * FROM BlogEntry";
    if ($where_query !== '') {
        $req = $req . " WHERE " . $where_query;
    }
    $req = $req . " ORDER BY date DESC LIMIT $element_per_page OFFSET $offset";

    // send the database query
    $entries = $conn->query($req);
    $entries = $entries->fetch_all(MYSQLI_ASSOC);
    return $entries;

}

?>