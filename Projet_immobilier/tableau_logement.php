<?php 
$contenu = '';

// fonction debug
function debug($var) {
    echo '<pre>';
        print_r($var);
    echo '</pre>';
}

$pdo = new PDO('mysql:host=localhost;dbname=immobilier', 'root', 'root', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$resultat = $pdo->prepare("SELECT * FROM logement");

$resultat->execute();

$contenu .= '<div class="table-responsive">';
$contenu .= '<table class="table">';
    $contenu .= '<tr>';
        $contenu .= '<th>id</th>';
        $contenu .= '<th>titre</th>';
        $contenu .= '<th>adresse</th>';
        $contenu .= '<th>ville</th>';
        $contenu .= '<th>code postal</th>';
        $contenu .= '<th>surface (m2)</th>';
        $contenu .= '<th>prix (€)</th>';
        $contenu .= '<th>type</th>';
        $contenu .= '<th>photo</th>';
        $contenu .= '<th>description</th>';
    $contenu .= '</tr>';


while ($logement = $resultat->fetch(PDO::FETCH_ASSOC)) {
    // debug($logement);
    $contenu .= '<tr>';
        foreach ($logement as $indice => $infos) {
            if ($indice == 'photo') {

                $contenu .= '<td><img src="'. $infos .'" style="width:170px"></td>';

            } elseif ($indice == 'description') {

                    $contenu .= '<td>'. substr($infos, 0, 10) . '...' .'</td>';

            } else {
                $contenu .= '<td>'. $infos .'</td>';
            }

        } // fin foreach

        $contenu .= '<td><a href="detail_logement.php?id_logement='. $logement['id_logement'] .'">voir</a></td>';
    
    $contenu .= '</tr>';
} // fin while


$contenu .= '</table';
$contenu .= '</div>';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tableau - Logements</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container text-center">
        <h1 class="my-4">Logements</h1>
        
        <?php echo $contenu; ?>
    </div>
    
</body>
</html>