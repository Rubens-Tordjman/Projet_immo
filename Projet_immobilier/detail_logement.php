<?php 
$detail_logement = '';

// function debug
function debug ($variable) {
    echo '<pre>';
        print_r($variable);
    echo '</pre>';
}

$pdo = new PDO('mysql:host=localhost;dbname=immobilier', 'root', 'root', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

//debug($_GET);

if (isset($_GET['id_logement'])) {

    // echappement 
    $_GET['id_logement'] = htmlspecialchars($_GET['id_logement'], ENT_QUOTES);

    // requête
    $resultat = $pdo->prepare("SELECT * FROM logement WHERE id_logement = :id_logement");

    $resultat->execute(array(':id_logement' => $_GET['id_logement']));

    if ($resultat->rowCount() == 0) {
        $detail_logement .= '<div class="alert alert-warning">Le logement n\'existe pas.</div>';
    } else {
        $detail = $resultat->fetch(PDO::FETCH_ASSOC);
        // debug($detail);

        $detail_logement .= '<img src="'. $detail['photo'] .'" style="width:600px">';
        $detail_logement .= '<div class="my-3">Titre : '. $detail['titre'] .'</div>';
        $detail_logement .= '<div>Adresse : '. $detail['adresse'] . ', ' . $detail['ville'] . ' ' . $detail['cp'] .'</div>';
        $detail_logement .= '<div>Surface : '. $detail['surface'] .' m2</div>';
        $detail_logement .= '<div>Prix : '. $detail['prix'] .' €</div>';
        $detail_logement .= '<div>Type : '. $detail['type'] .'</div>';
        $detail_logement .= '<div>Description : '. $detail['description'] .'</div>';
        

    }

} // fin du if (isset($_GET['id_logement']))




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails - Logement</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>  
    <div class="container">
        <h1 class="my-5">Détails : </h1>
    
        <?php echo $detail_logement; ?>
    
    </div> 
</body>
</html>