<?php
$parties = [];
$file = fopen("data.txt", "r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $data = trim($ligne);
        if (!empty($data)) {
            $parties[] = $data;
        }
    }
    fclose($file);
}

if (count($parties) === 0) {
    echo "<section style='border-radius:20px; margin-top:20px;' class='w3-card w3-content w3-theme-l4 w3-center w3-padding'>
            <h3 style=\"font-family: 'Oswald'; font-style: italic;\">Historique vide</h3>
            <p style=\"font-family: 'Oswald'; font-style: italic;\">Vous n'avez pas encore entré de données de partie.</p>
          </section>";
} else {
    $parties = array_reverse($parties);
?>

<section style="border-radius:20px; margin-top: 20px;" class="w3-card w3-content">
    <h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">Historique des parties</h1>
    
    <section class="w3-container" style="padding-bottom: 20px;">
        <div class="w3-responsive" style="margin-top: 20px;">
            <table class="w3-table w3-striped w3-bordered w3-hoverable">
                <tr class="w3-theme-d3">
                    <th style="font-family: 'Oswald'; font-style: italic;">Map</th>
                    <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Résultat</th>
                    <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Joueur 1</th>
                    <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Joueur 2</th>
                </tr>
                
                <?php 
                foreach ($parties as $ligne) {
                    list($map, $issue, $persoZ, $persoM) = explode("|", $ligne);
                    $badgeClass = ($issue === "Victoire") ? "w3-green" : "w3-red";
                ?>
                <tr>
                    <td style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;">
                        <a href="index.php?page=detailMap&map=<?php echo urlencode($map); ?>" class="w3-hover-text-orange" style="text-decoration:none; font-weight:bold;">
                            <?php echo htmlspecialchars($map); ?>
                        </a>
                    </td>
                    <td class="w3-center" style="vertical-align: middle;">
                        <span class="w3-tag <?php echo $badgeClass; ?> w3-round" style="padding: 4px 10px; font-family: 'Oswald'; font-style: italic;">
                            <?php echo htmlspecialchars($issue); ?>
                        </span>
                    </td>
                    <td class="w3-center" style="vertical-align: middle;">
                        <a href="index.php?page=fichePerso&hero=<?php echo urlencode($persoZ); ?>">
                            <img src="herosIm/<?php echo htmlspecialchars($persoZ); ?>.png" 
                                 alt="<?php echo htmlspecialchars($persoZ); ?>" 
                                 title="Voir la fiche de <?php echo htmlspecialchars($persoZ); ?>" 
                                 style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; transition: transform 0.2s;"
                                 onmouseover="this.style.transform='scale(1.1)';" 
                                 onmouseout="this.style.transform='scale(1)';">
                        </a>
                    </td>
                    <td class="w3-center" style="vertical-align: middle;">
                        <a href="index.php?page=fichePerso&hero=<?php echo urlencode($persoM); ?>">
                            <img src="herosIm/<?php echo htmlspecialchars($persoM); ?>.png" 
                                 alt="<?php echo htmlspecialchars($persoM); ?>" 
                                 title="Voir la fiche de <?php echo htmlspecialchars($persoM); ?>" 
                                 style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; transition: transform 0.2s;"
                                 onmouseover="this.style.transform='scale(1.1)';" 
                                 onmouseout="this.style.transform='scale(1)';">
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </section>
</section>
<?php } ?>