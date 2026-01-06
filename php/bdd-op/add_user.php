<?php
include '../bdd_connect.php';
// Vérification de la 'presence' des variables
if ((isset($_POST['nom'])) AND (isset($_POST['prenom'])) AND (isset($_POST['code']))) {
    // Verification du contenu
    if((!empty($_POST['nom'])) AND (!empty($_POST['prenom'])) AND (!empty($_POST['code']))) {

        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $code = htmlspecialchars($_POST['code']);

        // Le code doit etre composé de 6 chiffres
        if ((strlen($code) == 6)) {
            //On vérifie si le code n'est pas déjà utilisé
            $verify_code = $bdd->prepare('SELECT COUNT(*) AS code_used FROM utilisateurs WHERE code=:codeEntered');
            $verify_code->execute(array(
                'codeEntered' => $code
            ));
            $verified_code = $verify_code->fetch();
            //si code non utilisé
            if ($verified_code['code_used'] < 1) {
                $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, code) VALUE (:nom, :prenom, :code)');
                $insert->execute(array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'code' => $code
                ));
                // redirection avec message d'action 'positif'
                header('Location: ../../add-user.php?done=true');

            } else {
                // Redirection avec message d'action 'negatif' (code deja utilisé)
                header('Location: ../../add-user.php?done=false&error=Ce code est déjà utilisé par un autre utilisateur');
            }

        } else {
            // Redirection avec message d'action 'negatif' (code non conforme)
            header('Location: ../../add-user.php?done=false&error=Le code doit contenir 4 chiffres');
        }
    }
}