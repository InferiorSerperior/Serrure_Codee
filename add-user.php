<?php include 'php/url.php' ?>

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
                      <span class="msgbox-text">Utilisateur ajouté</span>
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
                <h2>Ajouter un utilisateur</h2>
            </div>
            <!-- Formulaire d'ajout d'un utilisateur -->
            <div class="form-container" style="height: 290px;">
                <form action="php/bdd-op/add_user.php" method="post">
                    <div class="form-sec">Données personelles</div>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" placeholder="Nom"/>

                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" placeholder="Prénom"/>

                    <div class="form-sec">Mot de passe</div>
                    <label for="code">Code [6 chiffres]</label>
                    <input type="text" name="code" placeholder="Code"/>

                    <input class="btn btn-primary" type="submit" value="Enregistrer"/>
                </form>
            </div>
        </div>
    </div>


    <?php include 'php/models/footer.php' ?>
</body>
</html>