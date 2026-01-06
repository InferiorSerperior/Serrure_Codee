<?php
include '../bdd_connect.php';
// Verification des variables en POST
if ((isset($_POST['user'])) AND (!empty($_POST['user'])) AND (isset($_POST['code'])) AND (!empty($_POST['code']))) {

    $user_id = $_POST['user'];
    $new_code = htmlspecialchars($_POST['code']);

    // Le code doit etre composé de 4 chiffres
    if ((strlen($new_code) == 6)) {
        //On vérifie si le code n'est pas déjà utilisé
        $verify_code = $bdd->prepare('SELECT COUNT(*) AS code_used FROM utilisateurs WHERE code=:userCode');
        $verify_code->execute(array(
            'userCode' => $new_code
        ));
        $verified_code = $verify_code->fetch();
        //si code non utilisé
        if ($verified_code['code_used'] < 1) {
            $code_verify = true;
        } else {
            $code_verify = false;
            // redirection avec message d'action 'negatif' (code deja utilisé)
            header('Location: ../../change-code.php?done=false&error=Ce code est déjà utilisé par un autre utilisateur');
        }
    } else {
        $code_verify = false;
        // redirection avec message d'action 'negatif' (code non conforme)
        header('Location: ../../change-code.php?done=false&error=Le code doit contenir 4 chiffres');
    }

    //Vérification de l'id envoyé
    if ($user_id = (int) $user_id) {
        $verify_id = $bdd->prepare('SELECT COUNT(*) AS id_used FROM utilisateurs WHERE id=:userID');
        $verify_id->execute(array(
            'userID' => $user_id
        ));
        $verified_id = $verify_id->fetch();
        //si id utilisé
        if ($verified_id['id_used'] >= 1) {
            $id_verify = true;
        } else {
            $id_verify = false;
            // Id changé volontairement non accepté
            header('Location: ../../change-code.php?done=false&error=Utilisateur inconnu');
        }
    } else {
        // Id changé volontairement non accepté
        header('Location: ../../change-code.php?done=false&error=Données corrompues');
    }

    // Si L'id est correct ainsi que le code
    if ($id_verify AND $code_verify) {
        $update = $bdd->prepare('UPDATE utilisateurs SET code = :newcode WHERE id = :userid');
        $update->execute(array(
            'newcode' => $new_code,
            'userid' => $user_id
        ));
        // Redirection avec message d'action 'positif'
        header('Location: ../../change-code.php?done=true');
    }

} else {
    // redirection avec message d'action 'negatif' (champs non vailde)
    header('Location: ../../change-code.php?done=false&error=Veuillez remplir la totalité des champs');
}