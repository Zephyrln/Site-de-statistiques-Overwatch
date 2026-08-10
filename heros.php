<?php
$rolesHeros = [
    
    'Ana' => 'Support',
    'Ange' => 'Support',
    'Anran' => 'Dégât',
    'Ashe' => 'Dégât',
    'Baptiste' => 'Support',
    'Bastion' => 'Dégât',
    'Bouldozer' => 'Tank',
    'Brigitte' => 'Support',
    'Cassidy' => 'Dégât',
    'Chacal' => 'Dégât',
    'Chopper' => 'Tank',
    'D.va' => 'Tank',
    'Danger' => 'Tank',
    'Domina' => 'Tank',
    'Doomfist' => 'Tank',
    'Emre' => 'Dégât',
    'Echo' => 'Dégât',
    'Fatale' => 'Dégât',
    'Faucheur' => 'Dégât',
    'Freja' => 'Dégât',
    'Genji' => 'Dégât',
    'Hanzo' => 'Dégât',
    'Illari' => 'Support',
    'Jetpack Cat' => 'Support',
    'Juno' => 'Support',
    'Kiriko' => 'Support',
    'Lucio' => 'Support',
    'Mauga' => 'Tank',
    'Mei' => 'Dégât',
    'Mizuki' => 'Support',
    'Moira' => 'Support',
    'Orisa' => 'Tank',
    'Pharah' => 'Dégât',
    'Ramattra' => 'Tank',
    'Reine des Junkers' => 'Tank',
    'Reinhardt' => 'Tank',
    'Shion' => 'Dégât',
    'Sierra' => 'Dégât',
    'Sigma' => 'Tank',
    'Sojourn' => 'Dégât',
    'Soldat 76' => 'Dégât',
    'Sombra' => 'Dégât',
    'Symmetra' => 'Dégât',
    'Torbjörn' => 'Dégât',
    'Tracer' => 'Dégât',
    'Vendetta' => 'Dégât',
    'Venture' => 'Dégât',
    'Vital' => 'Support',
    'Winston' => 'Tank',
    'Wuyang' => 'Support',
    'Zarya' => 'Tank',
    'Zenyatta' => 'Support'
];

$statsJ1 = [];
$statsJ2 = [];

$file = fopen("data.txt", "r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $data = trim($ligne);
        if (empty($data)) continue;

        list($map, $issue, $persoZ, $persoM) = explode("|", $data);

        if (!isset($statsJ1[$persoZ])) {
            $statsJ1[$persoZ] = ['Victoires' => 0, 'Defaites' => 0];
        }
        if ($issue === "Victoire") {
            $statsJ1[$persoZ]['Victoires'] += 1;
        } else {
            $statsJ1[$persoZ]['Defaites'] += 1;
        }

        if (!isset($statsJ2[$persoM])) {
            $statsJ2[$persoM] = ['Victoires' => 0, 'Defaites' => 0];
        }
        if ($issue === "Victoire") {
            $statsJ2[$persoM]['Victoires'] += 1;
        } else {
            $statsJ2[$persoM]['Defaites'] += 1;
        }
    }
    fclose($file);
}

foreach ($statsJ1 as $hero => &$stats) {
    $stats['Total'] = $stats['Victoires'] + $stats['Defaites'];
    $stats['Winrate'] = $stats['Total'] > 0 ? round(($stats['Victoires'] / $stats['Total']) * 100, 1) : 0;
}
unset($stats);

foreach ($statsJ2 as $hero => &$stats) {
    $stats['Total'] = $stats['Victoires'] + $stats['Defaites'];
    $stats['Winrate'] = $stats['Total'] > 0 ? round(($stats['Victoires'] / $stats['Total']) * 100, 1) : 0;
}
unset($stats);

$tri = isset($_GET['tri']) ? $_GET['tri'] : 'nom';
$filtreRole = isset($_GET['role']) ? $_GET['role'] : 'Tous';

if ($tri === 'winrate') {
    $triParWinrate = function($a, $b) { return $b['Winrate'] <=> $a['Winrate']; };
    uasort($statsJ1, $triParWinrate);
    uasort($statsJ2, $triParWinrate);
} elseif ($tri === 'parties') {
    $triParParties = function($a, $b) { return $b['Total'] <=> $a['Total']; };
    uasort($statsJ1, $triParParties);
    uasort($statsJ2, $triParParties);
} else {
    ksort($statsJ1);
    ksort($statsJ2);
}

function buildUrlParam($cle, $valeur) {
    $params = $_GET;
    $params[$cle] = $valeur;
    return '?' . http_build_query($params);
}
?>

<section style="border-radius:20px; margin-top: 20px;" class="w3-card w3-content">
    <h1 style="border-radius:20px 20px 0px 0px; font-family: 'Oswald'; font-style: italic;" class="w3-theme w3-container w3-center">Statistiques par Héros</h1>
    
    <section class="w3-container" style="padding-bottom: 30px; margin-top: 20px;">
        
        <div class="w3-center w3-margin-bottom w3-padding w3-light-grey" style="border-radius: 10px;">
            <div style="margin-bottom: 10px;">
                <span style="font-family: 'Oswald'; font-style: italic; margin-right: 10px;">Trier par :</span>
                <a href="<?php echo buildUrlParam('tri', 'nom'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'nom' ? 'w3-theme' : 'w3-white'; ?>">Nom</a>
                <a href="<?php echo buildUrlParam('tri', 'winrate'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'winrate' ? 'w3-theme' : 'w3-white'; ?>">Winrate</a>
                <a href="<?php echo buildUrlParam('tri', 'parties'); ?>" class="w3-button w3-round w3-small <?php echo $tri === 'parties' ? 'w3-theme' : 'w3-white'; ?>">Parties</a>
            </div>
            <div>
                <span style="font-family: 'Oswald'; font-style: italic; margin-right: 10px;">Filtrer par rôle :</span>
                <a href="<?php echo buildUrlParam('role', 'Tous'); ?>" class="w3-button w3-round w3-small <?php echo $filtreRole === 'Tous' ? 'w3-theme' : 'w3-white'; ?>">Tous</a>
                <a href="<?php echo buildUrlParam('role', 'Tank'); ?>" class="w3-button w3-round w3-small <?php echo $filtreRole === 'Tank' ? 'w3-theme' : 'w3-white'; ?>">Tanks</a>
                <a href="<?php echo buildUrlParam('role', 'Dégât'); ?>" class="w3-button w3-round w3-small <?php echo $filtreRole === 'Dégât' ? 'w3-theme' : 'w3-white'; ?>">Dégâts</a>
                <a href="<?php echo buildUrlParam('role', 'Support'); ?>" class="w3-button w3-round w3-small <?php echo $filtreRole === 'Support' ? 'w3-theme' : 'w3-white'; ?>">Supports</a>
            </div>
        </div>

        <div class="w3-row-padding">
            
            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros du Joueur 1</h3>
                    <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                        <tr class="w3-theme-d1">
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                        </tr>
                        <?php 
                        $aucunResultatJ1 = true;
                        foreach ($statsJ1 as $hero => $stats): 
                            $rolePerso = isset($rolesHeros[$hero]) ? $rolesHeros[$hero] : 'Inconnu';
                            if ($filtreRole !== 'Tous' && $rolePerso !== $filtreRole) {
                                continue;
                            }
                            $aucunResultatJ1 = false;
                            
                            $colorClass = "w3-text-black";
                            if ($stats['Winrate'] >= 66.7) $colorClass = "w3-text-green";
                            elseif ($stats['Winrate'] > 33.3) $colorClass = "w3-text-orange";
                            elseif ($stats['Winrate'] <= 33.3) $colorClass = "w3-text-red";
                        ?>
                        <tr>
                            <td class="w3-center" style="vertical-align: middle;">
                                <a href="index.php?page=fichePerso&hero=<?php echo urlencode($hero); ?>">
                                    <img src="herosIm/<?php echo htmlspecialchars($hero); ?>.png" 
                                         alt="<?php echo htmlspecialchars($hero); ?>" 
                                         title="Voir la fiche de <?php echo htmlspecialchars($hero); ?> (Joué <?php echo $stats['Total']; ?> fois)" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; transition: transform 0.2s;"
                                         onmouseover="this.style.transform='scale(1.1)';" 
                                         onmouseout="this.style.transform='scale(1)';">
                                </a>
                            </td>
                            <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Victoires']; ?></td>
                            <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Defaites']; ?></td>
                            <td class="w3-center <?php echo $colorClass; ?>" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><strong><?php echo $stats['Winrate']; ?> %</strong></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if ($aucunResultatJ1): ?>
                            <tr><td colspan="4" class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucun héros pour ce rôle.</td></tr>
                        <?php endif; ?>
                    </table>
                </section>
            </div>

            <div class="w3-half">
                <section style="border-radius: 10px;" class="w3-card w3-padding w3-white">
                    <h3 class="w3-theme-text w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros du Joueur 2</h3>
                    <table class="w3-table w3-striped w3-bordered w3-hoverable" style="margin-top: 15px;">
                        <tr class="w3-theme-d1">
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Héros</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">V</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">D</th>
                            <th class="w3-center" style="font-family: 'Oswald'; font-style: italic;">Winrate</th>
                        </tr>
                        <?php 
                        $aucunResultatJ2 = true;
                        foreach ($statsJ2 as $hero => $stats): 
                            $rolePerso = isset($rolesHeros[$hero]) ? $rolesHeros[$hero] : 'Inconnu';
                            if ($filtreRole !== 'Tous' && $rolePerso !== $filtreRole) {
                                continue; 
                            }
                            $aucunResultatJ2 = false;

                            $colorClass = "w3-text-black";
                            if ($stats['Winrate'] >= 66.7) $colorClass = "w3-text-green";
                            elseif ($stats['Winrate'] > 33.3) $colorClass = "w3-text-orange";
                            elseif ($stats['Winrate'] <= 33.3) $colorClass = "w3-text-red";
                        ?>
                        <tr>
                            <td class="w3-center" style="vertical-align: middle;">
                                <a href="index.php?page=fichePerso&hero=<?php echo urlencode($hero); ?>">
                                    <img src="herosIm/<?php echo htmlspecialchars($hero); ?>.png" 
                                         alt="<?php echo htmlspecialchars($hero); ?>" 
                                         title="Voir la fiche de <?php echo htmlspecialchars($hero); ?> (Joué <?php echo $stats['Total']; ?> fois)" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; transition: transform 0.2s;"
                                         onmouseover="this.style.transform='scale(1.1)';" 
                                         onmouseout="this.style.transform='scale(1)';">
                                </a>
                            </td>
                            <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Victoires']; ?></td>
                            <td class="w3-center" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><?php echo $stats['Defaites']; ?></td>
                            <td class="w3-center <?php echo $colorClass; ?>" style="vertical-align: middle; font-family: 'Oswald'; font-style: italic;"><strong><?php echo $stats['Winrate']; ?> %</strong></td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if ($aucunResultatJ2): ?>
                            <tr><td colspan="4" class="w3-center w3-text-grey" style="font-family: 'Oswald'; font-style: italic;">Aucun héros pour ce rôle.</td></tr>
                        <?php endif; ?>
                    </table>
                </section>
            </div>

        </div>
    </section>
</section>