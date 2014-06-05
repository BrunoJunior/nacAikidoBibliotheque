		<div class="navigation" id="sub-nav">

			<ul class="tabbed">
			
				<?php
					$genres = $bdd->query('SELECT * FROM categories');
					$genres = $genres->fetchAll();
					// $genres = array(
						// null,
						// 'Scolaire',
						// 'Aventure',
						// 'Policier',
						// 'Historique',
						// 'Drame',
						// 'Jeunesse',
						// 'Religion',
						// 'Fantasy',
						// 'Romance',
						// 'Science fiction',
						// 'BD',
						// 'Autre'
					// );
					$i = 0;
					while($i < count($genres)){
						if($genres[$i]['titre'] != 'n/a'){
							echo '<li ';
							$id = $i+1;
							if(isset($_GET['genre']) AND $_GET['genre'] == $id){
								echo 'class="current-tab">';
							}
							else{
								echo '>';
							}
							echo '<a href="catalogue.php?genre='.$id.'">';
							echo $genres[$i]['titre'];
							echo '</a></li>';
						}
						$i++;
					}

				?>

			</ul>

			<div class="clearer">&nbsp;</div>

		</div>