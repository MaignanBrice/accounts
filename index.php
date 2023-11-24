<?php
/* DB connection */
require_once 'includes/_dbconnection.php';
/* Functions filem */
require_once 'includes/_functions.php';
/* START USER SESSION */
session_start();
/* TOKEN CREATION */
generateToken();
/* Load Transaction */
include 'includes/_display.php';
include 'components/_head.php';
include 'components/_header.php';

?>

<body data-token='<?= $_SESSION['token'] ?>'>

    <div class="container">
        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h2 class="my-0 fw-normal fs-4">Solde aujourd'hui</h2>
            </div>
            <div class="card-body">
                <p class="card-title pricing-card-title text-center fs-1 js-current-balance"><?= getCurrentAccountBalance() ?> €</p>
            </div>
        </section>

        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h1 class="my-0 fw-normal fs-4">Opérations de <?=date('F Y');?></h1>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover align-middle js-table">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2">Opération</th>
                            <th scope="col" class="text-end">Montant</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class='js-table-display'>
                        <?php
                        displayTransaction(getTranscationByMonthYear(intval(date('m')), intval(date('Y'))));
                        ?>
                    </tbody>
                </table>
            </div>
            <?php include 'components/_pagination.php'; ?>
        </section>
    </div>

    <?php include 'components/_footer.php'; ?>
</body>

</html>