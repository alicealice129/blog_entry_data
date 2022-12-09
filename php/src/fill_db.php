<?php
    $servername = "";
    $username = "";
    $password = "";
    $dbname = "";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // make sures that database has correct character encoding
    $sql = "ALTER DATABASE $dbname
    CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;";

    // launch the sql
    if ($conn->query($sql) === TRUE) {
        echo "charset set sucessfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    // sql to create table model
    // make username, server_number, entry_number unique to avoid to duplicate data
    $sql = "CREATE TABLE BlogEntry (
        id INT(6) UNSIGNED AUTO_INCREMENT KEY,
        title text NOT NULL,
        description text NOT NULL,
        date DATETIME,
        username VARCHAR(250) NOT NULL,
        server_number INT(6),
        entry_number INT(6) NOT NULL,
        url text NOT NULL, 
        unique(username,server_number,entry_number)
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Table MyGuests created successfully";
    } else {
      echo "Error creating table: " . $conn->error;
    }

    // get and parse the blog newentry list rdf file using DOM document
    $dom = new DomDocument;
    $rdf = file_get_contents('https://blog.fc2.com/newentry.rdf');
    $dom->loadXml($rdf);
    foreach($dom->getElementsByTagName('item') as $item) {
        $title = '';
        $url = '';
        $username = '';
        $entry_number = '';
        $description = '';
        $date = '';

        // for all item tag in the rdf getting the useful data 
        foreach($item->childNodes as $item_node) {
            $node_name = $item_node->nodeName;
            $node_value = $item_node->nodeValue;

            if ($node_name === 'title') {
                $title = mysqli_real_escape_string($conn, $node_value);
            } elseif ($node_name === 'link') {
                $url = mysqli_real_escape_string($conn, $node_value);
                
                // parse the link with regular expressions to get username
                preg_match('/http:\/\/.+\.blog/', $url, $match1);
                if (count($match1) === 0) {
                    $username = preg_split ( '/\./' , $url);
                    $username = $username[0];
                    $username = str_replace('http://','', $username);
                    if ($username === 'www') {
                        $username = $username[1];
                    }
                } else {
                    $username = $match1[0];
                    $username = str_replace('http://','', $username);
                    $username = str_replace('.blog','', $username);
                }

                // parse the link with regular expressions to get the server number
                preg_match('/\.blog[0-9]+\./', $url, $match2);
                if (count($match2) === 0) {
                    $server_number = '0';
                } else {
                    $server_number = $match2[0];
                    $server_number = str_replace('.blog','', $server_number);
                    $server_number = str_replace('.','', $server_number);
                }

                // parse the link with regular expressions to get the entry number
                preg_match('/blog-entry-[0-9]+/', $url, $match3);
                $entry_number = $match3[0];
                $entry_number = str_replace('blog-entry-','', $entry_number);

            } elseif ($node_name === 'description') {
                $description = mysqli_real_escape_string($conn, $node_value);
            } elseif ($node_name === 'dc:date') {
                // convert the date to sql format and UTC
                $date = mysqli_real_escape_string($conn, $node_value);   // for security of the query
                $date = strtotime($date);
                $date = date('Y-m-d H:i:s', $date);
            }
        }

        // create the entry in database 
        $sql = "INSERT INTO BlogEntry (title, description, date, username, server_number, entry_number, url)
        VALUES ('$title', '$description', '$date', '$username',$server_number,$entry_number, '$url')";

        if ($conn->query($sql) === TRUE) {
            echo "Blog inserted successfully\n";
        } else {
            // check if the blog entry was already existing in the datebase.
            if ($conn->errno === 1062) {
                echo "Blog already exists\n";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // remove the blog entries older than 14 days
    $sql = "DELETE  FROM $dbname.BlogEntry WHERE DATE < DATE_SUB(NOW(), INTERVAL 14 DAY);";
    if ($conn->query($sql) === TRUE) {
        echo "Blog enrties older than 14 days deleted successfully\n";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


    $conn->close();
?>