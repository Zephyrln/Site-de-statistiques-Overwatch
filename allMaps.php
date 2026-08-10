<?php
$stats = [];

$file = fopen("data.txt","r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $data = trim($ligne);
        if (empty($data)) continue;
        
        list($map, $Issue, $persoZ, $persoM) = explode("|",$data);
        
        if (!isset($stats[$map])) {
            $stats[$map] = ['Victoires' => 0, 'Defaites' => 0];
        }
        
        if($Issue == "Victoire"){
            $stats[$map]['Victoires'] += 1;
        } else {
            $stats[$map]['Defaites'] += 1;
        }
    }
    fclose($file);
}
?>

<?php
$file = fopen("MapsNoms.txt","r");
if ($file) {
    while(!feof($file)){
        $ligne = fgets($file);
        if ($ligne === false) break;
        
        $nomMap = trim($ligne);
        if (empty($nomMap)) continue;

        echo "
            <section style='width: 350px;height: 320px ;margin:30px; float: left' class='w3-card w3-content w3-container'>
                <a href='index.php?page=detailMap&map=" . urlencode($nomMap) . "'><img src='mapsIm/$nomMap.png' style='width: 300px; padding-top:20px'></a>
                <p><strong>$nomMap</strong></p>";
                
                $victoires = isset($stats[$nomMap]['Victoires']) ? $stats[$nomMap]['Victoires'] : 0;
                $defaites = isset($stats[$nomMap]['Defaites']) ? $stats[$nomMap]['Defaites'] : 0;
                $totalParties = $victoires + $defaites;
                
                if ($totalParties > 0) {
                    $winrate = ($victoires / $totalParties) * 100;
                    
                    $couleurClass = '';
                    if ($winrate >= 66.6) {
                        $couleurClass = 'w3-text-green';
                    } elseif ($winrate > 33.4) {
                        $couleurClass = 'w3-text-orange';
                    } elseif ($winrate <= 33.4) {
                        $couleurClass = 'w3-text-red';
                    }

                    echo "<p>Winrate : <strong class='$couleurClass'>" . round($winrate, 1) . " %</strong> <br> 
                          <span style='font-size: 0.8em; color: gray;'>($victoires V / $defaites D)</span></p>";
                } else {
                    echo "<p>Winrate : Aucune partie</p>";
                }
                
        echo "</section>";
    }
    fclose($file);
}
?>