<?php
	session_start();
	if(isset($_SESSION['id'])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1" /> 
		<meta name="author" content="Thomas Diot" />
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/sticky.full.js"></script>
		<?php require_once('include/config.php'); ?>
		<title><?php echo $site; ?> - Suivi du livre</title>
		<script type="text/javascript" src="js/chili-1.8b.js"></script>
		<script type="text/javascript" src="js/docs.js"></script>
	</head>

	<body id="site-wrapper">

		<?php
			include('include/no-js.php');
			$suivi = new Suivi($bdd);
		?>

		<div id="header">
			
			<?php 
				include('include/top.inc.php'); 
				include('include/menu.inc.php');
			?>
			
		</div>

		<div class="main" id="main-two-columns">

			<div class="left" id="main-content">
			
			<?php
				if(!empty($_GET['id']) && Livre::isExist($bdd, $_GET['id'])){
				$book = new Livre($bdd);
				$book->get($_GET['id']);
			?>
				
				<div class="success"><a href="admin.php">Administration</a> &rarr; 
				<?php 
					echo '<a href="suivi_livre.php?id=';
					echo $book->getId();
					echo '">Emprunts de "';
					echo $book->getTitle();
					echo '"</a></div>';
				?>
				
				<table cellspacing="1" class="tablesorter" id="tablesorter">
					<thead>
						<tr>
							<th>Demande</th>
							<th>Pret</th>
							<th>Retour</th>
							<th>Emprunteur</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Demande</th>
							<th>Pret</th>
							<th>Retour</th>
							<th>Emprunteur</th>

						</tr>
					</tfoot>
					<tbody>
						<?php
							$array = Suivi::getList($bdd, $book->getId());
							for($i=0;$i<count($array);$i++)
							{
								$membre = new Membre($bdd);
								$membre->get($array[$i]->getId_emprunteur());
								$membre = $membre->getNom() . ' ' . $membre->getPrenom();
								
								echo '<tr>';
									echo '<td>'.$array[$i]->getDemande().'</td>';
									echo '<td>'.$array[$i]->getPret().'</td>';
									echo '<td>'.$array[$i]->getRetour().'</td>';
									echo '<td>'.$membre.'</td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
				
				<?php
					}
					else{
				?>
					<p>Nous sommes désolés, mais le livre demandé ne semble plus exister... Si vous pensez être victime d'un complot, n'hésitez pas à contacter un administrateur !!</p>
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
					<a href="index.php"><img src="img/vide.png" alt="" title="Vide !" height="261" width="261" class="imagecenter" /></a>
				<?php
					}
				?>
				
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