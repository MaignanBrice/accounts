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
 * Run the basic verification for token and REFERER to avoid CSRF 
 * injection.
 * Redirect in case of error.
 *
 * @param string $url
 * @return void
 */
function checkCSRF(string $url): void
{
    if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], 'http://localhost/accounts')) {
        header('Location: ' . $url . '?error=error_referer');
        exit;
    } else if (!isset($_SESSION['token']) || !isset($_REQUEST['token']) || $_REQUEST['token'] !== $_SESSION['token'] || $_SESSION['tokenExpire'] < time()) {
        header('Location: ' . $url . '?error=error_token');
        exit;
    }
}

/**
 * Return the current account balance 
 *
 * @return int
 */
function getAccountBalance(): int
{
    global $db_connect;
    $balance = $db_connect->prepare('SELECT SUM(amount) as account_balance FROM transaction WHERE MONTH(date_transaction) = MONTH(NOW()) AND YEAR(date_transaction) = 2022;');
    $balance->execute();
    return $balance->fetchColumn();
}


function addNewTransaction(array $array): void
{
    global $db_connect;

    $transaction = $db_connect->prepare('INSERT INTO transaction (name, amount, date_transaction, id_category) VALUES (:name, :amount, :date, :idcategory);');

    $transaction->bindValue(':name', $array['name'], PDO::PARAM_STR);
    $transaction->bindValue(':amount', $array['amount'], PDO::PARAM_INT);
    $transaction->bindValue(':date', $array['date'], PDO::PARAM_STR);
    $transaction->bindValue(':idcategory', $array['category'], PDO::PARAM_INT);

    $transaction->execute();
}
