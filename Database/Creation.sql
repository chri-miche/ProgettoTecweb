
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