<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1" /> 
		<meta name="author" content="Thomas Diot" />
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/sticky.full.js"></script>
		<?php require_once('include/config.php'); ?>
		<title><?php echo $site; ?> - Demande de prêt</title>
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
						<span class="titre">Disponibilité : </span><?php echo '<img src="img/etat_'.$book->getId_etat().'.png" alt="'.Livre::getValeurEtat($bdd, $book->getId_etat()).'" title="'.Livre::getValeurEtat($bdd, $book->getId_etat()).'" />'; ?><br />
					</fieldset>
					<br /><br />
					
					<form method="post" action="livre_base.php" id="demande_pret">
				
					<fieldset>
					
						<!-- Ne pas toucher !! -->
						<input type="hidden" name="type" id="type" value="8" />
						<input type="hidden" name="id" id="id" value="<?php echo $book->getId(); ?>" />
					
						<legend><?php if($book->getId_etat() == 1) {echo 'Demande de prêt';} else {echo 'Etre averti';} ?></legend>

						<table>
							<tr>
								 <td>
									  <label for="email">Adresse e-mail</label>
								 </td>
								 <td>
									  <input type="email" name="email" id="email" class="text" />
								 </td>
							</tr>
						</table>
	
					</fieldset>

				   <div id="class"></div>

				   <input class="right" type="submit" name="<?php if($book->getId_etat() == 1) {echo 'pret';} else {echo 'avert';} ?>" value="Envoyer" />

				</form>
				
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