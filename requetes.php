<?php
$connect = mysql_connect('localhost', 'root', 'root') or die("Erreur de connexion au serveur.");
mysql_select_db('LO07', $connect);

//Récupère l'id du membre
function get_id_membre($login) {
    $sql_id = 'SELECT id_membre FROM membre where login="' . $login . '"';
    $req_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());
    $id_membre = mysql_fetch_array($req_id);

    return $id_membre[0];
}

//Récupère le login du membre
function get_login_membre($id) {
    $sql_login = 'SELECT login FROM membre WHERE id_membre="' . $id . '"';
    $req_login = mysql_query($sql_login) or die('Erreur SQL !<br />' . $sql_login . '<br />' . mysql_error());
    $id_login = mysql_fetch_array($req_login);

    return $id_login[0];
}

//Récupère le vehicule s'il est renseigné
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

//Test si le vehicule est renseigné
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

//Récupère l'avatar
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

//Fonction de recherche du membre
function search($keyword) {
    $sql = "SELECT * FROM membre WHERE nom LIKE '%" . $keyword . "%' OR prenom LIKE '%" . $keyword . "%'";
    affichage($sql);
}

//Prépare la requete SQL de récupération de tous les membres
function afficher_tableau() {
    $sql = 'SELECT * FROM membre';
    affichage($sql);
}

//Affiche le tableau de tous les membres et leurs voitures
function affichage($sql) {
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    print <<<END
    <h2 class="sub-header">Liste des membres</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                    <th colspan=9>Membre</th>
                    <th colspan=4>Voiture</th>
                </tr>
                <tr>
                    <th>Id</th>
                    <th>Login</th>
                    <th>Pass</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Naissance</th>
                    <th>Argent</th>
                    <th>Note</th>
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

//Affiche tous les trajets
function afficher_tous_trajets($keyword) {
    printf('<h2 class="sub-header">Liste des trajets</h2>
            <table class="table table-stripped">');
      
    

    //trajet non effectues
    tab_trajets($keyword,0);
    //trajet  effectues
    tab_trajets($keyword,1);

    printf('</table>');
}

//Affiche tous les trajets
function tab_trajets($keyword,$effectue){
    printf('<tr>
                    <th colspan=7>Trajet ');
    if (!$effectue)
        printf('non ');
    printf('effectués</th>
                    <th colspan=2>Conducteur</th>
                    <th colspan=2>Passager(s)</th>
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
                  <th>ID</th>
                  <th>Login</th>
                </tr>');
    //Récupère toutes les informations des trajets
    if ($keyword){
        $sql_trajets = "SELECT * FROM trajet WHERE (depart LIKE '%" . $keyword . "%' OR arrivee LIKE '%" . $keyword . "%') AND effectue=". mysql_escape_string($effectue);
    }else{
        $sql_trajets = 'SELECT * FROM trajet WHERE effectue='. mysql_escape_string($effectue);
    }
        $query_trajets = mysql_query($sql_trajets) or die('Erreur SQL !<br />' . $sql_trajets . '<br />' . mysql_error());

    while ($row = mysql_fetch_assoc($query_trajets)) {

        //Récupère le login du conducteur 
        $sql_conducteur = 'SELECT login FROM membre WHERE id_membre=' . mysql_escape_string($row['id_membre']);
        $sql_conducteur = mysql_query($sql_conducteur) or die('Erreur SQL !<br />' . $sql_conducteur . '<br />' . mysql_error());
        $row_conducteur = mysql_fetch_assoc($sql_conducteur);

        //on affiche toutes les informations du trajets
        echo '<tr>';
        echo '<td>' . $row['id_trajet'] . '</td>';
        echo '<td>' . $row['depart'] . '</td>';
        echo '<td>' . $row['arrivee'] . '</td>';
        echo '<td>' . substr($row['jour'], 0, 2) . '/' . substr($row['jour'], 2, 2) .'/' . substr($row['jour'], 6, 2). '</td>';
        echo '<td>' . $row['heure'] . '</td>';
        echo '<td>' . $row['nb_place'] . '</td>';
        echo '<td>' . $row['prix'] . '</td>';
        echo '<td>' . $row['id_membre'] . '</td>';
        echo '<td>' . $row_conducteur['login'] . '</td>';

        // L'affichage comprend aussi les autres membres qui ont réservé des places sur les trajets
        $sql_id2 = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' . $row['id_trajet'] . '" AND conducteur = "0"';
        $query_id2 = mysql_query($sql_id2) or die('Erreur SQL !<br />' . $sql_id2 . '<br />' . mysql_error());

        echo '<td>';

        //affiche les passagers
        while ($row2 = mysql_fetch_array($query_id2)) {
            echo '<p>';
            echo $row2['id_membre'];
            $test[] = get_login_membre($row2['id_membre']);
        }
        echo '</td>';
        echo '<td>';

        foreach ($test as $value) {
            echo '<p>';
            echo $value;
        }
        echo '</td>';
        unset($test);
        echo '</tr>';
    }
}

//Connexion d'un membre
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

//Generation d'une liste de mois
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

//Generation d'une liste de jours
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

//Generation d'une liste d'années
function listAnnee() {
    $tab = array();
    for ($i = 2015; $i > 1900; $i--) {
        $tab[$i] = $i;
    }
    return $tab;
}

//Generation d'une liste d'années pour les trajets
function listAnneeTrajet() {
    $tab = array();
    for ($i = 2030; $i > 2000; $i--) {
        $tab[$i] = $i;
    }
    return $tab;
}

//Generation d'une liste de membres sans l'admin
function listMembres($login) {
    $sql = 'SELECT login FROM membre';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    while ($data = mysql_fetch_assoc($req)) {
        foreach ($data as $key => $value) {
            if ($value != 'admin' && $value != $login)
                $array[$value] = $value;
        }
    }
    return $array;
}

//Generation d'une liste d'heures
function listHeure() {
    $tab = array();
    for ($i = 0; $i < 24; $i++) {
        $tab[$i] = $i . " h";
    }
    return $tab;
}

//Generation d'une liste de notes
function listNote() {
    $tab = array();
    $tab[5] = "extraordinaire";
    $tab[4] = "excellent";
    $tab[3] = "bien";
    $tab[2] = "décevant";
    $tab[1] = "à éviter";
    return $tab;
}

//Select crée en PHP
function form_select_multiple($label, $name, $hashtable, $selected) {
    echo("<!-- form_select_multiple : $label $name-->\n");
    printf(" <select name='%s'>", $name);
    foreach ($hashtable as $key => $value) {
        if ($value == $selected) {
            printf(" <option value='%s' selected>%s</option>\n", $key, $value);
        } else {
            printf(" <option value='%s'>%s</option>\n", $key, $value);
        }
    }
    echo(" </select>");
}

//Insert vehicule dans la base
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

//Inscrit un membre
function inscription($fichiers, $post) {
    if ($fichiers['image']['error'] > 0)
        $erreur = "Erreur lors du transfert";
//Créer un dossier 'images/'
    mkdir('images/', 0777, true);

//Créer un identifiant difficile à deviner
//  $nom = md5(uniqid(rand(), true));
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

//Affiche les messages
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
        if ($data[1] == $id) {
            $sql_membre2 = 'SELECT login, image FROM membre WHERE id_membre=' . mysql_escape_string($data[0]);
            $fromto = "Reçu de";
        } else {
            $sql_membre2 = 'SELECT login, image FROM membre WHERE id_membre=' . mysql_escape_string($data[1]);
            $fromto = "Envoyé à";
        }
        $req_membre2 = mysql_query($sql_membre2) or die('Erreur SQL !<br />' . $sql_membre2 . '<br />' . mysql_error());
        $data2 = mysql_fetch_assoc($req_membre2);

        foreach ($data2 as $key => $value) {
            if ($key == 'login') {
                printf('<td>%s : %s</td>', $fromto, $value);
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

//Envoi un message
function envoi_message($login1, $login2, $message) {
    $id1 = get_id_membre($login1);
    $id2 = get_id_membre($login2);

    $sql = 'INSERT INTO message values ("",' . mysql_escape_string($id1) . ',' . mysql_escape_string($id2) . ',"' . mysql_escape_string($message) . '")';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    //header('Location: messagerie.php');
    //exit();
}

//Partie du formulaire insertion voiture
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

//Recherche de trajet
function recherche_trajet() {
    session_start();

    // On récupère l'ID du membre
    $id = get_id_membre($_SESSION['login']);
    
    if ($_POST['NoDate']=="NoDate"){
        if ( ($_POST['recherche_arrivee']=="Tout") && ($_POST['recherche_depart']=="Tout") ){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else if ($_POST['recherche_depart']=="Tout"){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND arrivee = "' . mysql_escape_string($_POST['recherche_arrivee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else if ($_POST['recherche_arrivee']=="Tout"){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND depart = "' . mysql_escape_string($_POST['recherche_depart'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else{
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND depart = "' . mysql_escape_string($_POST['recherche_depart'])
                    . '" AND arrivee = "' . mysql_escape_string($_POST['recherche_arrivee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
    }
    else {
        if ( ($_POST['recherche_arrivee']=="Tout") && ($_POST['recherche_depart']=="Tout") ){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND jour = "' . mysql_escape_string($_POST['recherche_jour']) . mysql_escape_string($_POST['recherche_mois']).mysql_escape_string($_POST['recherche_annee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else if ($_POST['recherche_depart']=="Tout"){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND arrivee = "' . mysql_escape_string($_POST['recherche_arrivee'])
                    . '" AND jour = "' . mysql_escape_string($_POST['recherche_jour']) . mysql_escape_string($_POST['recherche_mois']).mysql_escape_string($_POST['recherche_annee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else if ($_POST['recherche_arrivee']=="Tout"){
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND depart = "' . mysql_escape_string($_POST['recherche_depart'])
                    . '" AND jour = "' . mysql_escape_string($_POST['recherche_jour']) . mysql_escape_string($_POST['recherche_mois']).mysql_escape_string($_POST['recherche_annee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
        else{
            $sql_recherche = 'SELECT * FROM trajet WHERE id_membre != "' . mysql_escape_string($id)
                    . '" AND depart = "' . mysql_escape_string($_POST['recherche_depart'])
                    . '" AND arrivee = "' . mysql_escape_string($_POST['recherche_arrivee'])
                    . '" AND jour = "' . mysql_escape_string($_POST['recherche_jour']) . mysql_escape_string($_POST['recherche_mois']).mysql_escape_string($_POST['recherche_annee'])
                    . '" AND nb_place > 0 AND effectue = 0 ORDER BY heure ASC';
        }
    }

    $query_recherche = mysql_query($sql_recherche) or die('Erreur SQL !<br />' . $sql_recherche . '<br />' . mysql_error());
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
            <th>    </th>
        </tr>
        <form action="reservation_trajet.php" method="post" enctype="multipart/form-data">
            <?php
            while ($row = mysql_fetch_array($query_recherche)) {

                echo '<tr>';
                echo '<td>' . $row['id_trajet'] . '</td>';
                echo '<td>' . $row['depart'] . '</td>';
                echo '<td>' . $row['arrivee'] . '</td>';
                echo '<td>' . substr($row['jour'], 0, 2) . '/' . substr($row['jour'], 2, 2) .'/' .substr($row['jour'], 6, 2). '</td>';
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

//Insertion d'un trajet
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
            . '", "' . mysql_escape_string($_POST['jour']) . mysql_real_escape_string($_POST['mois']).mysql_real_escape_string($_POST['annee'])
            . '", "' . mysql_escape_string($_POST['heure'])
            . '", "' . mysql_escape_string($_POST['nb_place'])
            . '", "' . mysql_escape_string($_POST['prix'])
            . '","")';
    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());

    $sql = 'SELECT id_trajet FROM trajet WHERE id_membre = "' . mysql_escape_string($id)
            . '" AND depart = "' . mysql_escape_string($_POST['depart'])
            . '" AND arrivee = "' . mysql_escape_string($_POST['arrivee'])
            . '" AND jour = "' . mysql_escape_string($_POST['jour']) . mysql_real_escape_string($_POST['mois']).mysql_real_escape_string($_POST['annee'])
            . '" AND heure = "' . mysql_escape_string($_POST['heure'])
            . '" AND nb_place = "' . mysql_escape_string($_POST['nb_place'])
            . '" AND prix = "' . mysql_escape_string($_POST['prix']) . '"';
    $query_id = mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());
    $data_id = mysql_fetch_array($query_id);
    $id_trajet = $data_id[0];

    $sql_id = 'INSERT INTO pres_trajet VALUES ("' . $id_trajet . '","' . $id . '", "1",' . mysql_real_escape_string($_POST['prix'] . ')');
    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());

    header('Location: membre.php');
    exit();
}

//Récuperation du départ
function get_depart($login) {

    $id_membre = get_id_membre($login);

    $sql_depart = 'SELECT DISTINCT depart FROM trajet WHERE id_membre != ' . $id_membre . ' ORDER BY depart ASC';
    $req_depart = mysql_query($sql_depart) or die('Erreur SQL !<br />' . $sql_depart . '<br />' . mysql_error());

    echo ("<select name='recherche_depart'>");
    echo ("<option value='Tout'>Tout</option>\n");
    while ($row = mysql_fetch_array($req_depart)) {
        echo ("<option value=" . $row['depart'] . ">" . $row['depart'] . "</option>\n");
    }
    echo '</select>';
}

//Récuperation de l'arrivée
function get_arrivee($login) {

    $id_membre = get_id_membre($login);

    $sql_arrivee = 'SELECT DISTINCT arrivee FROM trajet WHERE id_membre != ' . $id_membre . ' ORDER BY arrivee ASC';
    $req_arrivee = mysql_query($sql_arrivee) or die('Erreur SQL !<br />' . $sql_arrivee . '<br />' . mysql_error());

    echo ("<select name='recherche_arrivee'>");
    echo ("<option value='Tout'>Tout</option>\n");
    while ($row = mysql_fetch_array($req_arrivee)) {
        echo ("<option value=" . $row['arrivee'] . ">" . $row['arrivee'] . "</option>\n");
    }
    echo '</select>';
}

//Récupération de la note
function get_note($login) {
    $id_membre = get_id_membre($login);

    $sql_image = 'SELECT note FROM membre WHERE id_membre=' . $id_membre;
    $req_image = mysql_query($sql_image) or die('Erreur SQL !<br />' . $sql_image . '<br />' . mysql_error());
    $data = mysql_fetch_array($req_image);
    return $data[0];
}

//Récupération de l'argent
function get_argent($login) {
    $id_membre = get_id_membre($login);

    $sql_image = 'SELECT argent FROM membre WHERE id_membre=' . $id_membre;
    $req_image = mysql_query($sql_image) or die('Erreur SQL !<br />' . $sql_image . '<br />' . mysql_error());
    $data = mysql_fetch_array($req_image);
    return $data[0];
}

//Notation d'un membre
function noter_membre($loginnoteur, $loginnote, $note, $trajet) {
    $id = get_id_membre($loginnoteur);
    $id2 = get_id_membre($loginnote);

    //insertion de la note dans la table note_trajet
    $sql = 'INSERT INTO note_trajet values ("",' . mysql_escape_string($trajet) . ',' . mysql_escape_string($id) . ',' . mysql_escape_string($id2) . ',' . mysql_escape_string($note) . ')';
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    //MAJ de la note du membre à partir de ses notes dans la table note_trajet
    $sql = 'UPDATE membre SET note = (SELECT AVG(note) FROM note_trajet WHERE id_note=' . mysql_escape_string($id2) . ') WHERE id_membre=' . mysql_escape_string($id2);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    header('Location: preparation_trajet.php');
    exit();
}

//Récupération du conducteur
function get_conducteur($id_trajet) {
    $sql_conducteur = 'SELECT id_membre FROM pres_trajet WHERE id_trajet=' . $id_trajet . ' AND conducteur=true;';
    $req_conducteur = mysql_query($sql_conducteur) or die('Erreur SQL !<br />' . $sql_conducteur . '<br />' . mysql_error());
    $id_membre = mysql_fetch_array($req_conducteur);
    return $id_membre[0];
}

//MAJ des soldes d'argent
function payer($login_conducteur, $id_trajet) {
    $id_conducteur = get_id_membre($login_conducteur);
    $argent_totale=0;
    
    //récupère le prix d'une place
    $sql_argent = 'SELECT prix FROM trajet WHERE id_trajet=' . $id_trajet;
    $req_argent = mysql_query($sql_argent) or die('Erreur SQL !<br />' . $sql_argent . '<br />' . mysql_error());
    $prix = mysql_fetch_array($req_argent)[0];

    //Récupère chaque membre du trajet
    $sql_membre = 'SELECT id_membre FROM pres_trajet WHERE id_trajet=' . $id_trajet.' AND conducteur=0';
    $req_membre = mysql_query($sql_membre) or die('Erreur SQL !<br />' . $sql_membre . '<br />' . mysql_error());
    while ($row = mysql_fetch_array($req_membre)) {
        //récupère le nombre de places achetées par le membre
        $nb_places = nb_places_achetees($row[0], $id_trajet);
        $argent = $prix * $nb_places;
        
        //modification du solde du passager
        $sql = 'UPDATE membre SET argent=argent-'.mysql_escape_string($argent) . ' WHERE id_membre=' . mysql_escape_string($row[0]);
        $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
        
        $argent_totale = $argent_totale + $argent;
    }
    
    //modification du solde du conducteur
    $sql_conduc = 'UPDATE membre SET argent=argent+' . mysql_escape_string($argent_totale) . ' WHERE id_membre=' . mysql_escape_string($id_conducteur);
    $req_conduc = mysql_query($sql_conduc) or die('Erreur SQL !<br />' . $sql_conduc . '<br />' . mysql_error());
    
    //Met le trajet à effectué
    trajet_effectue($id_trajet);
}

//Met le trajet comme étant effectué
function trajet_effectue($id_trajet){
    $sql = 'UPDATE trajet SET effectue=true WHERE id_trajet=' . mysql_escape_string($id_trajet);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
}

//Verifie si un trajet un trajet est deja effectué
function deja_effectue($id_trajet){
    $sql = 'SELECT effectue FROM trajet WHERE id_trajet=' . mysql_escape_string($id_trajet);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    $row = mysql_fetch_array($req);
    if ($row[0] == 0) {
        return false;
    } else {
        return true;
    }
}

//Incrémente l'argent
function rembourser_passager($id_membre, $id_trajet) {
    //récupère le prix d'une place
    $sql_argent = 'SELECT prix FROM trajet WHERE id_trajet=' . $id_trajet;
    $req_argent = mysql_query($sql_argent) or die('Erreur SQL !<br />' . $sql_argent . '<br />' . mysql_error());
    $prix = mysql_fetch_array($req_argent)[0];

    //récupère le nombre de places achetées
    $nb_places = nb_places_achetees($id_membre, $id_trajet);

    $prix_total = ($prix * $nb_places) + 10;

    $sql = 'UPDATE membre SET argent=argent+' . mysql_escape_string($prix_total) . ' WHERE id_membre=' . mysql_escape_string($id_membre);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());

    return $prix_total;
}

//Décrémente l'argent
function rembourser_trajet($login_conducteur, $id_trajet) {
    $id = get_id_membre($login_conducteur);  
    //argent a deduire du compte du conducteur
    $argent = 0;

    $sql_membre = 'SELECT id_membre FROM pres_trajet WHERE id_trajet=' . $id_trajet . ' AND conducteur=false';
    $req_membre = mysql_query($sql_membre) or die('Erreur SQL !<br />' . $sql_membre . '<br />' . mysql_error());

    //Test si des membres sont inscrits sur le trajet
    if (mysql_num_rows($req_membre) != 0) {
        while ($data = mysql_fetch_assoc($req_membre)) {
            foreach ($data as $key => $value) {
                $argent += rembourser_passager($value, $id_trajet);
            }
        }

        //modification du solde du conducteur
        $sql = 'UPDATE membre SET argent=argent-' . mysql_escape_string($argent) . ' WHERE id_membre=' . mysql_escape_string($id);
        $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    }
}

//Verifie si un membre est deja noté par rapport à un trajet
function deja_note($login_note, $login_noteur, $id_trajet) {
    $id_noteur = get_id_membre($login_noteur);
    $id_note = get_id_membre($login_note);
    $sql = 'SELECT note FROM note_trajet WHERE id_trajet=' . mysql_escape_string($id_trajet) .
            ' AND id_noteur=' . mysql_escape_string($id_noteur) .
            ' AND id_note=' . mysql_escape_string($id_note);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    if (mysql_num_rows($req) == 0) {
        return false;
    } else {
        return true;
    }
}

//Récupère le nombre de places achetées
function nombres_places_reserves($login, $id_trajet) {
    $id_membre = get_id_membre($login);

    $places = nb_places_achetees($id_membre,$id_trajet);
    return $places;
}

//Récupère le nombre de places achetées
function nb_places_achetees($id_membre,$id_trajet){
    $sql = 'SELECT nb_places FROM pres_trajet WHERE id_membre=' . mysql_escape_string($id_membre) . ' AND id_trajet=' . mysql_escape_string($id_trajet);
    $req = mysql_query($sql) or die('Erreur SQL !<br />' . $sql . '<br />' . mysql_error());
    $data = mysql_fetch_array($req);

    return $data[0];
}

//Affiche les trajets du conducteur
function afficher_trajet_du_conducteur($login) {
    print <<<END
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
            <th>Nombre de place(s) restante(s)</th>
            <th>Prix</th>
            <th>Membres Inscrits</th>
            <th>Nombre de place(s) réservée(s)</th>
            <th>Noter</th>
            <th>Valider</th>
            <th>Supprimer</th>
          </tr>                               
END;

    printf('<tr>');
    // On cherche tous les trajets où le membre de la session active conduit (cad les trajets qu'il a proposé)
    $sql_id = 'SELECT * FROM trajet WHERE id_membre="' . get_id_membre($login) . '"';
    $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());

    //puis on les affiche
    while ($row = mysql_fetch_array($query_id)) {
        echo '<tr>';
        echo '<td>' . $row['id_trajet'] . '</td>';
        echo '<td>' . $row['depart'] . '</td>';
        echo '<td>' . $row['arrivee'] . '</td>';
        echo '<td>' . substr($row['jour'], 0, 2) . '/' . substr($row['jour'], 2, 2) . '/'. substr($row['jour'], 6, 2) . '</td>';
        echo '<td>' . $row['heure'] . '</td>';
        echo '<td>' . $row['nb_place'] . '</td>';
        echo '<td>' . $row['prix'] . '</td>';

        // L'affichage comprend aussi les autres membres qui ont réservé des places sur les trajets
        $sql_id2 = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' . $row['id_trajet'] . '" AND conducteur = "0"';
        $query_id2 = mysql_query($sql_id2) or die('Erreur SQL !<br />' . $sql_id2 . '<br />' . mysql_error());
        echo '<td>';

        //Affichage d'un bouton par passager permettant de lui envoyer un message
        echo '<div class="btn-group-vertical">';
        echo '<form action="messagerie.php" method="post" enctype="multipart/form-data">';
        while ($row2 = mysql_fetch_array($query_id2)) {
            echo '<button class="btn btn-info btn-sm" style="width: 100%;" type="submit" name="message_trajet" value="' . get_login_membre($row2['id_membre']) . '">' . get_login_membre($row2['id_membre']) . '</button>';
            $login_membres[] = get_login_membre($row2['id_membre']);
        }
        echo '</form>';
        echo '</div>';
        echo'</td>';

        //affiche le nombre de places réservées
        echo '<td>';
        foreach ($login_membres as $key => $value) {
            echo nombres_places_reserves($value, $row['id_trajet']);
            echo '<p/><p/>';
        }
        echo '</td>';

        //Affichage d'un bouton pour noter le passager
        echo '<td>';
        foreach ($login_membres as $value) {
            if (deja_note($value, $login, $row['id_trajet'])) {
                echo 'Deja noté <p/><p/>';
            } else {
                echo '<form action="preparation_trajet.php" method="post" enctype="multipart/form-data">';
                form_select_multiple(note, note, listNote());
                echo '<input type="hidden" name="id_trajet" value=' . $row['id_trajet'] . '>';
                echo '<button class="btn btn-primary btn-xs" type="submit" name="Noter" value="' . $value . '">Noter</button>';
                echo'</form>';
            }
        }
        unset($login_membres);
        echo'</td>';

        //bouton Valider
        if (deja_effectue($row['id_trajet'])) {
                echo '<td><button type="button" class="btn btn-info disabled">Trajet effectué</button></td>';
            }
            else {
                echo'<form action="preparation_trajet.php" method="post" enctype="multipart/form-data">';
                echo ("<td><button class='btn btn-primary center-block' type='submit' name='valider_trajet' value=" . $row['id_trajet'] . ">Valider Trajet </button>");
                echo'</form></td>';
            }
        
        //bouton supprimer
            if (deja_effectue($row['id_trajet'])) {
                echo '<td><button type="button" class="btn btn-info disabled">Trajet effectué</button></td>';
            }
            else {
                echo'<form action="suppression_trajet.php" method="post" enctype="multipart/form-data">';
                echo "<input type='hidden' name='id_trajet' value='" . $row['id_trajet'] . "'>";
                echo ("<td><button class='btn btn-primary center-block' type='submit' name='supprimer_trajet' value=" . $row['id_trajet'] . ">Supprimer Trajet </button>");
                echo'</form></td>';
                echo '</tr>';
            }
        
    }
    echo '</table>';
}

//Affiche les trajets des passagers
function afficher_trajet_passager($login) {
    print <<<END
    <h3> Les trajets où vous êtes passager : <h3>                                                            
    <table class="table table-stripped">
        <!-- Tête du tableau-->
          <tr>
            <th>ID</th>
            <th>Depart</th>
            <th>Arrivée</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Nombre de place(s) restante(s)</th>
            <th>Prix</th>
            <th>Conducteur</th>
            <th>Noter</th>
    </tr>                               
END;

    //On cherche les id des trajets ou le membre est présent mais ne conduit pas
    $sql_id_trajet = 'SELECT DISTINCT id_trajet FROM pres_trajet WHERE id_membre="' . get_id_membre($login) . '" AND conducteur="0"';
    $query_id_trajet = mysql_query($sql_id_trajet) or die('Erreur SQL !<br />' . $sql_id_trajet . '<br />' . mysql_error());

    while ($row = mysql_fetch_array($query_id_trajet)) {
        // On affiche a partir de ces id les trajets en question
        $sql_id = 'SELECT * FROM trajet WHERE id_trajet="' . $row['id_trajet'] . '"';
        $query_id = mysql_query($sql_id) or die('Erreur SQL !<br />' . $sql_id . '<br />' . mysql_error());


        while ($row2 = mysql_fetch_array($query_id)) {
            echo '<tr>';
            echo '<td>' . $row2['id_trajet'] . '</td>';
            echo '<td>' . $row2['depart'] . '</td>';
            echo '<td>' . $row2['arrivee'] . '</td>';
            echo '<td>' . substr($row2['jour'], 0, 2) . '/' . substr($row2['jour'], 2, 2) . '/' . substr($row2['jour'], 6, 2). '</td>';
            echo '<td>' . $row2['heure'] . '</td>';
            echo '<td>' . $row2['nb_place'] . '</td>';
            echo '<td>' . $row2['prix'] . '</td>';

            // L'affichage comprend aussi le conducteur
            $sql_id2 = 'SELECT DISTINCT id_membre FROM pres_trajet WHERE id_trajet="' . $row['id_trajet'] . '" AND conducteur = "1"';
            $query_id2 = mysql_query($sql_id2) or die('Erreur SQL !<br />' . $sql_id2 . '<br />' . mysql_error());
            echo '<td>';
            echo '<div class="btn-group-vertical">';
            echo '<form action="messagerie.php" method="post" enctype="multipart/form-data">';

            while ($row3 = mysql_fetch_array($query_id2)) {
                echo '<button class="btn btn-info btn-sm" type="submit" name="message_trajet" value="' . get_login_membre($row3['id_membre']) . '">' . get_login_membre($row3['id_membre']) . '</button>';
                $login_membres[] = get_login_membre($row2['id_membre']);
            }
            echo '</form>';

            echo'</td>';
            echo '<td>';

            //Affichage d'un bouton pour noter le conducteur
            foreach ($login_membres as $value) {
                if (deja_note($value, $login, $row['id_trajet'])) {
                    echo 'Deja noté <p/>';
                } else {
                    echo '<form action="preparation_trajet.php" method="post" enctype="multipart/form-data">';
                    form_select_multiple(note, note, listNote());
                    echo '<input type="hidden" name="id_trajet" value=' . $row['id_trajet'] . '>';
                    echo '<button class="btn btn-group-sm btn-primary" type="submit" name="Noter" value="' . $value . '">Noter</button>';
                    echo'</form>';
                }
            }
            unset($login_membres);
            echo'</td>';
            
            echo '</tr>';
        }
    }

    echo '</table>';

    if (isset($erreur))
        echo '<br />', $erreur;
}

function inscription_page($post,$files){
                if ($files['image']['error'] > 0)
                $erreur = "Erreur lors du transfert";
//Créer un dossier 'images/'
            mkdir('images/', 0777, true);

//Créer un identifiant difficile à deviner
//          $nom = md5(uniqid(rand(), true));
//deplacer le fichier
            $nom = $files["image"]["name"];
            $chemin = "images/" . $nom;
            $tmpLoc = $files["image"]["tmp_name"];
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
                        . '","","'
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