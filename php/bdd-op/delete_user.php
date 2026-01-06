<?php
include '../bdd_connect.php';

$id_used = array();

// Vérification de la presence des variale $_GET['user-id]
if ((isset($_GET['user-id'])) AND (!empty($_GET['user-id']))) {

    $user_id = htmlspecialchars($_GET['user-id']);

    //Vérification du type de la variable (int)
    if ($user_id = (int) $user_id) {
        //Verification de la presence de l'utilisateurs
        $verify_id = $bdd->prepare('SELECT COUNT(*) AS id_used FROM utilisateurs WHERE id=:userID');
        $verify_id->execute(array(
            'userID' => $user_id
        ));
        $verified_id = $verify_id->fetch();

        if ($verified_id >= 1) {
            //Suppression utilisateur
            $delete = $bdd->prepare('DELETE FROM utilisateurs WHERE id=?');
            $delete->execute(array($user_id));
            //redirection avec message d'action 'positif'
            header('Location: ../../list-user.php?done=true');

        } else {
            // redirection avec message d'action 'negatif' (Utilisateur inconnu)
            header('Location: ../../list-user.php?done=true&error=Utilisateur inconnu');
        }
    } else {
        // redirection avec message d'action 'negatif' 
        header('Location: ../../list-user.php?done=true&error=Données corrompues');
    }
} else {
    // redirection avec message d'action 'negatif' 
    header('Location: ../../list-user.php?done=true&error=Information non transmise');
}