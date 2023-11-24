<?php

include 'includes/_dbconnection.php';
include 'includes/_functions.php';

session_start();
checkCSRF('index.php');


var_dump($_REQUEST);

if(isset($_REQUEST)) {
    switch ($_REQUEST['action']) {
        /* ADD form */
        case 'add':
            addNewTransaction($_REQUEST);
            header('Location: index.php?');
            break;

    }

}


header('Location: index.php?');