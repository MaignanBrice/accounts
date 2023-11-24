<?php 
$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php switch ($page) {
            case 'index.php':
                ?> Opérations de <?=date('F Y');
                break;
            case 'add.php':
                ?>Ajouter une opération - Mes Comptes<?php 
                break;
            case 'edit.php':
                ?> Modifier une opération - Mes Comptes<?php
                break;
            default : 
                ?> Erreur de chargement du titre <?php
        }   
            ?>
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>