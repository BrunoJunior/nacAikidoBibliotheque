<?php

	include('include/connexion.php');
	
	// Connexion � la base de donn�es
	try{$bdd = new PDO('mysql:host='.$hote.';dbname='.$base.'', ''.$user.'', ''.$pass.'');}
	catch(Exception $e){
		die('Erreur : ' . $e->getMessage() . '<br /><b>Etes vous pass� par le gestionnaire d\'installation ? Rendez vous vite dans le dossier install via votre navigateur !</b>');
	}
	
	// Insertion des biblioth�ques
	require_once('include/function.inc.php');
	require_once('class/livre.class.php');
	require_once('class/membres.class.php');
	require_once('class/suivi.class.php');
	
	// Pr�f�rences du site
	$q = $bdd->query('SELECT * FROM preferences');
	$pref = $q->fetchAll();
	$site = $pref[1]['value'];
	$nombre = Livre::numberBook($bdd);
	$livres_recents = ($nombre > $pref[2]['value']) ? ($pref[2]['value']) : ($nombre);
	$nombre_livre_mieux_notes = ($nombre > $pref[3]['value']) ? ($pref[3]['value']) : ($nombre);
	$livre_une = $pref[4]['value'];
	$nb = Livre::getFavorite($bdd,100000);
	$favorite_books = ($nb > $pref[5]['value']) ? ($pref[5]['value']) : ($nombre);
	
	// Messages d'erreurs
	$message = array(
		1 => 'Votre r�servation a bien �t� enregistr�e',
		2 => 'Vous �tes maintenant d�connect�',
		3 => 'D�sol�, mais vous ne pouvez pas acc�der � cette page...',
		4 => 'Votre compte est d�sormais cr��',
		5 => 'La donn�e a bien �t� cr��e !',
		6 => 'La modification a bien �t� enregistr�e',
		7 => 'Vous �tes maintenant connect�',
		8 => 'Les mots cl�s ont bien �t� mis � jour !',
		9 => 'Une erreur a �t� rencontr�e... Merci de recommencer',
		10 => 'La donn�e a bien �t� supprim�e !',
		11 => 'La base de donn�es a correctement �t� vid�e !',
		12 => 'Adresse email renseign�e inconnue du club !',
		13 => 'Vous serez pr�venu d�s que le livre sera disponible !',
		14 => 'Votre demande de pr�t a bien �t� prise en compte !',
	);
	
	// Affichage des messages d'erreur
	if(!empty($_GET['message']) && $_GET['message'] > 0 && array_key_exists($_GET['message'], $message)){
		echo "<script> $(document).ready(function(){ $.sticky(' ";
		echo $message[$_GET['message']];
		echo "');});</script>";
	}
	
	
?>