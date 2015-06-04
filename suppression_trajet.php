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
                        <li><a href="ajout_trajet.php">Rechercher un trajet</a></li>
                        <li class="active"><a href="suppression_trajet.php">Voir vos trajets<span class="sr-only">(current)</span></a></li>

                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Supprimer un trajet</h1>
                        <div class="row placeholders">
                           <?php
                           
                                if (isset($_POST['supprimer_trajet']) && $_POST['supprimer_trajet'] == 'Supprimer') {
            
                                    echo "<h2> Vous avez supprimé le trajet ";
                                    echo "<h4> Les membres suivants ont été prévenus et remboursés : ";
                                    $sql_id = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' . mysql_escape_string($_POST['id_trajet']) . '" AND conducteur=0';
                                    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
                                    
                                    while($row = mysql_fetch_array($query_id)) {
                                    
                                    echo get_login_membre($row['id_membre'])." ";
                                    envoi_message($login, get_login_membre($row['id_membre']), "Bonjour, ceci est un message automatique suite à la suppression du trajet auquel vous étiez inscrit. "
                                            . "Vous serez remboursé en intégralité, un dédommagement de 10€ supplémentaire vous sera offert.");
                                    }
                                    
                                    //Remboursement
                                    rembourser_trajet($login,($_POST['id_trajet']));
                                    
                                    //suppression
                                    $sql_del = 'DELETE FROM trajet WHERE id_trajet="' . mysql_escape_string($_POST['id_trajet']) . '"';
                                    $sql_del2 = 'DELETE FROM pres_trajet WHERE id_trajet="' . mysql_escape_string($_POST['id_trajet']) . '"';
                                    $query_id = mysql_query($sql_del2) or die('Erreur SQL !<br />' . $sql_del2 . '<br />' . mysql_error());
                                    $query_id = mysql_query($sql_del) or die('Erreur SQL !<br />' . $sql_del . '<br />' . mysql_error());
                                    
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
                                    <th>Membres Inscrits</th>
                                  </tr>
                            <?php 
                                $sql_id = 'SELECT * FROM trajet WHERE id_trajet="' . mysql_escape_string($_POST['id_trajet']) . '"';
                                $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
                                $row = mysql_fetch_array($query_id);

                                echo '<tr>';
                                echo '<td>'.$row['id_trajet'].'</td>';
                                echo '<td>'.$row['depart'].'</td>';
                                echo '<td>'.$row['arrivee'].'</td>';
                                echo '<td>'.substr($row['jour'],0,2).'/'.substr($row['jour'],2,2).'</td>';
                                echo '<td>'.$row['heure'].'</td>';
                                echo '<td>'.$row['nb_place'].'</td>';
                                echo '<td>'.$row['prix'].'</td>';
                                
                                
                                $sql_id2 = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' .$row['id_trajet'].'" AND conducteur = "0"';
                                $query_id2 = mysql_query($sql_id2) or die('Erreur SQL !<br />' . $sql_id2 . '<br />' . mysql_error());
                                echo '<td>';

                                //form pour la notation d'un membre
                                while($row2 = mysql_fetch_array($query_id2)) {
                                    echo '<p>';
                                    echo '<form action="messagerie.php" method="post" enctype="multipart/form-data">';
                                    echo '<button class="btn btn-group-sm btn-primary" type="submit" name="message_trajet" value="'.get_login_membre($row2['id_membre']).'">'.get_login_membre($row2['id_membre']).'</button>';
                                    echo '</form>';
                                  
                                }
                                echo '</tr>';
                                    
                             ?>
                                </table>
                             <h3> Etes-vous sûr de vouloir supprimer ce trajet ? </h3>
                             <h4> Un message sera envoyé au(x) membre(s) inscrit(s) et vous devrez rembourser 10€ à chacun</h4>
                            <form action="suppression_trajet.php" method="post" enctype="multipart/form-data">                           
                            <?php
                            echo '<input type="hidden" name="id_trajet" value="'.$_POST['id_trajet'].'">';
                            ?>
                                <br /><br />
                                <button class="btn btn-lg btn-primary center-block" type="submit" name="supprimer_trajet" value="Supprimer">Supprimer</button>
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

