<?php

require '_dbconnection.php';
require '_notifications.php';

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
function getCurrentAccountBalance(): int
{
    global $db_connect;
    $balance = $db_connect->prepare('SELECT SUM(amount) as account_balance FROM transaction;');
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

    $_SESSION['action'] = 'add';

}


function getTransactionByID(int $id): array
{
    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($id));

    if (is_int($id)) {
        $transaction = $db_connect->prepare('SELECT * FROM `transaction` JOIN category USING (id_category) WHERE id_transaction = :id;');
        $transaction->bindValue(':id', $id, PDO::PARAM_INT);
        $transaction->execute();
        return $transaction->fetch(PDO::FETCH_ASSOC);
    }
}

function updateTransaction(array $item): void
{

    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($item['id']));

    if (!is_int($id)) return;

    $transaction = $db_connect->prepare('UPDATE transaction SET name = :name, amount = :amount, date_transaction = :date_transaction, id_category = :id_category WHERE id_transaction = :id_transaction;');
    $transaction->bindValue(':name', $item['name'], PDO::PARAM_STR);
    $transaction->bindValue(':amount', $item['amount'], PDO::PARAM_INT);
    $transaction->bindValue(':date_transaction', $item['date'], PDO::PARAM_STR);
    $transaction->bindValue(':id_transaction', $id, PDO::PARAM_INT);
    $transaction->bindValue(':id_category', $item['category'], PDO::PARAM_INT);
    $transaction->execute();
}

function deleteTransactionById(int $id): void {
    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($id));

    if (!is_int($id)) return;

    $deletion = $db_connect->prepare('DELETE FROM transaction WHERE id_transaction = :id_transaction;');
    $deletion->bindValue(':id_transaction', $id, PDO::PARAM_INT);
    $deletion->execute();
}

function echoError(string $action): void
{
    global $error;
    echo json_encode([
        'output' => false,
        'action' => $error["{$action}"]
    ]);
}

function echoSuccess(string $action): void
{
    global $notif;

    echo json_encode([
        'output' => true,
        'action' => $notif["{$action}"],
        'balance' => getCurrentAccountBalance()
    ]);
}

