<?php

require '_dbconnection.php';

/* Global Functions File */


/**
 * Set a 15 minutes session TOKEN in the superglobal $_SESSION
 * Uses MD5 encryption
 * @return void
 */
function generateToken(): void
{
    if (!isset($_SESSION['token']) || time() > $_SESSION['tokenExpire']) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        $_SESSION['tokenExpire'] = time() + 15 * 60;
    };
};

/**
 * Undocumented function
 *
 * @return void
 */
function getAccountBalance():int {
    global $db_connect;
    $balance = $db_connect->prepare('SELECT SUM(amount) as account_balance FROM transaction WHERE MONTH(date_transaction) = MONTH(NOW()) AND YEAR(date_transaction) = 2022;');
    $balance->execute();
    return $balance->fetchColumn();
}


