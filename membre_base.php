<?php
	
	if( (isset($_POST['type']) && !empty($_POST['type'])) || (isset($_GET['type']) && !empty($_GET['type'])))
	{
	
		require_once('include/config.php');
		$type = (!empty($_POST['type'])) ? ($_POST['type']) : ($_GET['type']);
		
		// Add a new membre in the db
		if($type == 1 && !empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['email'])){
				header('Location: gerer_membres.php?message=5');
				
				$membre = new Membre($bdd);
				$membre->setNom($_POST['nom']);
				$membre->setPrenom($_POST['prenom']);
				$membre->setEmail($_POST['email']);
				$membre->setActif('1');
				$membre->add();
				
		}
		elseif($type == 1){
			echo 'a';
			header('Location: gerer_membres.php?message=9');
		}
		
		
		// Update a membre
		if($type == 2 && !empty($_POST['id']) AND !empty($_POST['modif'])){
			header('Location: gerer_membres.php?message=6');
			
			$membre = new Membre($bdd);
			$membre->setNom($_POST['nom']);
			$membre->setPrenom($_POST['prenom']);
			$membre->setEmail($_POST['email']);
			$membre->setActif(isset($_POST['actif']));
			$membre->setId($_POST['id']);
			$membre->update();
		}
		elseif($type == 2 && !empty($_POST['id']) AND !empty($_POST['suppr'])){
			Membre::deleteFromDb($bdd, $_POST['id']);
			header('Location: gerer_membres.php?message=10');
		}
		elseif($type == 2){
			echo 'a';
			header('Location: gerer_membres.php?message=9');
		}
		
		// Truncate database
		if($type == 4 && $_GET['pass'] == 'Oui je le veux !'){
			$truncate = Membre::truncateDb($bdd);
			$message = (is_array($truncate)) ? (11) : (9);
			header('Location: admin.php?message='.$message);
		}
		elseif($type == 4){
			header('Location: admin.php?message=9');
		}
	}
		
?>