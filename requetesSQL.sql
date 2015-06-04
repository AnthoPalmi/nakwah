CREATE TABLE membre (
	id_membre INT(10) NOT NULL auto_increment,
	login VARCHAR(20) NOT NULL,
	pass VARCHAR(20) NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	naissance INT(8) NOT NULL,
        argent INT(4) NOT NULL DEFAULT 0,
        note INT(2) NOT NULL DEFAULT 0,
	image TEXT,
	PRIMARY KEY  (id_membre)
);

CREATE TABLE voiture (
	id_voiture INT(10) NOT NULL auto_increment,
	id_membre INT(10) NOT NULL UNIQUE,
	marque VARCHAR(20) NOT NULL,
	modele VARCHAR(20) NOT NULL,
	couleur VARCHAR(50) NOT NULL,
	annee VARCHAR(4) NOT NULL,
	PRIMARY KEY  (id_voiture),
	CONSTRAINT PROPRIETAIRE_FK 
		FOREIGN KEY (id_membre) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE trajet (
	id_trajet INT(10) NOT NULL auto_increment,
	id_membre INT(10) NOT NULL,
	depart VARCHAR(20) NOT NULL,
	arrivee VARCHAR(20) NOT NULL,
	jour VARCHAR(4) NOT NULL,
	heure VARCHAR(2) NOT NULL,
	nb_place INT(2) NOT NULL,
	prix INT(2) NOT NULL,
	effectue BOOLEAN DEFAULT false,
	PRIMARY KEY  (id_trajet),
	CONSTRAINT PROPRIETAIRE_FK2 
		FOREIGN KEY (id_membre) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE message (
	id_message INT(10) NOT NULL auto_increment,
	id_membre1 INT(10) NOT NULL,
	id_membre2 INT(10) NOT NULL,
	texte VARCHAR(200) NOT NULL,
	PRIMARY KEY  (id_message),
	CONSTRAINT USER1_FK 
		FOREIGN KEY (id_membre1) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT USER2_FK 
		FOREIGN KEY (id_membre2) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE pres_trajet (
	id_trajet INT(10) NOT NULL,
	id_membre INT(10) NOT NULL,
	conducteur BOOLEAN NOT NULL DEFAULT false,
        nb_places INT(2) NOT NULL,
	CONSTRAINT TRAJET_FK 
		FOREIGN KEY (id_trajet) 
		REFERENCES trajet(id_trajet)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT MEMBRE_FK 
		FOREIGN KEY (id_membre) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

CREATE TABLE note_trajet (
        id_note_trajet INT(10) NOT NULL auto_increment,
        id_trajet INT(10) NOT NULL,
	id_noteur INT(10) NOT NULL,
	id_note INT(10) NOT NULL,
	note INT(2) NOT NULL DEFAULT 0,
        PRIMARY KEY  (id_note_trajet),
	CONSTRAINT notetrajet_FK 
		FOREIGN KEY (id_trajet) 
		REFERENCES trajet(id_trajet)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT noteur_FK 
		FOREIGN KEY (id_noteur) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT note_FK 
		FOREIGN KEY (id_note) 
		REFERENCES membre(id_membre)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

-- INSERT INTO membre values ("","admin","4dm1n","admin","admin","01011990","");
INSERT INTO `membre` (`id_membre`, `login`, `pass`, `nom`, `prenom`, `naissance`, `image`) VALUES
(1, 'admin', '4dm1n', 'admin', 'admin', 1011990, ''),
(12, 'test', 'test', 'Jacques', 'Test', 1012005, 'images/1428607024785 2.jpg'),
(13, 'a', 'a', 'Chirac', 'Jacques', 1012006, 'images/ChiracUSA.jpg'),
(14, 'Kim', 'kim', 'Berley', 'Kim', 1012008, 'images/avatr.jpg');
