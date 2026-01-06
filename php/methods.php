<?php
/*
 * Ici sont présent 3 fonctions utilisés dans la page journal
 * Elles servent a traiter les infos reçcues depuis la bdd
*/
// Formatage de la date
function formatDate ($arg) {
    // "YYYY-MM-DD HH:mm:SS" ==> DD/MM/YYYY
    $formatedDate = preg_replace('#^([0-9]{4})-([0-9]{2})-([0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}$#', '$3/$2/$1', $arg);
    return $formatedDate;   // On retourne la chaine de caractère formaté
}
// Formatage de l'heure
function formatTime ($arg) {
    // "YYYY-MM-DD HH:mm:SS" ==> HH:mm
    $formatedTime = preg_replace('#^[0-9]{4}-[0-9]{2}-[0-9]{2} ([0-9]{2}:[0-9]{2}):[0-9]{2}$#', '$1', $arg);
    return $formatedTime;   // On retourne la chaine de caractère formaté
}
// Affiche du mot "Entrée" ou "Sortie" en fonction de l'info contenu dans la bdd
function actionIO ($arg) {
    if ($arg == 1) {
        $result = "Entrée";
    } elseif ($arg == 0) {
        $result = "Sortie";
    }
    return $result;
}