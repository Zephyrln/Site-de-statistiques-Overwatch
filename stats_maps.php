<?php
$statsMaps = [];
$file = fopen("data.txt", "r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        $data = trim($ligne);
        if (empty($data)) continue;
        list($map, $issue, $persoZ, $persoM) = explode("|", $data);
        if (!isset($statsMaps[$map])) {
            $statsMaps[$map] = ['Victoires' => 0, 'Defaites' => 0];
        }
        if ($issue === "Victoire") {
            $statsMaps[$map]['Victoires'] += 1;
        } else {
            $statsMaps[$map]['Defaites'] += 1;
        }
    }
    fclose($file);
}

foreach ($statsMaps as $map => &$stats) {
    $stats['Total'] = $stats['Victoires'] + $stats['Defaites'];
    $stats['Winrate'] = $stats['Total'] > 0 ? round(($stats['Victoires'] / $stats['Total']) * 100, 1) : 0;
}
unset($stats);

$tri = isset($_GET['tri']) ? $_GET['tri'] : 'nom';

if ($tri === 'winrate') {
    $triParWinrate = function($a, $b) { return $b['Winrate'] <=> $a['Winrate']; };
    uasort($statsMaps, $triParWinrate);
} elseif ($tri === 'parties') {
    $triParParties = function($a, $b) { return $b['Total'] <=> $a['Total']; };
    uasort($statsMaps, $triParParties);
} else {
    ksort($statsMaps);
}

function buildUrlParamMap($cle, $valeur) {
    $params = $_GET;
    $params[$cle] = $valeur;
    return '?' . http_build_query($params);
}
?>

<section style="border-radius:20px; margin-top: 20px;" class="w3-card w3-content">
    <h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">Statistiques par Map</h1>
    
    <section class="w3-container" style="padding-bottom: 30px; margin-top: 20px;">
        
        <div class="w3-center w3-margin-bottom w3-padding w3-light-grey" style="border-radius: 10px;">
            <div style="margin-bottom: 10px;">
                <span style="font-family: 'Oswald'; font-style: italic; margin-right: 10px;">Trier par :</span>
                <a href="<?php echo buildUrlParamMap('tri', 'nom'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'nom' ? 'w3-theme' : 'w3-white'; ?>">Nom</a>
                <a href="<?php echo buildUrlParamMap('tri', 'winrate'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'winrate' ? 'w3-theme' : 'w3-white'; ?>">Winrate</a>
                <a href="<?php echo buildUrlParamMap('tri', 'parties'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'parties' ? 'w3-theme' : 'w3-white'; ?>">Parties</a>
            </div>
        </div>

        <div class="w3-row-padding">
            <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                    <tr class="w3-theme-d1">
                        <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Map</th>
                        <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                        <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                        <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                    </tr>
                    
                    <?php 
                    $aucunResultat = true;
                    foreach ($statsMaps as $map => $stats): 
                        $aucunResultat = false;
                        $colorClass = "w3-text-black";
                        if ($stats['Winrate'] >= 66.7) $colorClass = "w3-text-green";
                        elseif ($stats['Winrate'] > 33.3) $colorClass = "w3-text-orange";
                        elseif ($stats['Winrate'] <= 33.3) $colorClass = "w3-text-red";
                    ?>
                    <tr>
                        <td class="w3-center" style="vertical-align: middle;">
                            <a href="index.php?page=detailMap&map=<?php echo urlencode($map); ?>" style="text-decoration:none;">
                                <img src="mapsIm/<?php echo htmlspecialchars($map); ?>.png" 
                                     alt="<?php echo htmlspecialchars($map); ?>" 
                                     title="Voir les détails de <?php echo htmlspecialchars($map); ?> (Jouée <?php echo $stats['Total']; ?> fois)" 
                                     style="width: 200px; height: 100px; object-fit: cover; border-radius: 5px; transition: transform 0.2s;"
                                     onmouseover="this.style.transform='scale(1.1)';" 
                                     onmouseout="this.style.transform='scale(1)';"><br>
                                <span class="w3-hover-text-orange" style="font-weight:bold; font-family: 'Oswald'; font-style: italic;">
                                    <?php echo htmlspecialchars($map); ?>
                                </span>
                            </a>
                        </td>
                        <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Victoires']; ?></td>
                        <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Defaites']; ?></td>
                        <td class="w3-center <?php echo $colorClass; ?>" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><strong><?php echo $stats['Winrate']; ?> %</strong></td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if ($aucunResultat): ?>
                        <tr><td colspan="4" class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune map n'a été jouée pour le moment.</td></tr>
                    <?php endif; ?>
                </table>
            </section>
        </div>
    </section>
</section>