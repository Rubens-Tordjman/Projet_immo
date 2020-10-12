<?php 
$message = '';

$pdo = new PDO('mysql:host=localhost;dbname=immobilier', 'root', 'root', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// fonction debug
function debug($var) {
    echo '<pre>';
        print_r($var);
    echo '</pre>';
}

// Contrôle du formulaire
//debug($_POST);

if (!empty($_POST)) {

    if (!isset($_POST['titre']) || strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 50 ) {
        $message .= '<div class="alert alert-danger">Le titre doit contenir entre 3 et 50 caractères.</div>';
    }

    if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 5 || strlen($_POST['adresse']) > 200 ) {
        $message .= '<div class="alert alert-danger">L\'adresse n\'est pas valide.</div>';
    }

    if (!isset($_POST['ville']) || strlen($_POST['ville']) < 3 || strlen($_POST['ville']) > 200 ) {
        $message .= '<div class="alert alert-danger">La ville n\'est pas valide.</div>';
    }

    if (!isset($_POST['cp']) || !preg_match('#^[0-9]{5}$#', $_POST['cp']) ) {
        $message .= '<div class="alert alert-danger">Le code postal est incorrecte.</div>';
    }

    if (!isset($_POST['surface']) || !ctype_digit($_POST['surface']) ) {
        $message .= '<div class="alert alert-danger">Le surface est invalide.</div>';
    }

    if (!isset($_POST['prix']) || !ctype_digit($_POST['prix']) ) {
        $message .= '<div class="alert alert-danger">Le prix est incorrecte.</div>';
    }

    if (!isset($_POST['type']) || ( $_POST['type'] != 'location' && $_POST['type'] != 'vente') ) {
        $message .= '<div class="alert alert-danger">Le type de logement saisi n\'est pas valide.</div>';
    }

    if (!isset($_POST['description']) || strlen($_POST['description']) < 5 || strlen($_POST['description']) > 255 ) {
        $message .= '<div class="alert alert-danger">La description doit contenir entre 5 et 255 caractères.</div>';
    }

// Ajout logement dans la BDD

    if (empty($message)) {
        
        $photo = '';

        //debug($_FILES);
        if (!empty($_FILES['photo']['name'])) {
            
            // $timestamp = strtotime('05/05/2020');

            $photo = 'photos/logement_'. $_FILES['photo']['name'];

            copy($_FILES['photo']['tmp_name'], $photo);

            // contrôle type de fichier :
            


        }

        // echappement des données:
        foreach ($_POST as $indice => $valeur) {
            $_POST[$indice] = htmlspecialchars($valeur, ENT_QUOTES);
        }

        // requête
        $resultat = $pdo->prepare("INSERT INTO logement (titre, adresse, ville, cp, surface, prix, photo, type, description) VALUES (:titre, :adresse, :ville, :cp, :surface, :prix, :photo, :type, :description) ");

        $succes = $resultat->execute(array(
            ':titre'        => $_POST['titre'],
            ':adresse'      => $_POST['adresse'],
            ':ville'        => $_POST['ville'],
            ':cp'           => $_POST['cp'],
            ':surface'      => $_POST['surface'],
            ':prix'         => $_POST['prix'],
            ':photo'        => $photo,
            ':type'         => $_POST['type'],
            ':description'  => $_POST['description']
        ));

        if ($succes) {
            $message .= '<div class="alert alert-success">Le logement a bien été ajouté.</div>';
        } else {
            $message .= '<div class="alert alert-danger">Erreur lors de l\'enregistrement...</div>';
        }

    } // if (empty($message))

} // fin du if (!empty($_POST))

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Immobilier - Formulaire</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

    <div class="container text-center">

        <h1>Formulaire - Logement</h1>

        <p class="text-right"><a href="tableau_logement.php" >derniers ajouts></a></p>

        <?php echo $message; ?>

        <form action="" method="post" enctype="multipart/form-data" style="border:1px solid black">

            <div class="mb-3">
                <div><label for="titre">Titre :</label></div>
                <div><input type="text" name="titre" id="titre" value="<?php echo $_POST['titre'] ?? ''; ?>"></div>
            </div>

            <div>
                <label for="adresse">Adresse :</label>
                <div><textarea name="adresse" id="adresse" placeholder="Veuillez saisir votre adresse"><?php echo $_POST['adresse'] ?? ''; ?></textarea></div>
            </div>
        
            <div class="mb-3">
                <div><label for="ville">Ville :</label></div>
                <div><input type="text" name="ville" id="ville" value="<?php echo $_POST['ville'] ?? ''; ?>"></div>
            </div>

            <div class="mb-3">
                <div><label for="cp">Code postal :</label></div>
                <div><input type="text" name="cp" id="cp" value="<?php echo $_POST['cp'] ?? ''; ?>"></div>
            </div>

            <div class="mb-3">
                <div><label for="surface">Surface (m2) :</label></div>
                <div><input type="text" name="surface" id="surface" value="<?php echo $_POST['surface'] ?? ''; ?>"></div>
            </div>

            <div class="mb-3">
                <div><label for="prix">Prix (€) :</label></div>
                <div><input type="text" name="prix" id="prix" value="<?php echo $_POST['prix'] ?? ''; ?>"></div>
            </div>

            <div class="mb-3">
                <div><label>Photo</label></div>
                <div><input type="file" name="photo"></div>
            </div>


            <div class="mb-3">
                <div><label>Type :</label></div>
                <select name="type">
                    <option value="location">location</option>
                    <option value="vente">vente</option>
                </select>
            </div>

            <div>
                <div><label>Description :</label></div>
                <textarea name="description" placeholder="Description..." cols="25" rows="5"><?php echo $_POST['description'] ?? ''; ?></textarea>
            </div>
        
            <input type="submit" value="envoyer" class="btn btn-primary my-3">
        
        </form>

    </div> <!-- .container -->

    
</body>
</html>