<?php
require_once 'requetes.php';
// Le visiteur a soumis le formulaire ?
if (isset($_POST['vehicule']) && $_POST['vehicule'] == 'Valider') {
    if ((isset($_POST['marque']) && !empty($_POST['marque'])) 
            && (isset($_POST['modele']) && !empty($_POST['modele'])) 
            && (isset($_POST['couleur']) && !empty($_POST['couleur'])) 
            && (isset($_POST['annee']) && !empty($_POST['annee']))
    ) {
        insertion_vehicule($_POST['marque'],$_POST['modele'],$_POST['couleur'],$_POST['annee']);
    } else {
        $erreur = 'Au moins un des champs est vide.';
    }
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/dashboard.css" rel="stylesheet">
        <title>Vehicule</title>
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
                        <li class="active"><a href="membre.php">Modifier le vehicule<span class="sr-only">(current)</span></a></li>
                        <li><a href="ajout_trajet.php">Proposer un trajet</a></li>
                        <li><a href="selection_trajet.php">Rechercher un trajet</a></li>
                        <li><a href="preparation_trajet.php">Voir vos trajets</a></li>
                        <li><a href="messagerie.php">Messagerie</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Vehicule</h1>
                    <form action="vehicule.php" method="post" enctype="multipart/form-data">
                        <div class="row placeholders">
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Marque</h4>
                                <?php input_voiture("marque"); ?>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Modèle</h4>
                                <?php input_voiture("modele"); ?>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Couleur</h4>
                                <?php input_voiture("couleur"); ?>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Année de mise en service</h4>
                                <span class="text-muted"><?php input_voiture("annee"); ?></span>
                            </div>
                        </div>
                        <button class="btn btn-lg btn-primary center-block" type="submit" name="vehicule" value="Valider">Valider</button>
                    </form>
                    <?php
                    if (isset($erreur))
                        echo '<br />', $erreur;
                    ?>
                </div>
            </div>
    </body>
</html>

