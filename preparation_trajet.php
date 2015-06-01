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
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Voir vos trajets</h1>
                        <div class="row placeholders">
                            
                            <?php 

                            if (isset($_POST['reservation_place']) && $_POST['reservation_place'] == 'Reserver') {

                                    //echo "id trajet = ".$_POST['id_trajet']." et nb_place_init = ".$_POST['nb_place_init'];
                                    echo "<h2> Vous avez réservé ".$_POST['nombre_place'];

                                    if($_POST['nombre_place'] == 1){
                                         echo " place sur ce trajet.";
                                    }else{
                                         echo " places sur ce trajet.";
                                    }
                                    $nb_place_restantes = $_POST['nb_place_init'] - $_POST['nombre_place'];

                                    $sql_id = 'UPDATE trajet SET nb_place='.$nb_place_restantes.' WHERE id_trajet = '.$_POST['id_trajet'];
                                    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());

                                    for($i = 0; $i<$_POST['nombre_place'];$i++){
                                        $sql_id = 'INSERT INTO pres_trajet VALUES ("'.$_POST['id_trajet'].'","'.get_id_membre($login).'", "0")';
                                        $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
                                        
                                    }
                                    ?>
                                    <br/><br/>
                                    <form method="link" action="membre.php">
                                        <input class="btn btn-lg btn-primary center-block" type="submit" value="Retour">
                                    <form>

                                    <?php

                    
                            }else{

                            ?>   
                            <h3> Les trajets où vous conduisez : <h3>
                            <h5> Cliquer sur le login d'un membre pour lui envoyer un message privé</h5><br/>


                            <table class="table table-stripped">
                                <!-- Tête du tableau-->
                                  <tr>
                                    <th>ID</th>
                                    <th>Depart</th>
                                    <th>Arrivée</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Nombre de place restante</th>
                                    <th>Prix</th>
                                    <th>Membres Inscrits</th>
                                    <th></th>
                                  </tr>


                                
                            <?php 
                                
                                // On cherche tous les trajets où le membre de la session active conduit (cad les trajets qu'il a proposé)
                                $sql_id = 'SELECT * FROM trajet WHERE id_membre="' .get_id_membre($login). '"';
                                $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());

                                //puis on les affiche
                                while ($row = mysql_fetch_array($query_id)) {
                                    echo '<tr>';
                                    echo '<td>'.$row['id_trajet'].'</td>';
                                    echo '<td>'.$row['depart'].'</td>';
                                    echo '<td>'.$row['arrivee'].'</td>';
                                    echo '<td>'.substr($row['jour'],0,2).'/'.substr($row['jour'],2,2).'</td>';
                                    echo '<td>'.$row['heure'].'</td>';
                                    echo '<td>'.$row['nb_place'].'</td>';
                                    echo '<td>'.$row['prix'].'</td>';

                                    // L'affichage comprend aussi les autres membres qui ont réservé des places sur les trajets
                                    $sql_id2 = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' .$row['id_trajet'].'" AND conducteur = "0"';
                                    $query_id2 = mysql_query($sql_id2) or die('Erreur SQL !<br />' . $sql_id2 . '<br />' . mysql_error());
                                    echo '<td>';
                                    
                                    //form pour la notation d'un membre
                                    while($row2 = mysql_fetch_array($query_id2)) {
                                        echo '<p>';
                                        echo '<form action="messagerie.php" method="post" enctype="multipart/form-data">';
                                        echo '<button class="btn btn-group-sm btn-primary" type="submit" name="message_trajet" value="'.get_login_membre($row2['id_membre']).'">'.get_login_membre($row2['id_membre']).'</button>';
                                        echo '</form>';
                                        echo '<form action="preparation_trajet.php" method="post" enctype="multipart/form-data">';
                                        form_select_multiple(note, note, listNote());
                                        echo '<input type="hidden" name="id_trajet" value='.$row['id_trajet'].'>';
                                        echo '<button class="btn btn-group-sm btn-primary" type="submit" name="Noter" value="'.get_login_membre($row2['id_membre']).'">Noter</button>';                                        
                                        echo'</form>';
                                    }
                                    
                                    echo'</td>';
                                    echo'<form action="suppression_trajet.php" method="post" enctype="multipart/form-data">';
                                    echo ("<td><button class='btn btn-group-sm btn-primary center-block' type='submit' name='supprimer-trajet' value=".$row['id_trajet']."> Supprimer Trajet </button></td>");
                                    echo'</form>';
                                echo '</tr>';
                                }
                            
                            
                             echo '</table>';
                            
                            }
                            if (isset($erreur))
                                echo '<br />', $erreur;

                            ?>
                                </form>
                </div>
            </div>
    </body>
</html>

