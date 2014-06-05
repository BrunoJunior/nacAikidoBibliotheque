<?php
	session_start();
	if(isset($_SESSION['id'])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1" /> 
		<meta name="author" content="Bruno Desprez" />
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/sticky.full.js"></script>
		<?php require_once('include/config.php'); ?>
		<title><?php echo $site; ?> - Liste des membres</title>
		<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
		<script type="text/javascript" src="js/chili-1.8b.js"></script>
		<script type="text/javascript" src="js/docs.js"></script>
	</head>

	<body id="site-wrapper">

		<?php
			include('include/no-js.php');
			$membre = new Membre($bdd);
		?>

		<div id="header">
			
			<?php 
				include('include/top.inc.php'); 
				include('include/menu.inc.php');
			?>
			
		</div>

		<div class="main" id="main-two-columns">

			<div class="left" id="main-content">
				
				<div class="success"><a href="admin.php">Administration</a> &rarr; <a href="livre_enreg.php">Liste des membres enregistrés</a></div>
				
				<table cellspacing="1" class="tablesorter" id="tablesorter">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nom</th>
							<th>Prénom</th>
							<th>Email</th>
							<th>Actif</th>
							<th>Action</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Nom</th>
							<th>Prénom</th>
							<th>Email</th>
							<th>Actif</th>
							<th>Action</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
							$array = Membre::getList($bdd);
							for($i=0;$i<count($array);$i++)
							{
								echo '<tr>';
									echo '<form method="post" action="membre_base.php" id="ajout">';
										echo '<input type="hidden" name="type" value="2" />';
										echo '<input type="hidden" name="id" value="'.$array[$i]->getId().'" />';
										echo '<td>'.$array[$i]->getId().'</td>';
										echo '<td><input type="text" name="nom" class="text" value="'.$array[$i]->getNom().'" /></td>';
										echo '<td><input type="text" name="prenom" class="text" value="'.$array[$i]->getPrenom().'" /></td>';
										echo '<td><input type="email" name="email" class="text" value="'.$array[$i]->getEmail().'" /></td>';
										echo '<td><input type="checkbox" name="actif"';
										if($array[$i]->getActif()){ echo ' checked';}
										echo '></td>';
										echo '<td><input type="submit" name="modif" value="Modifier" /><input type="submit" name="suppr" value="Supprimer" /></td>';
									echo '</form>';
								echo '</tr>';
							}
						?>
						<tr>
							<td></td>
							<form method="post" action="membre_base.php" id="ajout">
								<input type="hidden" name="type" id="type" value="1" />
								<td><input type="text" name="nom" id="nom" class="text" /></td>
								<td><input type="text" name="prenom" id="prenom" class="text" /></td>
								<td><input type="email" name="email" id="email" class="text" /></td>
								<td><input type="checkbox" name="actif" checked></td>
								<td><input type="submit" name="envoi" id="envoi" value="Ajouter" /></td>
							</form>
						</tr>
					</tbody>
				</table>
			</div>

			<?php include('include/sidebar_right.inc.php'); ?>

		</div>

		<div id="footer">

			<?php include('include/footer.inc.php'); ?>

		</div>

	</body>
	
</html>
<?php
	}
	else{
		header('Location: index.php?message=3');
	}
?>