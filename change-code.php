<?php
include 'php/url.php';
include 'php/bdd_connect.php';
/*
 * Ses 2 requetes servent a sélection des utilisateurs :
 * 1) Avec un id definie depuis la liste des utilisateurs
 * 2) Pas d'id definie donc on récupère donc tout les utilisateurs (par défaut)
*/
$changeByID = $bdd->prepare('SELECT * FROM utilisateurs WHERE id=?');
$getUserList = $bdd->query('SELECT * FROM utilisateurs ORDER BY nom')
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'php/models/head.php' ?>
</head>
<body>

<?php include 'php/models/header.php' ?>

<?php include 'php/models/sidebar.php' ?>
<!--  Boite de dialogue  -->
    <?php
    if ((isset($_GET['done'])) AND (!empty($_GET['done']))) {
        ?>
        <div class="action-done">
            <?php
            if ($_GET['done'] == "true") {
                ?>
                <div class="msgbox true">
                    <span class="msgbox-text">Changement de mot de passe effectué</span>
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
<!--  Fin boite de dialogue  -->

<div class="container">
    <div class="content">
        <div class="page-title">
            <h2>Modifier un code</h2>
        </div>

        <?php
            /*
             * 2 modes d'affichage sont disponibles :
             * 1) Avec l'utilisateur déjà sélectionné depuis la page 'list-user.php' en GET
             * 2) Aucun utilisateur sélectionné (par défaut)
            */
            // On vérifie si on récupère l'id d'un utilisateurs en provenance de la page 'list-user.php' en GET
            if((isset($_GET['user_id'])) AND (!empty($_GET['user_id']))) {
                // Si 'user_id' est définie
                $userID = htmlspecialchars($_GET['user_id']);   // On stocke l'id dans $userID
                $changeByID->execute(array($userID));           // On éxécute la requete 'changeByID' en renseigant l'id reçue en GET

                /*
                 * On récupère les informations recues par la requete
                 * La requete fait toujours réference a un seul et unique utilisateurs,
                 * donc on récupère les informations sans avoir besoin de 'while'
                */
                $userInfo = $changeByID->fetch();
                ?>


                <div class="form-container" style="height: 140px;">
                    <!-- On récupère le code à changer dans la page 'change_code.php' -->
                    <form action="php/bdd-op/change_code.php" method="post">
                        <!-- On créer un champs invisible contenant l'id de l'utilisateur pour le traitement -->
                        <input type="hidden" name="user" value="<?= $userInfo['id'] ?>"/>
                        <!-- On affiche sont nom et prénom -->
                        <div class="label"><span class="selected-user"><?php echo $userInfo['nom'] . " " .  $userInfo['prenom'] ?></span></div>
                        <input type="text" name="code" placeholder="Entrez le nouveau code [6 chiffres]"/>
                        <input class="btn btn-primary" style="position: absolute;top:160px;right: 65px;" type="submit" value="Modifier le code"/>
                    </form>
                </div>

                <?php
            } else {
                ?>


                <div class="form-container" style="height: 140px;">
                    <form action="php/bdd-op/change_code.php" method="post">
                    <!-- Dans un menu déroulant on affiche les noms et prénoms de chaque utilisateurs et leur id stocké dans la 'value'
                    Pour le traitement dans 'change_code.php' -->
                    <select name="user" class="">
                        <?php
                            while($data = $getUserList->fetch()) {
                                echo "<option value=\"" . $data['id'] . "\">" . $data['nom'] . " " . $data['prenom'] . "</option>";
                            }
                        ?>
                        <input type="text" name="code" placeholder="Entrez le nouveau code"/>
                        <input class="btn btn-primary" type="submit" value="Modifier le code"/>
                    </select>
                    </form>
                </div>


                <?php
            }
        ?>
    </div>
</div>

<?php include 'php/models/footer.php' ?>
</body>
</html>