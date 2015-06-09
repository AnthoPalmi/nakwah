<?php
require_once 'requetes.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
$login = ($_SESSION['login']);

$valide = false;

if (isset($_POST['Noter']) && !empty($_POST['Noter'])
    && (isset($_POST['note']) && !empty($_POST['note']))) {
    noter_membre($login,$_POST['Noter'],$_POST['note'],$_POST['id_trajet']);
}

if (isset($_POST['valider_trajet']) && !empty($_POST['valider_trajet'])) {
    payer($login,$_POST['valider_trajet']);
}

?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/dashboard.css" rel="stylesheet">
        <title>Voir vos trajets</title>
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="membre.php">Profil</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li><a href="membre.php">Profil</a></li>
                        <li><a href="vehicule.php">Modifier le vehicule</a></li>
                        <li><a href="ajout_trajet.php">Proposer un trajet</a></li>
                        <li><a href="selection_trajet.php">Rechercher un trajet</a></li>
                        <li class="active"><a href="preparation_trajet.php">Voir vos trajets<span class="sr-only">(current)</span></a></li>
                        <li><a href="messagerie.php">Messagerie</a></li>
                    </ul>
                </div>
                <div class="col-md-offset-2 main">
                    <h1 class="page-header">Voir vos trajets</h1>
                        <div class="row placeholders">
                            <?php afficher_trajet_du_conducteur($login);?>    
                        </div>
                        <div class="row placeholders">
                            <?php afficher_trajet_passager($login);?> 
                </div>
            </div>
    </body>
</html>


