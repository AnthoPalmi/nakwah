<?php
require_once 'requetes.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
$login = ($_SESSION['login']);

if (isset($_POST['messagerie']) && $_POST['messagerie'] == 'Envoyer') {
    if ((isset($_POST['message']) && !empty($_POST['message']))) {
        envoi_message($login, $_POST['membre'], $_POST['message']);
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
                        <?php
                        if (($_SESSION['login']) == 'admin') {
                            print <<<END
                            <li><a href = "liste_comptes.php">Membres</a></li>
                            <li><a href = "#">Trajets</a></li>
                            <li class = "active"><a href = "messagerie.php">Messagerie<span class="sr-only">(current)</span></a></li >
END;
                        } else {
                            printf('<li><a href="membre.php">Profil</a></li>');
                            if (test_vehicule_renseigne(($_SESSION['login']))) {
                                printf('<li><a href = "vehicule.php">Modifier le vehicule</a></li>');
                            } else {
                                printf('<li><a href = "vehicule.php">Renseigner le véhicule</a></li>');
                            }
                        printf('<li><a href="ajout_trajet.php">Proposer un trajet</a></li>
                        <li><a href="selection_trajet.php">Rechercher un trajet</a></li>
                        <li><a href="preparation_trajet.php">Voir vos trajets</a></li>');
                            printf('<li class = "active"><a href = "messagerie.php">Messagerie<span class = "sr-only">(current)</span></a></li>');
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Nouveau message<br /></h1>

                    <form action="messagerie.php" method="post" enctype="multipart/form-data">
                        <div class="row placeholders">
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Utilisateur</h4>
                                <span class="text-muted"><?php form_select_multiple("Choix du membre", "membre", listMembres()); ?></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Message</h4>
                                <span class="text-muted"><textarea class="form-control" name="message" rows="3"></textarea>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <br /><button class="btn btn-lg btn-primary center-block" type="submit" name="messagerie" value="Envoyer">Envoyer</button>
                            </div>
                        </div>
                    </form>

                    <?php
                    afficher_messages($login);
                    if (isset($erreur))
                        echo '<br />', $erreur;
                    ?>
                </div>
            </div>
    </body>
</html>
