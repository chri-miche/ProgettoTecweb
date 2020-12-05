
    /* Jacopo Fichera - 05/12/2020.
       Creazione del database. Rilascio delle possibli tabelle.
       Pirma vengono droppate le entità più deboli.*/


    drop table if exists CertificatoUtente;
    drop table if exists Certificato;
    drop table if exists EnteRiconosciuto;

    drop table if exists Notifica;
    drop table if exists Commento;

    drop table if exists ImmaginiPost;
    drop table if exists Citazione;
    drop table if exists Post;

    drop table if exists Segnalazione;
    drop table if exists Approvazione;

    drop table if exists Contenuto;

    drop table if exists Seguito;
    drop table if exists Interesse;
    drop table if exists Moderatore;
    drop table if exists Utente;

    drop table if exists Residenza;
    drop table if exists ZonaGeografica;

    drop table if exists Uccello;
    drop table if exists Famiglia;
    drop table if exists Ordine;
    drop table if exists SuperOrdine;

    drop table if exists Tag;
    drop table if exists Label;


    create table EnteRiconosciuto(
        ID INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        Nome VARCHAR(30) NOT NULL UNIQUE
    ) ENGINE = InnoDB;

    create table Certificato(
        ID INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

        EnteID INT UNSIGNED NOT NULL,
        FOREIGN KEY  (EnteID) REFERENCES EnteRiconosciuto(ID)
    ) ENGINE = InnoDB;

    create table Utente(
        ID INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(25) NOT NULL ,

        email VARCHAR(40) NOT NULL ,
        password VARCHAR(14) NOT NULL
    ) engine = InnoDB;

    create table Moderatore(
        UserID INT UNSIGNED NOT NULL PRIMARY KEY,
        isAdmin BOOLEAN NOT NULL,

        FOREIGN KEY (UserID) REFERENCES Utente(ID)
    ) engine = InnoDB;

    create table CertificatoUtente(

        UserID INT UNSIGNED NOT NULL PRIMARY KEY,
        CertID INT UNSIGNED NOT NULL PRIMARY KEY,

        ModID INT UNSIGNED NOT NULL ,

        CONSTRAINT CertificatoKey PRIMARY KEY (UserID,CertID),
        FOREIGN KEY (UserID) REFERENCES Utente(ID),
        FOREIGN KEY (CertID) REFERENCES Certificato(ID),

        FOREIGN KEY (ModID) REFERENCES Moderatore(UserID)
    ) engine InnoDB;

    /** Sistemare un po' i nomi, non troppo chiari.*/
    create table Seguito(
        SeguitoID INT UNSIGNED NOT NULL,
        SeguaceID INT UNSIGNED NOT NULL,

        CONSTRAINT SeguitoKey PRIMARY KEY (SeguitoID,SeguaceID),

        FOREIGN KEY (SeguaceID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (SeguitoID) REFERENCES Utente(ID) ON DELETE CASCADE
    ) engine = InnoDB;

    create table Label( /* Etttichetta (relativa a uno o molti tag)*/
        ID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        text VARCHAR(255) NOT NULL
    ) engine = InnoDB;

    create table Tag(
        ID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(20) UNIQUE NOT NULL,

        LabelID INT UNSIGNED,
        FOREIGN KEY (LabelID) REFERENCES Label(ID) ON DELETE SET NULL
    ) engine = InnoDB;

    /* Elemetni degli uccelli.*/

    create table Ordine(
        TagID INT UNSIGNED NOT NULL PRIMARY KEY,
        nomeScientifico VARCHAR(40) NOT NULL UNIQUE,

        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE
    ) engine = InnoDB;


    create table Famiglia(
        TagID INT UNSIGNED NOT NULL PRIMARY KEY,
        OrdID INT UNSIGNED NOT NULL PRIMARY KEY,

        nomeScientifico VARCHAR(40) NOT NULL,
        caratteristicheComuni TEXT,

        /** Se elimino dal sistema un ordine elimino anche tutte le famiglie
            ad esso relativo. Mi pare giusto così.*/
        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE ,
        FOREIGN KEY (OrdID) REFERENCES Ordine(TagID) ON DELETE CASCADE

    ) engine = InnoDB;


    create table Genere(
        tagID INT UNSIGNED NOT NULL PRIMARY KEY,
        famID INT UNSIGNED NOT NULL,

        nomeScientifico VARCHAR(40) NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID) ON DELETE CASCADE ,
        FOREIGN KEY (famID) REFERENCES Ordine(TagID) ON DELETE CASCADE

    ) engine = InnoDB;

    create table Conservazione(
        codice varchar(2) NOT NULL PRIMARY KEY,

        nome varchar(20) NOT NULL UNIQUE,
        probEstinzione integer

    ) engine = InnoDB;

    create table Specie(
        tagID int UNSIGNED NOT NULL PRIMARY KEY,
        genID int UNSIGNED NOT NULL,

        nomeScientifico varchar(40) NOT NULL,
        percorsoImmagine varchar(80) NOT NULL,

        conservazioneID varchar(2) NOT NULL,

        pesoMedio int UNSIGNED NOT NULL,
        altezzaMedia int UNSIGNED NOT NULL,
        descrizione text NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID),
        FOREIGN KEY (genID) REFERENCES Genere(tagID) ON DELETE CASCADE,

        FOREIGN KEY (conservazioneID) references Conservazione(Codice) ON DELETE RESTRICT
    ) engine = InnoDB;


    create table ZonaGeografica(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,

        nome varchar(40) NOT NULL,
        continente enum('Africa','America del nord', 'Sud America',
            'Asia', 'Europa', 'Oceania', 'Antartide'),

        FOREIGN KEY (tagID) REFERENCES Tag(ID) ON DELETE CASCADE
    ) engine = InnoDB;

    create table Residenza(

        specieID int UNSIGNED NOT NULL PRIMARY KEY,
        zonaID int UNSIGNED NOT NULL PRIMARY KEY,

        periodoInizio date NOT NULL,
        periodoFine date NOT NULL CHECK ( periodoFine > periodoInizio ) /* Data fine deve venire dopo di inizio*/

    ) engine = InnoDB;