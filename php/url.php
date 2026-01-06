<?php 
$URL = $_SERVER['REQUEST_URI'];     // On récupère l'url de la page

// On récupère uniquement le nom de la page
// ex : http://local.dev/Dropbox/web/serrure/change-code.php?user_id=6 ==> 'change-code'
$page = preg_replace('#[a-zA-Z0-9._/-]*/serrure/([a-z0-9._/-]+).php[?A-za-z0-9=._/%&-]*#i', '$1', $URL);

// Si ce sont des 'sous page' :
if (($page == "add-user") OR ($page == "list-user") OR ($page == "change-code")) {
    // On affichera la "categorie"
	$origin = "UTILISATEURS";

    if ($page == "add-user") {
        $pageName = "Ajout d'un utilisateur";
    } elseif ($page == "list-user") {
        $pageName = "Liste des utilisateurs";
    } elseif ($page == "change-code") {
        $pageName = "Modifier un code";
    }

} elseif ($page == "unlock") {

	$origin = "DÉVERROUILLER LA GÂCHE";

} else {
    // Par défaut on affiche le nom de la page en majuscule
	$origin = strtoupper($page);

}
