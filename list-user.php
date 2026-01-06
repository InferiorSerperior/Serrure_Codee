<?php
include 'php/url.php';
include 'php/bdd_connect.php';

$users = $bdd->query('SELECT * FROM utilisateurs ORDER BY nom')
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'php/models/head.php' ?>
</head>
<body>

<?php include 'php/models/header.php' ?>

<?php include 'php/models/sidebar.php' ?>
<!-- Boite de dialogue -->
    <?php
    if ((isset($_GET['done'])) AND (!empty($_GET['done']))) {
        ?>
        <div class="action-done">
            <?php
            if ($_GET['done'] == "true") {
                ?>
                <div class="msgbox true">
                    <span class="msgbox-text">Utilisateur supprimé</span>
                </div>
            <?php
            } elseif (($_GET['done'] == "false") AND ((isset($_GET['error']))) AND (!empty($_GET['error']))) {
                ?>
                <div class="msgbox false">
                    <span class="msgbox-text"><?= $_GET['error'] ?></span>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
<!-- Fin boite de dialogue -->

<div class="container">
    <div class="content">
        <div class="page-title">
            <h2>Liste des utilisateurs</h2>
        </div>
<!-- Tableau : Liste des utilisateurs -->
        <div class="table-container table-user">
            <table>
                <thead>
                <tr>
                    <th class="thead-title">NOM</th>
                    <th class="thead-title">PRENOM</th>
                    <th class="thead-title">CODE</th>
                    <th class="thead-title">ACTION</th>
                </tr>
                </thead>

                <tbody>

                <?php
                // Résultat de la requete (Sélection de tout les utilisateurs)
                while ($data = $users->fetch()) {
                    ?>
                    <tr>
                        <td><?= $data['nom'] ?></td>
                        <td><?= $data['prenom'] ?></td>
                        <td><?= $data['code'] ?></td>
                        <td><a class="change icon-change" href="change-code.php?user_id=<?= $data['id'] ?>"></a><a class="red-cross icon-cross" href="php/bdd-op/delete_user.php?user-id=<?= $data['id'] ?>"></a></td>
                    </tr>
                <?php
                }
                ?>


                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'php/models/footer.php' ?>
</body>
</html>