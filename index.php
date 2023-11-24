<?php
/* DB connection */
require_once 'includes/_dbconnection.php';
/* Functions filem */
require_once 'includes/_functions.php';
/* START USER SESSION */
session_start();
/* TOKEN CREATION */
generateToken();


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
                <p class="card-title pricing-card-title text-center fs-1 js-current-balance"><?=getCurrentAccountBalance()?> €</p>
            </div>
        </section>

        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h1 class="my-0 fw-normal fs-4">Opérations de Novembre 2022</h1>
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
                    <tbody>
                        <?php include 'display.php'; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <nav class="text-center">
                    <ul class="pagination d-flex justify-content-center m-2">
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-arrow-left"></i>
                            </span>
                        </li>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">Juillet 2023</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="index.php">Juin 2023</a>
                        </li>
                        <li class="page-item">
                            <span class="page-link">...</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="index.php">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
    </div>

    <div class="position-fixed bottom-0 end-0 m-3">
        <a href="add.php" class="btn btn-primary btn-lg rounded-circle">
            <i class="bi bi-plus fs-1"></i>
        </a>
    </div>

    <div class='visually-hidden position-absolute bottom-0 notif position-absolute w-50 top-10 text-center z-3 bg-info-subtle py-3 js-notif-display' id='notif'>
    </div>

    <div class='visually-hidden position-absolute bottom-0 notif position-absolute w-50 top-10 text-center z-3 bg-warning-subtle py-3 js-error-display' id='error'>
    </div>

    <?php include 'components/_footer.php'; ?>
</body>
</html>