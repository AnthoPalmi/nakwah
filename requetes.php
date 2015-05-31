<?php
$connect = mysql_connect('localhost', 'root', 'root') or die("Erreur de connexion au serveur.");
mysql_select_db('LO07', $connect);

function get_id_membre($login) {
    $sql_id = 'SELECT id_membre FROM membre where login="' . $login . '"';
    $req_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
    $id_membre = mysql_fetch_array($req_id);

    return $id_membre[0];
}

function get_login_membre($id) {
    $sql_login = 'SELECT login FROM membre WHERE id_membre="' . $id . '"';
    $req_login = mysql_query($sql_login) or die('Erreur SQL !<br />' . $sql_login . '<br />' . mysql_error());
    $id_login = mysql_fetch_array($req_login);

    return $id_login[0];
}

function get_vehicule($login) {
    if (test_vehicule_renseigne($login)) {
        $id_membre = get_id_membre($login);
        $sql = 'SELECT marque, modele FROM voiture where id_membre=' . $id_membre;
        $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
        $data = mysql_fetch_array($req);

        $voiture = $data[0] . " " . $data[1];
        return $voiture;
    } else {
        return "non renseigné";
    }
}

function test_vehicule_renseigne($login) {
    $id_membre = get_id_membre($login);

    $sql = 'SELECT * FROM voiture where id_membre=' . $id_membre;
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    $data = mysql_fetch_array($req);

    if ($data[0] == 0) {
        return false;
    } else {
        return true;
    }
}

function get_avatar($login) {
    $id_membre = get_id_membre($login);

    $sql_image = 'SELECT image FROM membre WHERE id_membre=' . $id_membre;
    $req_image = mysql_query($sql_image) or die('Erreur SQL !<br />' . $sql_image . '<br />' . mysql_error());
    $data = mysql_fetch_array($req_image);

    if ($data[0] == "images/") {
        $data[0] = "images/no_avatar.png";
    }
    return $data[0];
}

function search($keyword) {
    $sql = "SELECT * FROM membre WHERE nom LIKE '%" . $keyword . "%' OR prenom LIKE '%" . $keyword . "%'";
    affichage($sql);
}

function afficher_tableau() {
    $sql = 'SELECT * FROM membre';
    affichage($sql);
}

function affichage($sql) {
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    print <<<END
    <h2 class="sub-header">Liste des membres</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                    <th colspan=7>Membre</th>
                    <th colspan=4>Voiture</th>
                </tr>
                <tr>
                    <th>Id</th>
                    <th>Login</th>
                    <th>Pass</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Naissance</th>
                    <th>Image</th>
                    <th>Marque</th>
                    <th>Modele</th>
                    <th>Couleur</th>
                    <th>Annee</th>
                </tr>
            </thead>
            <tbody>  
END;

    printf('<tr>');
    while ($data = mysql_fetch_assoc($req)) {
        $sql_voiture = 'SELECT marque,modele,couleur,annee FROM voiture WHERE id_membre=' . mysql_escape_string($data['id_membre']);
        $req_voiture = mysql_query($sql_voiture) or die('Erreur SQL !<br />' . $sql_voiture . '<br />' . mysql_error());

        foreach ($data as $key => $value) {
            if ($key == 'image') {
                printf('<td><img src="%s" width="60" height="60" alt="" /></td>', $value);
            } else {
                printf('<td>%s</td>', $value);
            }
        }
        while ($data_v = mysql_fetch_assoc($req_voiture)) {
            foreach ($data_v as $key => $value) {
                printf('<td>%s</td>', $value);
            }
        }
        printf('</tr>');
    }
    printf('
            </tbody>
        </table>
 </div>');
}

function connexion($login, $pass) {
    // Test si la base de donnée contient le login et le pass
    $sql = "SELECT count(*) FROM membre WHERE login='" . mysql_escape_string($login) . "' AND pass='" . mysql_escape_string($pass) . "'";
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    $data = mysql_fetch_array($req);

    mysql_free_result($req);
    mysql_close();

    // si ok, alors la bdd contient le membre
    if ($data[0] == 1) {
        session_start();
        $_SESSION['login'] = $login;
        if ($_SESSION['login'] == "admin") {
            header('Location: liste_comptes.php');
        } else {
            header('Location: membre.php');
        }
        exit();
    }
    // sinon erreur dans login ou mot de passe
    elseif ($data[0] == 0) {
        $erreur = 'Compte non reconnu.';
    } else {
        $erreur = 'Problème dans la base de données';
    }
    return $erreur;
}

function listMois() {
    return array(
        "01" => "janvier",
        "02" => "fevrier",
        "03" => "mars",
        "04" => "avril",
        "05" => "mai",
        "06" => "juin",
        "07" => "juillet",
        "08" => "aout",
        "09" => "septembre",
        "10" => "octobre",
        "11" => "novembre",
        "12" => "decembre"
    );
}

function listJour() {
    $tab = array();
    for ($i = 1; $i < 32; $i++) {
        if ($i < 10) {
            $i = str_pad($i, 2, "0", STR_PAD_LEFT);
        }
        $tab[$i] = $i;
    }
    return $tab;
}

function listAnnee() {
    $tab = array();
    for ($i = 2015; $i > 1900; $i--) {
        $tab[$i] = $i;
    }
    return $tab;
}

function listMembres() {
    $sql = 'SELECT login FROM membre';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    while ($data = mysql_fetch_assoc($req)) {
        foreach ($data as $key => $value) {
            if ($value != 'admin')
                $array[$value] = $value;
        }
    }
    return $array;
}

function listHeure() {
    $tab = array();
    for ($i = 0; $i < 24; $i++) {
        $tab[$i] = $i . " h";
    }
    return $tab;
}

function form_select_multiple($label, $name, $hashtable, $selected) {
    echo("<!-- form_select_multiple : $label $name-->\n");
    printf(" <select name='%s'>\n", $name);
    foreach ($hashtable as $key => $value) {
        if ($value == $selected) {
            printf(" <option value='%s' selected>%s</option>\n", $key, $value);
        } else {
            printf(" <option value='%s'>%s</option>\n", $key, $value);
        }
    }
    echo(" </select> \n");
}

function insertion_vehicule($marque, $modele, $couleur, $annee) {
    session_start();

    // On récupère l'ID du membre
    $id = get_id_membre($_SESSION['login']);

    // on recherche si le membre a deja un vehicule enregistré
    $sql_count = 'SELECT count(*) FROM voiture WHERE id_membre=' . mysql_escape_string($id);
    $query_count = mysql_query($sql_count) or die('Erreur SQL !<br />' . $sql_count . '<br />' . mysql_error());
    $data_count = mysql_fetch_array($query_count);

    if ($data_count[0] == 0) {
        $sql = 'INSERT INTO voiture VALUES("", ' . mysql_escape_string($id)
                . ', "' . mysql_escape_string($_POST['marque'])
                . '", "' . mysql_escape_string($_POST['modele'])
                . '", "' . mysql_escape_string($_POST['couleur'])
                . '", "' . mysql_escape_string($_POST['annee'])
                . '")';
        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());
    } else {
        $sql2 = 'UPDATE voiture SET marque="' . mysql_escape_string($_POST['marque'])
                . '", modele="' . mysql_escape_string($_POST['modele'])
                . '", couleur="' . mysql_escape_string($_POST['couleur'])
                . '", annee="' . mysql_escape_string($_POST['annee'])
                . '" WHERE id_membre=' . mysql_escape_string($id);
        mysql_query($sql2) or die('Erreur SQL !' . $sql2 . '<br />' . mysql_error());
    }

    header('Location: membre.php');
    exit();
}

function inscription($fichiers, $post) {
    if ($fichiers['image']['error'] > 0)
        $erreur = "Erreur lors du transfert";
//Créer un dossier 'images/'
    mkdir('images/', 0777, true);

//Créer un identifiant difficile à deviner
//          $nom = md5(uniqid(rand(), true));
//deplacer le fichier
    $nom = $fichiers["image"]["name"];
    $chemin = "images/" . $nom;
    $tmpLoc = $fichiers["image"]["tmp_name"];
    $resultat = move_uploaded_file($tmpLoc, $chemin);


    $connect = mysql_connect('localhost', 'root', 'root') or die("Erreur de connexion au serveur.");
    mysql_select_db('LO07', $connect);

    // on recherche si ce login est déjà utilisé par un autre membre
    $sql = 'SELECT count(*) FROM membre WHERE login="' . mysql_escape_string($post['login']) . '"';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    $data = mysql_fetch_array($req);

    if ($data[0] == 0) {
        $sql = 'INSERT INTO membre VALUES("", "' . mysql_escape_string($post['login'])
                . '", "' . mysql_escape_string($post['pass'])
                . '", "' . mysql_escape_string($post['nom'])
                . '", "' . mysql_escape_string($post['prenom'])
                . '", "' . mysql_escape_string($post['jour']) . mysql_real_escape_string($post['mois']) . mysql_escape_string($post['annee'])
                . '", "' . mysql_escape_string($chemin)
                . '")';
        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());

        session_start();
        $_SESSION['login'] = $post['login'];
        header('Location: membre.php');
        exit();
    } else {
        $erreur = 'Un membre a déjà ce login. Veuillez en choisir un autre';
    }
}

function afficher_messages($login) {

    $id = get_id_membre($login);
    $sql = 'SELECT id_membre1, id_membre2, texte FROM message where id_membre1 =' . mysql_escape_string($id) . ' OR id_membre2 =' . mysql_escape_string($id);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    print <<<END
    <h2 class="sub-header">Liste des messages</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                    <th>Membre</th>
                    <th>Image</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>  
END;

    while ($data = mysql_fetch_array($req)) {
        //si le membre actuel est le destinataire du message
        if ($data[1] == $id){
            $sql_membre2 = 'SELECT login, image FROM membre WHERE id_membre=' . mysql_escape_string($data[0]);
            $fromto = "Reçu de";
        }else{
            $sql_membre2 = 'SELECT login, image FROM membre WHERE id_membre=' . mysql_escape_string($data[1]);
            $fromto = "Envoyé à";
        }    
        $req_membre2 = mysql_query($sql_membre2) or die('Erreur SQL !<br />' . $sql_membre2 . '<br />' . mysql_error());
        $data2 = mysql_fetch_assoc($req_membre2);

        foreach ($data2 as $key => $value) {
            if ($key == 'login') {
                printf('<td>%s : %s</td>',$fromto, $value);
            }
            if ($key == 'image') {
                printf('<td><img src="%s" width="60" height="60" alt="" /></td>', $value);
            }
        }
        printf('<td>%s</td>', $data['texte']);
        while ($data_v = mysql_fetch_assoc($req_voiture)) {
            foreach ($data_v as $key => $value) {
                printf('<td>%s</td>', $value);
            }
        }
        printf('</tr>');
    }
    printf('
            </tbody>
        </table>
 </div>');
}

function envoi_message($login1, $login2, $message) {
    $id1 = get_id_membre($login1);
    $id2 = get_id_membre($login2);

    $sql = 'INSERT INTO message values ("",' . mysql_escape_string($id1) . ',' . mysql_escape_string($id2) . ',"' . mysql_escape_string($message) . '")';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    header('Location: messagerie.php');
    exit();
}

function input_voiture($input) {
    session_start();

    // On récupère l'ID du membre
    $id = get_id_membre($_SESSION['login']);

    // on recherche si le membre a deja un vehicule enregistré
    $sql_count = 'SELECT count(*) FROM voiture WHERE id_membre=' . mysql_escape_string($id);
    $query_count = mysql_query($sql_count) or die('Erreur SQL !<br />' . $sql_count . '<br />' . mysql_error());
    $data_count = mysql_fetch_array($query_count);

    if ($data_count[0] == 0) {
        if ($input == 'annee') {
            form_select_multiple("Choix de l'année", "annee", listAnnee());
        } else {
            printf('<span class="text-muted"><input type="text" name="%s"></span>', $input);
        }
    } else {
        $sql2 = 'SELECT ' . mysql_escape_string($input) . ' FROM voiture WHERE id_membre=' . mysql_escape_string($id);
        $req_membre2 = mysql_query($sql2) or die('Erreur SQL !' . $sql2 . '<br />' . mysql_error());
        $data2 = mysql_fetch_assoc($req_membre2);

        if ($input == 'annee') {
            form_select_multiple("Choix de l'année", "annee", listAnnee(), $data2[$input]);
        } else {

            printf('<span class="text-muted"><input type="text" placeholder="%s" name="%s"></span>', $data2[$input], $input);
        }
    }
}

function recherche_trajet() {
    session_start();

    // On récupère l'ID du membre
    $sql_id = 'SELECT id_membre FROM membre WHERE login="' . mysql_escape_string($_SESSION['login']) . '"';
    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
    $data_id = mysql_fetch_array($query_id);
    $id = $data_id[0];

    $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
            . '" AND depart = "' . mysql_escape_string($_POST['recherche_depart'])
            . '" AND arrivee = "' . mysql_escape_string($_POST['recherche_arrivee'])
            . '" AND jour = "' . mysql_escape_string($_POST['recherche_jour']) . mysql_escape_string($_POST['recherche_mois'])
            . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';

    //echo $sql_recherche;
    $query_recherche = mysql_query($sql_recherche) or die('Erreur SQL !<br />' . $sql_recherche . '<br />' . mysql_error());
    ?>
    <!--table class="table">
      <tr>
        <th>$id</th>
        <th>$_POST["recherche_depart"]</th>
        <th>$_POST["recherche_arrivee"]</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Nombre de place</th>
        <th>Prix</th>
      </tr-->

    <table class="table table-stripped">
        <tr>
            <th>ID</th>
            <th>Depart</th>
            <th>Arrivée</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Nombre de place</th>
            <th>Prix</th>
            <th>    </th>
        </tr>
        <form action="reservation_trajet.php" method="post" enctype="multipart/form-data">
            <?php
            while ($row = mysql_fetch_array($query_recherche)) {

                echo '<tr>';
                echo '<td>' . $row['id_trajet'] . '</td>';
                echo '<td>' . $row['depart'] . '</td>';
                echo '<td>' . $row['arrivee'] . '</td>';
                echo '<td>' . substr($row['jour'], 0, 2) . '/' . substr($row['jour'], 2, 2) . '</td>';
                echo '<td>' . $row['heure'] . '</td>';
                echo '<td>' . $row['nb_place'] . '</td>';
                echo '<td>' . $row['prix'] . '</td>';
                echo ("<td><button class='btn btn-lg btn-primary center-block' type='submit' name='reservation_trajet' value=" . $row['id_trajet'] . "> Réserver </button></td>");
                echo '</tr>';
            }
            ?> 
    </table>
    </form> 

    <?php
    exit();
}

function insertion_trajet() {
    session_start();

    // On récupère l'ID du membre
    $sql_id = 'SELECT id_membre FROM membre WHERE login="' . mysql_escape_string($_SESSION['login']) . '"';
    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
    $data_id = mysql_fetch_array($query_id);
    $id = $data_id[0];

    $sql = 'INSERT INTO trajet VALUES("", ' . mysql_escape_string($id)
            . ', "' . mysql_escape_string($_POST['depart'])
            . '", "' . mysql_escape_string($_POST['arrivee'])
            . '", "' . mysql_escape_string($_POST['jour']) . mysql_real_escape_string($_POST['mois'])
            . '", "' . mysql_escape_string($_POST['heure'])
            . '", "' . mysql_escape_string($_POST['nb_place'])
            . '", "' . mysql_escape_string($_POST['prix'])
            . '","")';
    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());

    $sql = 'SELECT id_trajet FROM trajet WHERE id_membre = "' . mysql_escape_string($id)
            . '" AND depart = "' . mysql_escape_string($_POST['depart'])
            . '" AND arrivee = "' . mysql_escape_string($_POST['arrivee'])
            . '" AND jour = "' . mysql_escape_string($_POST['jour']) . mysql_real_escape_string($_POST['mois'])
            . '" AND heure = "' . mysql_escape_string($_POST['heure'])
            . '" AND nb_place = "' . mysql_escape_string($_POST['nb_place'])
            . '" AND prix = "' . mysql_escape_string($_POST['prix']) . '"';
    $query_id = mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());
    $data_id = mysql_fetch_array($query_id);
    $id_trajet = $data_id[0];

    $sql_id = 'INSERT INTO pres_trajet VALUES ("' . $id_trajet . '","' . $id . '", "1")';
    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());

    header('Location: membre.php');
    exit();
}

function get_depart($login) {

    $id_membre = get_id_membre($login);

    $sql_depart = 'SELECT DISTINCT depart FROM trajet WHERE id_membre != ' . $id_membre . ' ORDER BY depart ASC';
    $req_depart = mysql_query($sql_depart) or die('Erreur SQL !<br />' . $sql_depart . '<br />' . mysql_error());

    echo ("<select name='recherche_depart'>");
    while ($row = mysql_fetch_array($req_depart)) {
        echo ("<option value=" . $row['depart'] . ">" . $row['depart'] . "</option>\n");
    }
    echo '</select>';
}

function get_arrivee($login) {

    $id_membre = get_id_membre($login);

    $sql_arrivee = 'SELECT DISTINCT arrivee FROM trajet WHERE id_membre != ' . $id_membre . ' ORDER BY arrivee ASC';
    $req_arrivee = mysql_query($sql_arrivee) or die('Erreur SQL !<br />' . $sql_arrivee . '<br />' . mysql_error());

    echo ("<select name='recherche_arrivee'>");
    while ($row = mysql_fetch_array($req_arrivee)) {
        echo ("<option value=" . $row['arrivee'] . ">" . $row['arrivee'] . "</option>\n");
    }
    echo '</select>';
}

function afficher_tous_trajets() {
    printf('<h2 class="sub-header">Liste des Trajets</h2>
            <table class="table table-stripped">
                <tr>
                    <th colspan=7>Trajet</th>
                    <th colspan=2>Conducteur</th>
                </tr>
                <tr>
                  <th>ID</th>
                  <th>Depart</th>
                  <th>Arrivée</th>
                  <th>Date</th>
                  <th>Heure</th>
                  <th>Nombre de place</th>
                  <th>Prix</th>
                  <th>ID</th>
                  <th>Login</th>
                </tr>');

    $sql_trajets = 'SELECT * FROM trajet';
    $query_trajets = mysql_query($sql_trajets) or die('Erreur SQL !<br />' . $sql_trajets . '<br />' . mysql_error());

    while ($row = mysql_fetch_assoc($query_trajets)) {

        $sql_conducteur = 'SELECT login FROM membre WHERE id_membre=' . mysql_escape_string($row['id_membre']);
        $sql_conducteur = mysql_query($sql_conducteur) or die('Erreur SQL !<br />' . $sql_conducteur . '<br />' . mysql_error());
        $row_conducteur = mysql_fetch_assoc($sql_conducteur);
        echo '<tr>';
        echo '<td>' . $row['id_trajet'] . '</td>';
        echo '<td>' . $row['depart'] . '</td>';
        echo '<td>' . $row['arrivee'] . '</td>';
        echo '<td>' . substr($row['jour'], 0, 2) . '/' . substr($row['jour'], 2, 2) . '</td>';
        echo '<td>' . $row['heure'] . '</td>';
        echo '<td>' . $row['nb_place'] . '</td>';
        echo '<td>' . $row['prix'] . '</td>';
        echo '<td>' . $row['id_membre'] . '</td>';
        echo '<td>' . $row_conducteur['login'] . '</td>';

        echo '</tr>';
    }


    printf('</table>');
}
