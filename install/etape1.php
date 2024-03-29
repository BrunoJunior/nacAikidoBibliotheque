﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>
		Installation de ConnectedBooks
	</title>
	<link rel="stylesheet" type="text/css" href="style.css" media="all" />
</head>

<body>

	
	<div id="Header">
		Installation de ConnectedBooks : Etape 1
	</div>
	
	<br />
	
	<div id="Content">
		<h1>Connexion à la base de données</h1>
		
		<p>&nbsp;</p>
		
		<?php
			if(!empty($_GET['error'])){
				echo '<p>Une erreur a été rencontrée. Merci de réessayer.<br /><br />';
				echo $error;
				echo '<p>&nbsp</p>';
				echo '<p>&nbsp</p>';
			}
		?>
		
			<p>Nous voilà lancés comme des boulets ... de canons ! Pour fonctionner, l'application requiert une base de données SQL: nous allons donc la configurer. Vous allez pour cela remplir les quelques champs présents ci-dessous: tout d'abord, l'adresse de la base (par exemple, avec wamp et consorts, c'est localhost), puis le nom de la base utilisée, votre login de connexion (avec le même logiciel c'est en général wamp), et enfin le mot de passe qui lui est associé.</p>
			
			<p>&nbsp;</p>
			
			<p>A noter que si vous avez un jour besoin de changer, il vous sera plus pratique de passer par le fichier d'enregistrement: vous pourrez modifier le fichier <b>connexion.php</b> se situant dans le dossier <b>include</b>... Mais finissons notre connexion avant de parler du futur ! Nous allons maintenant tester deux paramètres de votre serveur: le logiciel va d'abord regarder si il a le droit de se connecter à la base de données, puis il vérifiera qu'il peut correctement se connecter à internet. En fait, c'est déjà fait ! Voici les résultats:</p>
			
			<p>&nbsp;</p>
			
			<p>
				<table cellpadding="10" cellspacing="1" width="100%"> 
						
					<tr> 
						<th>Test</th>
						<th>Résultat</th>
					</tr> 
					
					<tr> 
						<th>Vérification de la bonne connexion à la base de données avec la méthode PDO</th> 
						<td>
							<?php 
								echo (defined('PDO::ATTR_DRIVER_NAME')) ? ('Le plugin PDO est activé sur votre serveur, ce qui est parfait pour la suite !') : ('Le plugin PDO n\'est pas activé sur votre serveur, ce qui est assez problématique pour la suite... Merci de <a href="http://www.commentcamarche.net/forum/affich-18243296-easyphp-et-pdo#vote_18243581" target="_blank">l\'activer</a> pour continuer');
							?>
						</td> 
					</tr>
					
					<tr>
						<th>Vérification de la connexion internet requise pour le fonctionnement de l'application</th>
						<td>
							<?php
								$content = @file_get_contents('http://www.google.fr');
								if($content === FALSE) {echo 'Il semble y avoir un problème avec la connexion: vérifiez que vous êtes bien connecté au réseau internet. D\'autre part, il se peut que votre serveur n\'accepte pas les requêtes de l\'application: il faut activer la fonction <i>allow_url_fopen</i> dans le fichier php.ini, en passant le paramètre sur on (cf: <a href="http://www.lecoindunet.com/activer-la-fonction-filegetcontent-sur-hebergement-1and1-mutualise-63" target="_blank">Activer la fonction file_get_contents</a>';}
								else{echo 'La connexion est parfaite ! L\'algorithme de reconnaissance du numéro ISBN va très bien fonctionner !';}
							?>
						</td>
					</tr>
				
				</table> 
			</p>
			
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
				
			<?php
				if(defined('PDO::ATTR_DRIVER_NAME')){
			?>
					<div id="stylized" class="myform">
						<form id="form" name="form" method="post" action="connexion.php">
						<h1>Connectons, connectons !</h1>
						<p>La véracité de ces informations en va de la rapidité de l'installation...</p>

						<label>Hôte de connexion
							<span class="small">Adresse du serveur</span>
						</label>
						<input type="text" name="hote" id="hote" />
						
						<label>Base
						<span class="small">Nom de la base</span>
						</label>
						<input type="text" name="base" id="base" />
						
						<label>Identifiant
						<span class="small">Votre identifiant</span>
						</label>
						<input type="text" name="login" id="login" />

						<label>Mot de passe
						<span class="small">Votre mot de passe</span>
						</label>
						<input type="password" name="pass" id="pass" />

						<button type="submit">Connexion !!</button>
						<div class="spacer"></div>

						</form>
					</div>
			<?php
				
			
			}
		?>
	</div>

	
</body>
</html>