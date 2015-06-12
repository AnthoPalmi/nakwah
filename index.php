<?php
require_once ('requetes.php');
// Le visiteur est passé par le formulaire de connexion ?
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {
        $retour_erreur_connex = connexion($_POST['login'], $_POST['pass']);
    } else {
        $erreur = 'Au moins un champ est vide.';
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
        <title>Accueil</title>
        <!-- test js -->
        <script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="http://s3.amazonaws.com/codecademy-content/courses/hour-of-code/js/alphabet.js"></script>
    </head>

    <body>
        <div class="container text-center">
            <canvas id="myCanvas"></canvas>
            <script type="text/javascript" src="js/bubble.js"></script>
            <script type="text/javascript" src="js/main.js"></script>
            <form class="form-signin" action="index.php" method="post" enctype="multipart/form-data">
                <h2 class="form-signin-heading">Connexion à l'espace membre :</h2>
                <label for="Login" class="sr-only">Login</label>
                <input type="text" class="form-control" placeholder="Login" required autofocus name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"><br />
                <label for="inputPassword" class="sr-only">Mot de passe</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"><br />                          
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="connexion" value="Connexion">Connexion</button>
                <a href="inscription.php">Vous inscrire</a>
            </form>
        </div> <!-- /container -->


        <?php
        if (isset($erreur))
            echo '<br /><br />', $erreur;
        if (isset($retour_erreur_connex))
            echo '<br /><br />', $retour_erreur_connex;
        ?>
    </body>


</html>