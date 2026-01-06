<?php
// Connexion au serveur
try
	{
	$dns = 'mysql:host=localhost;dbname=serrure';
	$utilisateur = 'root';
	$motDePasse = 'DylanHugoAlexis';
	$bdd = new PDO( $dns, $utilisateur, $motDePasse );
	} catch ( Exception $e ) {
		echo "Connexion � MySQL impossible : ", $e->getMessage();
		die();}
?>
