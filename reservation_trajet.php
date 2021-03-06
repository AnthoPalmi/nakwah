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
        <title>Réserver un trajet</title>
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
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Réserver un trajet</h1>
                        <div class="row placeholders">
                            
                            <?php 

                            if (isset($_POST['reservation_place']) && $_POST['reservation_place'] == 'Reserver') {
                                
                                    //modification de la solde du compte utilisateur
                                    //payer($login,$_POST['nombre_place'],$_POST['prix_trajet'],$_POST['id_trajet']);

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
                                    
                                    $sql = 'SELECT * FROM pres_trajet  WHERE id_trajet='.$_POST['id_trajet'].' AND id_membre='.get_id_membre($login);
                                    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
                                    $data = mysql_fetch_array($req);
                                    
                                    if ($data[0] == 0){
                                        $sql_id = 'INSERT INTO pres_trajet VALUES ("'.$_POST['id_trajet'].'","'.get_id_membre($login).'", "0",'.$_POST['nombre_place'].')';
                                        $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
                                    }else {
                                        $sql_id = 'UPDATE pres_trajet set nb_places=nb_places+'.$_POST['nombre_place'].' WHERE id_trajet = '.$_POST['id_trajet'].' AND id_membre='.get_id_membre($login);
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

                            <table class="table table-stripped">
                                  <tr>
                                    <th>ID</th>
                                    <th>Depart</th>
                                    <th>Arrivée</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Nombre de place</th>
                                    <th>Prix</th>
                                  </tr>
                            <?php 
                                $sql_id = 'SELECT * FROM trajet WHERE id_trajet="' . mysql_escape_string($_POST['reservation_trajet']) . '"';
                                $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
                                $row = mysql_fetch_array($query_id);

                                echo '<tr>';
                                echo '<td>'.$row['id_trajet'].'</td>';
                                echo '<td>'.$row['depart'].'</td>';
                                echo '<td>'.$row['arrivee'].'</td>';
                                echo '<td>'.substr($row['jour'],0,2).'/'.substr($row['jour'],2,2).'/'.substr($row['jour'],6,2).'</td>';
                                echo '<td>'.$row['heure'].'</td>';
                                echo '<td>'.$row['nb_place'].'</td>';
                                echo '<td>'.$row['prix'].'</td>';
                                echo '</tr>';

                             ?>
                                </table>
                             <h3> Combien de place souhaitez-vous réserver ? </h3>
                            <form action="reservation_trajet.php" method="post" enctype="multipart/form-data">
                            <?php $it = $row['id_trajet'];
                            $np = $row['nb_place'];
                            $prix = $row['prix'];

                            echo "<input type='hidden' name='id_trajet' value='".$it."'>"; 
                            echo "<input type='hidden' name='nb_place_init' value='".$np."'>";
                            echo "<input type='hidden' name='prix_trajet' value='$prix'>"
                            ?>
                            <select name='nombre_place'>
                            <?php
                                for($i = 1; $i<=$row['nb_place'];$i++)  {
                                    echo ("<option value=".$i.">".$i."</option>");
                                }   
                            ?> 
                            </select>
                                <br /><br />
                                <button class="btn btn-lg btn-primary center-block" type="submit" name="reservation_place" value="Reserver">Réserver</button>
                            </form>               	

                            <?php

                            }
                            if (isset($erreur))
                                echo '<br />', $erreur;

                    

                            ?>
                </div>
            </div>
    </body>
</html>

