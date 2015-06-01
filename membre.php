<?php
require_once 'requetes.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
$login = ($_SESSION['login']);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/dashboard.css" rel="stylesheet">
        <title>Profil</title>
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
                        <li class="active"><a href="membre.php">Profil<span class="sr-only">(current)</span></a></li>
                        <?php
                        if (test_vehicule_renseigne(($_SESSION['login']))) {
                            printf('<li><a href="vehicule.php">Modifier le vehicule</a></li>');
                        } else {
                            printf('<li><a href="vehicule.php">Renseigner le véhicule</a></li>');
                        }
                        ?>
                        <li><a href="ajout_trajet.php">Proposer un trajet</a></li>
                        <li><a href="selection_trajet.php">Rechercher un trajet</a></li>
                        <li><a href="preparation_trajet.php">Voir vos trajets</a></li>
                        <li><a href="messagerie.php">Messagerie</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">
                        <?php printf('Bienvenue %s<br />', htmlentities(trim($_SESSION['login'])));
                        ?></h1>

                    <div class="row placeholders">
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <?php
                            $avatar = get_avatar($login);
                            printf('<img src="%s" class="img-responsive" width="60" height="60" alt="" /></td><br />', $avatar);
                            ?>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <h4>Argent</h4>
                            <span class="text-muted">
                            <?php
                            printf(get_argent($login));
                            ?></span>
                            <a href="argent.php">Recharger</a>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <h4>Vehicule</h4>
                            <span class="text-muted"><?php printf(get_vehicule($login)); ?></span>
                        </div>
                        <div class="col-xs-6 col-sm-3 placeholder">
                            <h4>Note moyenne</h4>
                            <span class="text-muted"><?php printf(get_note($login));?></span>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>
