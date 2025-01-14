<?php

include 'includes/_dbconnection.php';
include 'includes/_functions.php';

session_start();
checkCSRF('index.php');

header('content-type: application/json');



if (isset($_REQUEST['action'])) {

    $id = intval(htmlspecialchars($_REQUEST['id']));

    if (!is_int($id)) return;

    switch ($_REQUEST['action']) {
        case 'delete':
            deleteTransactionById($id);
            break;
    }
    echoSuccess($_REQUEST['action']);
    exit();
}
