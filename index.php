<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-orange.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    </head>

    <style>
        nav a {
            margin-top: 10px;
            width: 100%;
            font-family: 'Oswald';
            font-style: italic;
            font-size: 20px;
        }

        p, label, option, input, h3, th, td{
            font-family: 'Oswald';
            font-style: italic;
            font-size: 20px;
        }
    </style>

    <body>
        <header class="w3-top w3-theme-d3 w3-container">
            <img src='Overwatch.png' style='width: 74px; float: left'>
            <h1 style="margin-left: 100px;font-family: 'Oswald';font-style: italic">Overwatch Map Stats Tracker</h1>
		</header>
        <section style="padding-top: 74px">
            <nav class="w3-theme-l4 w3-sidebar w3-center">
                <a href="index.php?page=allMaps" class="w3-button w3-theme">Toutes les maps</a>
                <a href="index.php?page=historique" class="w3-button w3-theme">Historique de parties</a>
                <a href="index.php?page=heros" class="w3-button w3-theme">Stats par héros</a>
                <a href="index.php?page=statsMaps" class="w3-button w3-theme">Stats par map</a>
                <a href="index.php?page=enterData" class="w3-button w3-theme">Entrer des données</a>
            </nav>
        </section>
        <div style="margin-left: 200px; margin-top: 20px" class="w3-center">
            <?php
                $pageOK= array('detailMap' => 'detailMap.php',
                                'enterData' => 'enterData.php',
                                'allMaps' => 'allMaps.php',
                                'historique' => 'historique.php',
                                'fichePerso' => 'fiche_perso.php',
                                'heros' => 'heros.php',
                                'statsMaps' => 'stats_maps.php');
                if ((isset($_GET['page'])) && (isset($pageOK[$_GET['page']])) ) {
		            include($pageOK[$_GET['page']]);
	            }
                else{
                    include($pageOK['allMaps']);
                }

            ?>
        </div>
    </body>
</html>
