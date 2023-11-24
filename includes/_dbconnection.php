<?php

/* Setting ENV variables */

$contents = file_get_contents('.env');
if ($contents) {
    $contents = explode('"', $contents);
    $contents = array_filter(
        $contents,
        function ($content) {
            return strlen($content) != 0;
        }
    );
    foreach ($contents as $index => $value) {
        if ($index % 2 === 0) {
            $value = str_replace('=', '', $value);
            $value = trim($value, "\r\n");
            $_ENV["$value"] = $contents[$index + 1];
        }
    }
}


/* DATABASE CONNECTION */

try {
    $db_connect = new PDO(
        $_ENV["DB_HOST"],
        $_ENV["DB_USER"],
        $_ENV["DB_PWD"]
    );
    $db_connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Impossible de se connecter à la BDD 😱' . $e->getMessage());
}