<?php include 'php/url.php' ?>
<?php include 'php/bdd_connect.php' ?>
<?php include 'php/methods.php' ?>
<?php
    $setInterv = false;     // Par défaut il n'y a pas d'intervalle de date
    //  vérfication de l'existence des variable pour l'intervalle de date
    if ((isset($_POST['date-after'])) AND (!empty($_POST['date-after'])) AND (isset($_POST['date-before'])) AND (!empty($_POST['date-before']))) {
        $dateAfter = htmlspecialchars($_POST['date-after']);        // 1ere date sélectionné
        $dateBefore = htmlspecialchars($_POST['date-before']);      // 2eme date sélectionné
        $setInterv = true;      // Ici l'intervalle est définie
        /*
         * Requete SQL avec intervalle
         * Sélection de tout les champs de la table 'journal'
         * On joint le champ 'user_id' du journal au champs 'id' de la table 'utilisateurs'
         * On récupère les entrées/sorties durant la date sélectionnée
        */
        $getIO = $bdd->prepare("SELECT *
                                FROM journal
                                INNER JOIN utilisateurs
                                ON journal.user_id = utilisateurs.code
                                WHERE journal.date_action >= :date_after AND journal.date_action <= :date_before
                                ORDER BY date_action
                                ");
        // On éxécute la requete en indiquant les dates sélectionnées en POST
        $getIO->execute(array(
            'date_after' => $dateAfter,
            'date_before' => $dateBefore
        ));
    } else {
        /*
         * Requete SQL sans intervalle
         * On garde le meme systeme de jointure -> (line 14)
         * On récupère seulement les 20 dernières entrées/sorties provenant de la table 'journal'
        */
        $getIO = $bdd->query("SELECT *
                            FROM journal
                            INNER JOIN utilisateurs
                            ON journal.user_id = utilisateurs.id
                            ORDER BY date_action
                            LIMIT 20
                            ");
    }
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'php/models/head.php' ?>
</head>
<body>
	
	<?php include 'php/models/header.php' ?>

	<?php include 'php/models/sidebar.php'; ?>

	<div class="container">
		<div class="content">
            <div class="page-title">
                <h2>Journal</h2>
            </div>
            <!-- Bandeau de sélection de la date -->
            <div class="form-container" style="height: 180px; margin-bottom: 40px;">
                <form action="journal.php" method="post">
                    <label for="date">Du</label>
                    <!-- Si "$setInterv = true" alors on affiche les dates correspondnates -->
                    <input type="date" name="date-after" value="<?=$dateDispAfter = ($setInterv) ? $dateAfter : ""?>"/> 
                    <label for="date">Au</label>
                    <input type="date" name="date-before" value="<?=$dateDispBefore = ($setInterv) ? $dateBefore : ""?>"/>
                    <input class="btn btn-primary" type="submit" value="OK"/>
                    <span class="btn btn-secondary"><a href="journal.php">Reset</a></span>
                </form>
            </div>
            <!-- Tableau html -->
            <div class="table-container table-journal">
                <table>
                    <!-- En-tête de chaque colonne -->
                    <thead>
                    <tr>
                        <th class="thead-title">NOM</th>
                        <th class="thead-title">PRENOM</th>
                        <th class="thead-title">DATE</th>
                        <th class="thead-title">HEURE</th>
                        <th class="thead-title">ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // On récupère chaque ligne dans la table correpondant a la requête
                    while ($data = $getIO->fetch()) {
                        echo "<tr>";                                                // Début de la ligne html
                        echo "<td>" . $data['nom'] . "</td>";                       // Affichage Nom
                        echo "<td>" . $data['prenom'] . "</td>";                    // Affichage Prénom
                        echo "<td>" . formatDate($data['date_action']) . "</td>";   // Affichage de la DATE
                        echo "<td>" . formatTime($data['date_action']) . "</td>";   // Affichage de l'HEURE
                        echo "<td>" . actionIO($data['actionIO']) . "</td>";        // Affichage 'entrée' / 'sortie'
                        echo "</tr>";                                               // Fin de la ligne html
                    }   
                    ?>
                    </tbody>
                </table>
            </div> <!-- Fin tableau -->
		</div>
	</div>

    <?php include 'php/models/footer.php' ?>
</body>
</html>