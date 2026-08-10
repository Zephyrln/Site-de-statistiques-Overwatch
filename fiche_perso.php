<?php
if (!isset($_GET['hero']) || empty($_GET['hero'])) {
    echo "<div class='w3-panel w3-red w3-round'><h3 style=\"font-family: 'Oswald'; font-style: italic;\">Erreur</h3><p>Aucun héros n'a été sélectionné.</p></div>";
    exit;
}

$selectedHero = urldecode($_GET['hero']);

$victoiresJ1 = 0;
$defaitesJ1 = 0;
$statsMapsJ1 = [];

$victoiresJ2 = 0;
$defaitesJ2 = 0;
$statsMapsJ2 = [];

$file = fopen("data.txt", "r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $data = trim($ligne);
        if (empty($data)) continue;

        list($map, $Issue, $persoZ, $persoM) = explode("|", $data);

        if ($persoZ === $selectedHero) {
            if (!isset($statsMapsJ1[$map])) {
                $statsMapsJ1[$map] = ['Victoires' => 0, 'Defaites' => 0];
            }
            if ($Issue === "Victoire") {
                $victoiresJ1 += 1;
                $statsMapsJ1[$map]['Victoires'] += 1;
            } else {
                $defaitesJ1 += 1;
                $statsMapsJ1[$map]['Defaites'] += 1;
            }
        }

        if ($persoM === $selectedHero) {
            if (!isset($statsMapsJ2[$map])) {
                $statsMapsJ2[$map] = ['Victoires' => 0, 'Defaites' => 0];
            }
            if ($Issue === "Victoire") {
                $victoiresJ2 += 1;
                $statsMapsJ2[$map]['Victoires'] += 1;
            } else {
                $defaitesJ2 += 1;
                $statsMapsJ2[$map]['Defaites'] += 1;
            }
        }
    }
    fclose($file);
}

ksort($statsMapsJ1);
ksort($statsMapsJ2);

$totalJ1 = $victoiresJ1 + $defaitesJ1;
$winrateJ1 = ($totalJ1 > 0) ? round(($victoiresJ1 / $totalJ1) * 100, 1) : 0;

$totalJ2 = $victoiresJ2 + $defaitesJ2;
$winrateJ2 = ($totalJ2 > 0) ? round(($victoiresJ2 / $totalJ2) * 100, 1) : 0;
?>

<section style="border-radius:20px; margin-top: 20px;" class="w3-card w3-content">
    <h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">
        Fiche du héros : <?php echo htmlspecialchars($selectedHero); ?>
    </h1>
    
    <section class="w3-container w3-center" style="padding: 20px; padding-bottom: 30px;">
        <img src="herosIm/<?php echo htmlspecialchars($selectedHero); ?>.png" alt="<?php echo htmlspecialchars($selectedHero); ?>" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;" class="w3-card">
        
        <div class="w3-row-padding" style="margin-top: 30px; text-align: left;">
            
            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">J1</h3>
                    
                    <div class="w3-center w3-margin-bottom">
                        <span style="font-family: 'Oswald'; font-style: italic; font-size: 1.1em;">
                            Winrate Global : <strong><?php echo $totalJ1 > 0 ? $winrateJ1 . " %" : "N/A"; ?></strong>
                        </span><br>
                        <span style="font-family: 'Oswald'; font-style: italic; color: gray; font-size: 0.9em;">
                            (<?php echo $victoiresJ1; ?> V / <?php echo $defaitesJ1; ?> D)
                        </span>
                    </div>

                    <?php if (empty($statsMapsJ1)): ?>
                        <p class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune donnée</p>
                    <?php else: ?>
                        <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                            <tr class="w3-theme-d1">
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Map</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                            </tr>
                            <?php foreach ($statsMapsJ1 as $mapName => $stats): 
                                $total = $stats['Victoires'] + $stats['Defaites'];
                                $winrate = round(($stats['Victoires'] / $total) * 100, 1);
                                
                                $colorClass = "w3-text-black";
                                if ($winrate >= 66.7) {
                                    $colorClass = "w3-text-green";
                                } elseif ($winrate > 33.3) {
                                    $colorClass = "w3-text-orange";
                                } elseif ($winrate <= 33.3) {
                                    $colorClass = "w3-text-red";
                                }
                            ?>
                            <tr>
                                <td class="w3-center" style="vertical-align: middle;">
                                    <a href="index.php?page=detailMap&map=<?php echo urlencode($mapName); ?>" style="text-decoration: none; font-weight: bold;" class="w3-hover-text-orange">
                                        <?php echo htmlspecialchars($mapName); ?>
                                    </a>
                                </td>
                                <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Victoires']; ?></td>
                                <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Defaites']; ?></td>
                                <td class="w3-center <?php echo $colorClass; ?>" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><strong><?php echo $winrate; ?> %</strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Tableau de J2 -->
            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">J2</h3>
                    
                    <div class="w3-center w3-margin-bottom">
                        <span style="font-family: 'Oswald'; font-style: italic; font-size: 1.1em;">
                            Winrate Global : <strong><?php echo $totalJ2 > 0 ? $winrateJ2 . " %" : "N/A"; ?></strong>
                        </span><br>
                        <span style="font-family: 'Oswald'; font-style: italic; color: gray; font-size: 0.9em;">
                            (<?php echo $victoiresJ2; ?> V / <?php echo $defaitesJ2; ?> D)
                        </span>
                    </div>

                    <?php if (empty($statsMapsJ2)): ?>
                        <p class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune donnée</p>
                    <?php else: ?>
                        <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                            <tr class="w3-theme-d1">
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Map</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                            </tr>
                            <?php foreach ($statsMapsJ2 as $mapName => $stats): 
                                $total = $stats['Victoires'] + $stats['Defaites'];
                                $winrate = round(($stats['Victoires'] / $total) * 100, 1);
                                
                                $colorClass = "w3-text-black";
                                if ($winrate >= 66.7) {
                                    $colorClass = "w3-text-green";
                                } elseif ($winrate > 33.3) {
                                    $colorClass = "w3-text-orange";
                                } elseif ($winrate <= 33.3) {
                                    $colorClass = "w3-text-red";
                                }
                            ?>
                            <tr>
                                <td class="w3-center" style="vertical-align: middle;">
                                    <a href="index.php?page=presentation&map=<?php echo urlencode($mapName); ?>" style="text-decoration: none; font-weight: bold;" class="w3-hover-text-orange">
                                        <?php echo htmlspecialchars($mapName); ?>
                                    </a>
                                </td>
                                <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Victoires']; ?></td>
                                <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Defaites']; ?></td>
                                <td class="w3-center <?php echo $colorClass; ?>" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><strong><?php echo $winrate; ?> %</strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </section>
            </div>

        </div>

        <div style="margin-top: 40px;">
            <a href="javascript:history.back()" style="border-radius: 30px; font-family: 'Oswald'; font-style: italic;" class="w3-button w3-theme">Retour</a>
        </div>
    </section>
</section>