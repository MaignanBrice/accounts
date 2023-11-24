<?php

require 'includes/_dbconnection.php';

/* MAIN items display */

$itemQuery = $db_connect->prepare("SELECT * 
                                    FROM transaction
                                        JOIN category USING (id_category)
                                    WHERE MONTH(date_transaction) = MONTH(NOW()) 
                                    AND YEAR(date_transaction) = 2022 
                                    ORDER BY date_transaction DESC;");
$itemQuery->execute();

foreach ($itemQuery->fetchAll(PDO::FETCH_ASSOC) as $item) {
?>
    <tr>
        <td width="50" class="ps-3">
            <i class="bi bi-<?= $item['icon_class'] ?> fs-3"></i>
        </td>
        <td>
            <time datetime="<?= $item['date_transaction'] ?>" class="d-block fst-italic fw-light"><?= $item['date_transaction'] ?></time>
            <?= $item['name'] ?>
        </td>
        <td class="text-end">


            <!-- Checks sign of amount -->
            <?php if ($item['amount'] < 0) {
            ?>
                <span class="rounded-pill text-nowrap bg-warning-subtle px-2"> 
                <?php
                } else {?>
                <span class="rounded-pill text-nowrap bg-success-subtle px-2">
                <?php } ?>

                <?= $item['amount'] ?>
                </span>
        </td>
        <td class="text-end text-nowrap">
            <a href="#" class="btn btn-outline-primary btn-sm rounded-circle">
                <i class="bi bi-pencil"></i>
            </a>
            <a href="#" class="btn btn-outline-danger btn-sm rounded-circle">
                <i class="bi bi-trash"></i>
            </a>
        </td>
    </tr>
<?php
}
?>