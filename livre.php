<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1" /> 
		<meta name="author" content="Thomas Diot" />
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/sticky.full.js"></script>
		<?php include('include/config.php'); ?>
		<title><?php echo $site; ?> - Fiche de livre</title>
	</head>
	
	<body id="site-wrapper">
	
		<?php
			include('include/no-js.php');
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
				<img src="<?php echo $book->getCover(); ?>" width="200" height="300" class="cover" />
				
				<h4><?php echo $book->getTitle(); ?> de <?php echo strtoupper($book->getAuthor()); ?></h4>
				
				<div id="consult">
				
					<fieldset>
						<legend>&nbsp;A propos de ce livre :&nbsp;</legend>
						<span class="titre">Année de parution : </span><?php echo $book->getYear(); ?><br />
						<span class="titre">Collection : </span><?php echo $book->getCollection(); ?><br />
						<span class="titre">Note : </span>
						<?php
							$i = 0;
							while($i < $book->getNote()){
								echo '<img src="img/etoile.png" />';
								$i++;
							}
							while($i < 5){
								echo '<img src="img/etoile_grise.gif" />';
								$i++;
							}
						?>
						<br />
						<span class="titre">Isbn : </span><?php echo $book->getIsbn(); ?><br />
						<span class="titre">Propriété : </span><?php echo $book->getProprietaire(); ?><br />
						<span class="titre">Disponibilité : </span><?php echo '<img src="img/etat_'.$book->getId_etat().'.png" alt="'.Livre::getValeurEtat($bdd, $book->getId_etat()).'" title="'.Livre::getValeurEtat($bdd, $book->getId_etat()).'" />'; ?>
						<?php
							echo '&nbsp;<a href="demande_livre?id='.$_GET['id'].'">';
							if($book->getId_etat() == 1){
								echo 'Demande de prêt';
							}
							else{
								echo 'Etre averti de sa disponibilité';
							}
							echo '</a>';
						?>
						<br />
						
						<?php 
							if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
								echo '<span class="titre">Suivi : </span><a href="suivi_livre?id='.$_GET['id'].'">Consulter le suivi du livre</a><br />';
								echo '<span class="titre">Action : </span>';
								
								if($book->getId_etat() != 1){
									echo '<a href="livre_base?id='.$_GET['id'].'&type=9&action='.$book->getId_etat().'">';
									if($book->getId_etat() == 3){
										echo 'Acheminer';
									}
									elseif($book->getId_etat() == 5){
										echo 'Prêter';
									}
									elseif($book->getId_etat() == 2){
										echo 'Retourner';
									}
									elseif($book->getId_etat() == 6 || $book->getId_etat() == 4){
										echo 'Rendre disponible';
									}
									echo '</a>';
								}
								else{
									echo 'Aucune action disponible';
								}
								echo '<br />';
							}
						?>
						
					</fieldset>
					<br /><br />
					<span class="titre">Résumé: </span><?php echo $book->getResume(); ?><br />
				
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
			
			</div>

			<?php include('include/sidebar_right.inc.php'); ?>

		</div>

		<div id="footer">

			<?php include('include/footer.inc.php'); ?>

		</div>

	</body>
	
</html>