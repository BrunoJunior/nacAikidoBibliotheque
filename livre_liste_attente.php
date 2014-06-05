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
		<title><?php echo $site; ?> - Demande des emprunts en attente</title>
		<script type="text/javascript" src="js/chili-1.8b.js"></script>
		<script type="text/javascript" src="js/docs.js"></script>
	</head>

	<body id="site-wrapper">

		<?php
			include('include/no-js.php');
			$book = new Livre($bdd);
		?>

		<div id="header">
			
			<?php 
				include('include/top.inc.php'); 
				include('include/menu.inc.php');
			?>
			
		</div>

		<div class="main" id="main-two-columns">

			<div class="left" id="main-content">
				
				<div class="success"><a href="admin.php">Administration</a> &rarr; <a href="livre_liste_emprunts.php">Demande des emprunts en attente</a></div>
				
				<table cellspacing="1" class="tablesorter" id="tablesorter">
					<thead>
						<tr>
							<th>ID</th>
							<th>Titre</th>
							<th>Auteur</th>
							<th>Etat</th>
							<th>Demandeur</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Titre</th>
							<th>Auteur</th>
							<th>Etat</th>
							<th>Demandeur</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
							$array = Livre::getListDemandes($bdd);
							for($i=0;$i<count($array);$i++)
							{
								$emrunteur = Suivi::getEmprunteur($bdd, $array[$i]->getId());
								echo '<tr>';
									echo '<td><a href="livre?id='.$array[$i]->getId().'">'.$array[$i]->getId().'</a></td>';
									echo '<td><a href="livre?id='.$array[$i]->getId().'">'.$array[$i]->getTitle().'</a></td>';
									echo '<td><a href="livre?id='.$array[$i]->getId().'">'.$array[$i]->getAuthor().'</a></td>';
									echo '<td><a href="suivi_livre?id='.$array[$i]->getId().'">'.Livre::getValeurEtat($bdd, $array[$i]->getId_etat()).'</a></td>';
									echo '<td><a href="suivi_livre?id='.$array[$i]->getId().'">'.$emrunteur->getNom().' '.$emrunteur->getPrenom().'</a></td>';
								echo '</tr>';
							}
						?>
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