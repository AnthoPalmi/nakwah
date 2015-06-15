<?php

require_once 'requetes.php';

// Le visiteur a soumis le formulaire ?
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
    // Les variables existent ? sont vides ?
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm'])) && (isset($_FILES['image']['name']) && !empty($_FILES['image']['name']))) {
        // test des mots de passe identiques 
        if ($_POST['pass'] != $_POST['pass_confirm']) {
            $erreur = 'Les 2 mots de passe sont différents.';
        } else {
            inscription_page($_POST,$_FILES);
        }
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
        <link href="bootstrap/css/signin.css" rel="stylesheet">
        <title>Inscription</title>
    </head>

    <body>

        <div class="container">

            <form class="form-signin" action="inscription.php" method="post" enctype="multipart/form-data">
                <h2 class="form-signin-heading">Inscription</h2>
                <label for="Login" class="sr-only">Login</label>
                <input type="text" class="form-control" placeholder="Login" required autofocus name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"><br />
                <label for="inputPassword" class="sr-only">Mot de passe</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"><br />                          
                <label for="inputPassword" class="sr-only">Confirmation du mot de passe</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Confirmation du mot de passe" required name="pass_confirm" value="<?php if (isset($_POST['pass_confirm'])) echo htmlentities(trim($_POST['pass_confirm'])); ?>"><br />
                <label for="Nom" class="sr-only">Nom</label>
                <input type="text" class="form-control" placeholder="Nom" required autofocus name="nom" value="<?php if (isset($_POST['nom'])) echo htmlentities(trim($_POST['nom'])); ?>"><br />

                <label for="Prenom" class="sr-only">Prenom</label>
                <input type="text" class="form-control" placeholder="Prenom" required autofocus name="prenom" value="<?php if (isset($_POST['prenom'])) echo htmlentities(trim($_POST['prenom'])); ?>"><br />

                <label for="Date de naissance">Date de naissance</label><br />
<?php
form_select_multiple("Choix du jour", "jour", listJour());
form_select_multiple("Choix du mois", "mois", listMois());
form_select_multiple("Choix de l'année", "annee", listAnnee());
?>               
                <br />
                <label for="mon_image">Image</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                <input type="file" name="image" id="img"/>
                <p/>
                <br />

                <button class="btn btn-lg btn-primary btn-block" type="submit" name="inscription" value="Inscription">Inscription</button>
            </form>
<?php
if (isset($erreur))
    echo '<br />', $erreur;
?>
        </div> <!-- /container -->

    </body>
</html>