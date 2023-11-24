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
    $db_connect->beginTransaction();
    $balance = $db_connect->prepare('SELECT SUM(amount) as account_balance FROM transaction;');
    $balance->execute();

    if ($balance->rowCount() !== 1) $db_connect->rollback();

    $db_connect->commit();

    return $balance->fetchColumn();
}

/**
 * Add a new transaction in the database
 *
 * @param array $array
 * @return void
 */
function addNewTransaction(array $array): void
{
    global $db_connect;

    $db_connect->beginTransaction();

    $transaction = $db_connect->prepare('INSERT INTO transaction (name, amount, date_transaction, id_category) VALUES (:name, :amount, :date, :idcategory);');

    $transaction->bindValue(':name', $array['name'], PDO::PARAM_STR);
    $transaction->bindValue(':amount', $array['amount'], PDO::PARAM_INT);
    $transaction->bindValue(':date', $array['date'], PDO::PARAM_STR);
    $transaction->bindValue(':idcategory', $array['category'], PDO::PARAM_INT);

    $transaction->execute();

    if ($transaction->rowCount() !== 1) $db_connect->rollback();

    $db_connect->commit();

    $_SESSION['action'] = 'add';
}

/**
 * Return an Assoc array with all datas of a transaction
 * Searching by her ID
 *
 * @param integer $id
 * @return array
 */
function getTransactionByID(int $id): array
{
    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($id));

    if (is_int($id)) {
        $db_connect->beginTransaction();
        $transaction = $db_connect->prepare('SELECT * FROM `transaction` JOIN category USING (id_category) WHERE id_transaction = :id;');
        $transaction->bindValue(':id', $id, PDO::PARAM_INT);
        $transaction->execute();
        if ($transaction->rowCount() !== 1) $db_connect->rollback();

        $db_connect->commit();
        return $transaction->fetch(PDO::FETCH_ASSOC);
    }
}

/**
 * Return all transactions in the DB depending of a month and a year in parameter
 * 
 *
 * @param integer $month
 * @param integer $year
 * @return array
 */
function getTranscationByMonthYear(int $month, int $year): array
{
    global $db_connect;
    $itemQuery = $db_connect->prepare("SELECT * 
                                    FROM transaction
                                        JOIN category USING (id_category)
                                    WHERE MONTH(date_transaction) = :month
                                    AND YEAR(date_transaction) = :year
                                    ORDER BY date_transaction DESC;");
    $itemQuery->bindValue(':month', $month, PDO::PARAM_INT);
    $itemQuery->bindValue(':year', $year, PDO::PARAM_INT);
    $itemQuery->execute();

    return $itemQuery->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Takes an array in parameter to update a transaction
 *
 * @param array $item
 * @return void
 */
function updateTransaction(array $item): void
{

    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($item['id']));

    if (!is_int($id)) return;

    $db_connect->beginTransaction();

    $transaction = $db_connect->prepare('UPDATE transaction SET name = :name, amount = :amount, date_transaction = :date_transaction, id_category = :id_category WHERE id_transaction = :id_transaction;');
    $transaction->bindValue(':name', $item['name'], PDO::PARAM_STR);
    $transaction->bindValue(':amount', $item['amount'], PDO::PARAM_INT);
    $transaction->bindValue(':date_transaction', $item['date'], PDO::PARAM_STR);
    $transaction->bindValue(':id_transaction', $id, PDO::PARAM_INT);
    $transaction->bindValue(':id_category', $item['category'], PDO::PARAM_INT);
    $transaction->execute();

    if ($transaction->rowCount() !== 1) $db_connect->rollback();

    $db_connect->commit();

    $_SESSION['action'] = 'edit';
}

/**
 * Delete a transaction in the database depending of her ID
 *
 * @param integer $id
 * @return void
 */
function deleteTransactionById(int $id): void
{
    global $db_connect;
    /* Clean ID */
    $id = intval(htmlspecialchars($id));

    if (!is_int($id)) return;

    $db_connect->beginTransaction();

    $deletion = $db_connect->prepare('DELETE FROM transaction WHERE id_transaction = :id_transaction;');
    $deletion->bindValue(':id_transaction', $id, PDO::PARAM_INT);
    $deletion->execute();
    if ($deletion->rowCount() !== 1) $db_connect->rollback();

    $db_connect->commit();

    $_SESSION['action'] = 'delete';
}



/**
 * Echo a json depending of the action in parameter
 * Used in Asynchronous queries
 *
 * @param string $action
 * @return void
 */
function echoError(string $action): void
{
    global $error;
    echo json_encode([
        'output' => false,
        'error' => $error["{$action}"],
        'action' => $action
    ]);
}

/**
 * Echo a json depending of the action in parameter
 * Used in Asynchronous queries
 *
 * @param string $action
 * @return void
 */
function echoSuccess(string $action): void
{
    global $notif;

    echo json_encode([
        'output' => true,
        'notif' => $notif["{$action}"],
        'action' => $action,
        'balance' => getCurrentAccountBalance()
    ]);
}
