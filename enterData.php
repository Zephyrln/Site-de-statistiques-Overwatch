<section style="border-radius:20px" class="w3-card w3-content">
	<form method="post">
		<h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">Entrez les données d'une partie.</h1>
		<section class="w3-container">

			<label class="w3-text-theme">Nom de la map</label><br>
			<select required name="Map" style="border-radius:5px" class="w3-select">
				<?php 
					$mapPreselectionnee = isset($_GET['map']) ? urldecode($_GET['map']) : '';

					$file = fopen("MapsNoms.txt","r");
					while(!feof($file)){
						$nommap = trim(fgets($file));
						if (empty($nommap)) continue;
						if ($nommap === $mapPreselectionnee) {
							echo "<option value='$nommap' selected>$nommap</option>";
						} else {
							echo "<option value='$nommap'>$nommap</option>";
						}
					}
					fclose($file);
				?>
			</select>

			
			<label class="w3-text-theme">Issue de la partie</label><br>
			<input type="radio" id="choix_victoire" name="Issue" value="Victoire" required>
            <label style="cursor: pointer;" for="choix_victoire"><span>Victoire</span></label><br>
            
            <input type="radio" id="choix_defaite" name="Issue" value="Défaite" required>
            <label style="cursor: pointer;" for="choix_defaite"><span>Défaite</span></label><br>

			<label class="w3-text-theme">Personnage du Joueur 1</label>
			<select name="PersoZ" style="border-radius:5px" class="w3-select">
				<?php 
					$file = fopen("HerosNoms.txt","r");
					while(!feof($file)){
						$nompersoZ = trim(fgets($file));
						if($nompersoZ == "Domina"){
							echo "<option value='$nompersoZ' selected>$nompersoZ</option>";
						}
						else{
							echo "<option value='$nompersoZ'>$nompersoZ</option>";
						}
					}
					fclose($file);
				?>
			</select>

			<label class="w3-text-theme">Personnage du Joueur 2</label>
			<select name="persoM" style="border-radius:5px" class="w3-select">
				<?php 
					$file = fopen("HerosNoms.txt","r");
					while(!feof($file)){
						$nompersoM = trim(fgets($file));
						if($nompersoM == "Vital"){
							echo "<option value='$nompersoM' selected>$nompersoM</option>";
						}
						else{
							echo "<option value='$nompersoM'>$nompersoM</option>";
						}
					}
					fclose($file);
				?>
			</select>


			<input style="margin-bottom:20px; margin-top: 20px; border-radius: 30px 0px 0px 30px" class="w3-button w3-theme" type="submit" value="Soumettre">
			<input style="margin-bottom:20px; margin-top: 20px; border-radius: 0px 30px 30px 0px" class="w3-button w3-theme" type="reset" value="Recommencer">
		</section>
	</form>
	<?php
		if (!empty($_POST["Issue"])) {
			$Map = $_POST["Map"];
			$Issue = $_POST["Issue"];
			$PersoZ = $_POST["PersoZ"];
			$persoM = $_POST["persoM"];
			$file = fopen("data.txt", "a");
			$savestring = "\n".$Map."|".$Issue."|".$PersoZ."|".$persoM;
			fwrite($file, $savestring);
			fclose($file);
			echo "<script>alert('Les données de la partie ont bien été ajoutés')</script>";
			echo '<META http-equiv="refresh" content="0.5; URL=index.php?page=historique">';
		}
	?>
</section>
