<?php
require_once 'requetes.php';

session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
$login = ($_SESSION['login']);

$valide = false;


/*if (test_vehicule_renseigne(($_SESSION['login'])) == false) {
	printf('Veuillez d\'abord renseigner votre véhicule.');
}else{
}*/

// formulaire déjà rempli ?
if (isset($_POST['trajet']) && $_POST['trajet'] == 'Valider') {
	// Les variables existent ? sont vides ?

    if ((isset($_POST['depart']) && !empty($_POST['depart'])) && (isset($_POST['arrivee']) && !empty($_POST['arrivee'])) && (isset($_POST['nb_place']) && !empty($_POST['nb_place'])) && (isset($_POST['prix']) && !empty($_POST['prix']))) 
    {

    	insertion_trajet();

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
        <title>Trajet</title>
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
                        <li class="active"><a href="ajout_trajet.php">Proposer un trajet<span class="sr-only">(current)</span></a></li>
                        <li><a href="selection_trajet.php">Rechercher un trajet</a></li>
                        <li><a href="preparation_trajet.php">Voir vos trajets</a></li>
                        <li><a href="messagerie.php">Messagerie</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Trajet</h1>
                    <form action="ajout_trajet.php" method="post" enctype="multipart/form-data">
                        <div class="row placeholders">
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Ville de départ</h4>
                                <span class="text-muted"><input type="text" name="depart"></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Ville d'arrivée</h4>
                                <span class="text-muted"><input type="text" name="arrivee"></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Date & Heure</h4>
                                <span class="text-muted">
                                <?php
								form_select_multiple("Choix du jour", "jour", listJour());
								form_select_multiple("Choix du mois", "mois", listMois());
                                                                form_select_multiple("Choix de l'année", "annee", listAnneeTrajet());
								form_select_multiple("Choix de l'heure", "heure", listHeure());
								?>
							</span>
                            </div>
                       	</div>
                       	<div class="row placeholders">
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Nombre de Places</h4>
                                <span class="text-muted"><input type="number" name="nb_place"></span>
                            </div>
                            <div class="col-xs-6 col-sm-3 placeholder">
                                <h4>Prix</h4>
                                <span class="text-muted"><input type="number" name="prix"></span>
                            </div>
                        </div>
                        <button class="btn btn-lg btn-primary center-block" type="submit" name="trajet" value="Valider">Valider</button>

                    </form>
                    <?php
                    if ($valide == true) {
                                    echo ('Trajet enregistré !');
                                }
                    if (isset($erreur))
                        echo '<br />', $erreur;
                    ?>
                </div>
            </div>
    </body>
</html>





<?php /*
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/signin.css" rel="stylesheet">
        <title>Trajets</title>
    </head>

     <body>

        <div class="container">

            <form class="form-signin" action="ajout_trajet.php" method="post" enctype="multipart/form-data">
			<h2 class="form-signin-heading">Proposer un trajet</h2>
           
            <label for="depart" class="sr-only">Ville de départ</label>
            <input type="text" class="form-control" placeholder="Ville de départ" required autofocus name="depart" value="<?php if (isset($_POST['depart'])) echo htmlentities(trim($_POST['depart'])); ?>"><br />
           
            <label for="arrivee" class="sr-only">Ville d'arrivée</label>
            <input type="text" id="arrivee" class="form-control" placeholder="Ville d'arrivée" required name="arrivee" value="<?php if (isset($_POST['arrivee'])) echo htmlentities(trim($_POST['arrivee'])); ?>"><br /> 

           

			<label for="Date">Date & Heure</label><br />
			
			<?php
			form_select_multiple("Choix du jour", "jour", listJour());
			form_select_multiple("Choix du mois", "mois", listMois());
			form_select_multiple("Choix de l'heure", "heure", listHeure());
			?>               
            <br /><br />
            

            <label for="nb_place" class="sr-only">Nombre de place</label>
            <input type="number" id="nb_place" class="form-control" placeholder="Nombre de place (conducteur exclus)" required name="nb_place" value="<?php if (isset($_POST['nb_place'])) echo htmlentities(trim($_POST['nb_place'])); ?>"><br /> 

            <label for="prix" class="sr-only">Prix</label>
            <input type="number" id="prix" class="form-control" placeholder="Prix par personne" required name="prix" value="<?php if (isset($_POST['prix'])) echo htmlentities(trim($_POST['prix'])); ?>"><br /> 

            <br />

            <button class="btn btn-lg btn-primary btn-block" type="submit" name="trajet" value="Valider">Proposer le trajet</button>
        </form>

<?php
if (isset($erreur))
echo '<br />', $erreur;
?>
    </div> <!-- /container -->

</body>
</html>

*/?>