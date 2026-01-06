<?php
include "bdd_connect.php";
// $arg['1'] => premiere variable envoyée par python
if ((isset($argv['1'])) AND (!empty($argv['1']))) {

    $dataReceived = $argv['1'];

    // "code,1,2,3,4,x"  ==>  "1234"
    $code = preg_replace('#code,([0-9]),([0-9]),([0-9]),([0-9]),[0-9]#', '$1$2$3$4', $dataReceived);

    // "code,x,x,x,x,1" ==> "1"
    $from = preg_replace('#code,([0-9],){4}([0-9])#', '$2', $dataReceived);

    // Verification de l'existance du code
    $verify_code = $bdd->prepare('SELECT COUNT(*) AS code_used FROM utilisateurs WHERE code=:userCode');
    $verify_code->execute(array(
        'userCode' => $code
    ));
    $verified_code = $verify_code->fetch();

    //si code non utilise
    if ($verified_code['code_used'] >= 1) {
        // CODE BON Envoie reponse RS232
	if($from == 1) {
        // Code bon, PIC INT
        system("sudo echo \"1\" > /dev/ttyUSB0");
	} elseif($from == 0) {
        // Code bon, PIC EXT
		system("sudo echo \"3\" > /dev/ttyUSB0");
	}
    // On récupère l'id utilisateur correspondant au code traité
    $getUserID = $bdd->prepare('SELECT id,code FROM utilisateurs WHERE code=:code');
    $getUserID->execute(array(
        'code' => $code
    ));
    $userInfo = $getUserID->fetch();

	$userid = $userInfo['id'];
    // On ajoute l'evenement au journal 
	$addEntry = $bdd->prepare('INSERT INTO journal(user_id, date_action, actionIO) VALUES (:user_id, NOW(), :actionIO)');
	$addEntry->execute(array(
		'user_id' => $userid,
		'actionIO' => $from
	));


    } else {
        // CODE FAUX Envoie reponse RS232
	if($from == 1) {
        // Code faux, PIC INT
		system("sudo echo \"2\" > /dev/ttyUSB0");
	} elseif($from == 0) {
        // Code faux, PIC EXT
		system("sudo echo \"4\" > /dev/ttyUSB0");
	} 
    }

}
