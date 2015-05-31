<?php
require_once 'requetes.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
$login = ($_SESSION['login']);

$valide = false;


?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/dashboard.css" rel="stylesheet">
        <title>Rechercher un trajet</title>
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
                        <li class="active"><a href="selection_trajet.php">Rechercher un trajet<span class="sr-only">(current)</span></a></li>
                        <li><a href="preparation_trajet.php">Voir vos trajets</a></li>
                        <li><a href="messagerie.php">Messagerie</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Rechercher un trajet</h1>
                    <form action="selection_trajet.php" method="post" enctype="multipart/form-data">
                        <div class="row placeholders">
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Ville de départ</h4>
                                <span class="text-muted"><?php get_depart($login); ?></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Ville d'arrivée</h4>
                                <span class="text-muted"><?php get_arrivee($login); ?></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Date</h4>
                                <span class="text-muted">
                                <?php
								form_select_multiple("Choix du jour", "recherche_jour", listJour());
								form_select_multiple("Choix du mois", "recherche_mois", listMois());
								?>
							</span>
                            </div>
                       	
                        <button class="btn btn-lg btn-primary center-block" type="submit" name="recherche_trajet" value="Valider">Rechercher</button>

                    </form>
                    <?php
                    if (isset($erreur))
                        echo '<br />', $erreur;
                    ?>
                </div>
            </div>
    </body>
</html>

<?php
if (isset($_POST['recherche_trajet']) && $_POST['recherche_trajet'] == 'Valider') {
    // Les variables existent ? sont vides ?

    if ((isset($_POST['recherche_depart']) && !empty($_POST['recherche_depart'])) && (isset($_POST['recherche_arrivee']) && !empty($_POST['recherche_arrivee']))) 
    {
        ?>
        <!--div class="container-fluid"-->
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
                        <?php
                        recherche_trajet();
                        ?>
                    
                </div>
            </div>
            <?php
    } else {
        $erreur = 'Au moins un des champs est vide.';
    }
}

?>
