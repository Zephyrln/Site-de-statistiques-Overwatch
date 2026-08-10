<?php
if (!isset($_GET['map']) || empty($_GET['map'])) {
    echo "<div class='w3-panel w3-red w3-round'><h3 style=\"font-family: 'Oswald'; font-style: italic;\">Erreur</h3><p>Aucune map n'a été sélectionnée.</p></div>";
    exit;
}

$selectedMap = urldecode($_GET['map']);
$victoiresMap = 0;
$defaitesMap = 0;

$statsJ1 = [];
$statsJ2 = [];
$historiqueMap = [];

$file = fopen("data.txt", "r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $data = trim($ligne);
        if (empty($data)) continue;

        list($map, $Issue, $persoZ, $persoM) = explode("|", $data);

        if ($map === $selectedMap) {
            $historiqueMap[] = [
                'issue' => $Issue,
                'persoZ' => $persoZ,
                'persoM' => $persoM
            ];

            if (!isset($statsJ1[$persoZ])) {
                $statsJ1[$persoZ] = ['Victoires' => 0, 'Defaites' => 0];
            }
            if (!isset($statsJ2[$persoM])) {
                $statsJ2[$persoM] = ['Victoires' => 0, 'Defaites' => 0];
            }

            if ($Issue == "Victoire") {
                $victoiresMap += 1;
                $statsJ1[$persoZ]['Victoires'] += 1;
                $statsJ2[$persoM]['Victoires'] += 1;
            } else {
                $defaitesMap += 1;
                $statsJ1[$persoZ]['Defaites'] += 1;
                $statsJ2[$persoM]['Defaites'] += 1;
            }
        }
    }
    fclose($file);
}

ksort($statsJ1);
ksort($statsJ2);
$historiqueMap = array_reverse($historiqueMap);

$totalMap = $victoiresMap + $defaitesMap;
$winrateMap = ($totalMap > 0) ? round(($victoiresMap / $totalMap) * 100, 1) : 0;

$globalColorClass = "w3-text-black";
if ($totalMap > 0) {
    if ($winrateMap >= 66.7) {
        $globalColorClass = "w3-text-green";
    } elseif ($winrateMap > 33.3) {
        $globalColorClass = "w3-text-orange";
    } else {
        $globalColorClass = "w3-text-red";
    }
}
?>
<section style="border-radius:20px; margin-top: 20px;" class="w3-card w3-content">
    <h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">
        Détails de la map : <?php echo htmlspecialchars($selectedMap); ?>
    </h1>
    
    <section class="w3-container w3-center" style="padding: 20px; padding-bottom: 30px;">
        <img src="mapsIm/<?php echo htmlspecialchars($selectedMap); ?>.png" style="width: 100%; max-width: 400px; border-radius: 10px;">
        
        <h3 style="font-family: 'Oswald'; font-style: italic; margin-top: 20px;">
            Winrate Global : <strong class="<?php echo $globalColorClass; ?>"><?php echo $totalMap > 0 ? $winrateMap . " %" : "Aucune partie"; ?></strong>
        </h3>        <p style="font-family: 'Oswald'; font-style: italic; color: gray;">
            (<?php echo $victoiresMap; ?> Victoires / <?php echo $defaitesMap; ?> Défaites)
        </p>

        <div style="margin-top: 15px; margin-bottom: 25px;">
            <a href="index.php?page=enterData&map=<?php echo urlencode($selectedMap); ?>" 
               style="border-radius: 30px; font-family: 'Oswald'; font-style: italic;" 
               class="w3-button w3-theme-d1 w3-hover-orange">
                <i class="fa fa-plus"></i> Ajouter une partie sur cette map
            </a>
        </div>

        <div class="w3-row-padding" style="margin-top: 30px; text-align: left;">
            
            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros de J1</h3>
                    
                    <?php if (empty($statsJ1)): ?>
                        <p class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune donnée sur cette map.</p>
                    <?php else: ?>
                        <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                            <tr class="w3-theme-d1">
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                            </tr>
                            <?php foreach ($statsJ1 as $hero => $stats): 
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
                                    <a href="index.php?page=fichePerso&hero=<?php echo urlencode($hero); ?>">
                                        <img src="herosIm/<?php echo htmlspecialchars($hero); ?>.png" 
                                             alt="<?php echo htmlspecialchars($hero); ?>" 
                                             title="Voir la fiche de <?php echo htmlspecialchars($hero); ?>"
                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; transition: transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.1)';" 
                                             onmouseout="this.style.transform='scale(1)';" >
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

            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros de J2</h3>
                    
                    <?php if (empty($statsJ2)): ?>
                        <p class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune donnée sur cette map.</p>
                    <?php else: ?>
                        <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                            <tr class="w3-theme-d1">
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                            </tr>
                            <?php foreach ($statsJ2 as $hero => $stats): 
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
                                    <a href="index.php?page=fichePerso&hero=<?php echo urlencode($hero); ?>">
                                        <img src="herosIm/<?php echo htmlspecialchars($hero); ?>.png" 
                                             alt="<?php echo htmlspecialchars($hero); ?>" 
                                             title="Voir la fiche de <?php echo htmlspecialchars($hero); ?>"
                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; transition: transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.1)';" 
                                             onmouseout="this.style.transform='scale(1)';" >
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

        <!-- NOUVELLE SECTION : Historique de la map -->
        <div style="margin-top: 40px;">
            <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">Historique sur cette map</h3>
                
                <?php if (empty($historiqueMap)): ?>
                    <p class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucune partie enregistrée.</p>
                <?php else: ?>
                    <div class="w3-responsive" style="margin-top: 15px;">
                        <table class="w3-table w3-striped w3-bordered w3-hoverable">
                            <tr class="w3-theme-d3">
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Résultat</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros J1</th>
                                <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros J2</th>
                            </tr>
                            
                            <?php 
                            foreach ($historiqueMap as $partie) {
                                $badgeClass = ($partie['issue'] === "Victoire") ? "w3-green" : "w3-red";
                            ?>
                            <tr>
                                <td class="w3-center" style="vertical-align: middle;">
                                    <span class="w3-tag <?php echo $badgeClass; ?> w3-round" style="padding: 4px 10px; font-family: 'Oswald'; font-style: italic;">
                                        <?php echo htmlspecialchars($partie['issue']); ?>
                                    </span>
                                </td>
                                <td class="w3-center" style="vertical-align: middle;">
                                    <a href="index.php?page=fichePerso&hero=<?php echo urlencode($partie['persoZ']); ?>">
                                        <img src="herosIm/<?php echo htmlspecialchars($partie['persoZ']); ?>.png" 
                                             alt="<?php echo htmlspecialchars($partie['persoZ']); ?>" 
                                             title="Voir la fiche de <?php echo htmlspecialchars($partie['persoZ']); ?>" 
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; transition: transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.1)';" 
                                             onmouseout="this.style.transform='scale(1)';">
                                    </a>
                                </td>
                                <td class="w3-center" style="vertical-align: middle;">
                                    <a href="index.php?page=fichePerso&hero=<?php echo urlencode($partie['persoM']); ?>">
                                        <img src="herosIm/<?php echo htmlspecialchars($partie['persoM']); ?>.png" 
                                             alt="<?php echo htmlspecialchars($partie['persoM']); ?>" 
                                             title="Voir la fiche de <?php echo htmlspecialchars($partie['persoM']); ?>" 
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; transition: transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.1)';" 
                                             onmouseout="this.style.transform='scale(1)';">
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <div style="margin-top: 40px;">
            <a href="index.php?page=allMaps" style="border-radius: 30px; font-family: 'Oswald'; font-style: italic;" class="w3-button w3-theme">Retour aux maps</a>
        </div>
    </section>
</section>