<?php

require 'includes/_dbconnection.php';

// $month = $_SESSION['month'];
// $year = $_SESSION['year'];

/* MAIN items display */

function displayTransaction(array $array): void
{
    foreach ($array as $item) {
?>
        <tr data-transaction-id='<?= $item['id_transaction'] ?>'>
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
                } else { ?>
                        <span class="rounded-pill text-nowrap bg-success-subtle px-2">
                        <?php } ?>

                        <?= $item['amount'] ?>
                        </span>
            </td>
            <td class="text-end text-nowrap">
                <a href='edit.php?id=<?= $item['id_transaction'] ?>' class="btn btn-outline-primary btn-sm rounded-circle" name='action' value='edit'>
                    <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-outline-danger btn-sm rounded-circle js-delete-btn" data-id='<?= $item['id_transaction'] ?>' data-action='delete'>
                    <i class="bi bi-trash js-delete-btn" data-id='<?= $item['id_transaction'] ?>' data-action='delete'></i>
                </button>
            </td>
        </tr>
<?php
    }
}
?>