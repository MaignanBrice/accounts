<!DOCTYPE html>
<html lang="fr">


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
<body>

    <div class="container">
        <section class="card mb-4 rounded-3 shadow-sm">
            <div class="card-header py-3">
                <h1 class="my-0 fw-normal fs-4">Ajouter une opération</h1>
            </div>
            <div class="card-body">
                <form method='POST' action='action.php'>
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de l'opération *</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Facture d'électricité" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="amount" id="amount" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select class="form-select" name="category" id="category">
                            <option value="" selected>Aucune catégorie</option>
                            <option value="1">Habitat</option>
                            <option value="2">Travail</option>
                            <option value="3">Cadeaux</option>
                            <option value="4">Numérique</option>
                            <option value="5">Alimentation</option>
                            <option value="6">Voyage</option>
                            <option value="7">Loisir</option>
                            <option value="8">Voiture</option>
                            <option value="9">Santé</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg" name='action' value='add'>Ajouter</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <div class="position-fixed bottom-0 end-0 m-3">
        <a href="add.php" class="btn btn-primary btn-lg rounded-circle">
            <i class="bi bi-plus fs-1"></i>
        </a>
    </div>
    <?php include 'components/_footer.php'; ?>
</body>
</html>