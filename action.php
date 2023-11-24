<?php

include 'includes/_dbconnection.php';
include 'includes/_functions.php';

session_start();


checkCSRF('index.php');


if(isset($_REQUEST)) {
    switch ($_REQUEST['action']) {
        /* ADD form */
        case 'add':
            addNewTransaction($_REQUEST);
            header('Location: index.php');
            break;                
        case 'edit':
            updateTransaction($_REQUEST);
            $_SESSION['action'] = 'edit';
            header('Location: index.php');
            break;   
        case 'prev-month' :
            $_SESSION['month'] = $_REQUEST['month'] - 1;
            header('Location: index.php');
            break;
    }

}

header('Location: index.php?');