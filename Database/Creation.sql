
/* Jacopo Fichera - 05/12/2020.
   Creazione del database. Rilascio delle possibli tabelle.
   (Pirma vengono droppate le entità più deboli).*/

    DROP TABLE IF EXISTS CertificatoUtente;
    DROP TABLE IF EXISTS Certificato;
    DROP TABLE IF EXISTS EnteRiconosciuto;

    DROP TABLE IF EXISTS Notifica;
    DROP TABLE IF EXISTS Commento;

    DROP TABLE IF EXISTS ImmaginiPost;
    DROP TABLE IF EXISTS Citazione;
    DROP TABLE IF EXISTS Post;

    DROP TABLE IF EXISTS Segnalazione;
    DROP TABLE IF EXISTS Approvazione;

    DROP TABLE IF EXISTS Contenuto;

    DROP TABLE IF EXISTS Seguito;
    DROP TABLE IF EXISTS interesse;
    DROP TABLE IF EXISTS Moderatore;
    DROP TABLE IF EXISTS Utente;

    DROP TABLE IF EXISTS Residenza;
    DROP TABLE IF EXISTS ZonaGeografica;

    DROP TABLE IF EXISTS Specie;
    DROP TABLE IF EXISTS Genere;
    DROP TABLE IF EXISTS Famiglia;
    DROP TABLE IF EXISTS Ordine;


    DROP TABLE IF EXISTS Conservazione;

    DROP TABLE IF EXISTS Tag;
    DROP TABLE IF EXISTS Label;


    CREATE TABLE EnteRiconosciuto(

        ID int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        Nome varchar(30) NOT NULL UNIQUE

    ) ENGINE = InnoDB;


    CREATE TABLE Certificato(

        ID int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,

        EnteID int UNSIGNED NOT NULL,
        FOREIGN KEY  (EnteID) REFERENCES EnteRiconosciuto(ID)

    ) ENGINE = InnoDB;


    CREATE TABLE Utente(

        ID int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(25) NOT NULL,

        email VARCHAR(40) NOT NULL UNIQUE,
        password VARCHAR(14) NOT NULL,

        immagineProfilo varchar(40) NOT NULL DEFAULT ('imgs/default.png')

    ) ENGINE = InnoDB;


    CREATE TABLE Moderatore(

        UserID int UNSIGNED NOT NULL PRIMARY KEY,
        isAdmin BOOLEAN NOT NULL,

        FOREIGN KEY (UserID) REFERENCES Utente(ID)

    ) ENGINE = InnoDB;


    CREATE TABLE CertificatoUtente(

        UserID int UNSIGNED NOT NULL,
        CertID int UNSIGNED NOT NULL,

        ModID int UNSIGNED NOT NULL,

        CONSTRAINT CertificatoKey PRIMARY KEY (UserID,CertID),
        FOREIGN KEY (UserID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (CertID) REFERENCES Certificato(ID) ON DELETE CASCADE,

        FOREIGN KEY (ModID) REFERENCES Moderatore(UserID)

    ) ENGINE InnoDB;


    /** Sistemare un po' i nomi, non troppo chiari.*/
    CREATE TABLE Seguito(

        SeguitoID int UNSIGNED NOT NULL,
        SeguaceID int UNSIGNED NOT NULL,

        CONSTRAint SeguitoKey PRIMARY KEY (SeguitoID,SeguaceID),

        FOREIGN KEY (SeguaceID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (SeguitoID) REFERENCES Utente(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Label( /* Etttichetta (relativa a uno o molti tag)*/

        ID int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        text VARCHAR(255) NOT NULL

    ) ENGINE = InnoDB;


    CREATE TABLE Tag(

        ID int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(40) UNIQUE NOT NULL,

        LabelID int UNSIGNED,
        FOREIGN KEY (LabelID) REFERENCES Label(ID) ON DELETE SET NULL

    ) ENGINE = InnoDB;



    CREATE TABLE Ordine(

        TagID int UNSIGNED NOT NULL PRIMARY KEY,
        nomeScientifico VARCHAR(40) NOT NULL UNIQUE,

        FOREIGN KEY (TagID) REFERENCES Tag(ID)

    ) ENGINE = InnoDB;


    CREATE TABLE Famiglia(

        TagID int UNSIGNED NOT NULL PRIMARY KEY,
        OrdID int UNSIGNED NOT NULL,

        nomeScientifico VARCHAR(40) NOT NULL,
        caratteristicheComuni TEXT,

        /** Se elimino dal sistema un ordine elimino anche tutte le famiglie
            ad esso relativo. Mi pare giusto così.*/
        FOREIGN KEY (TagID) REFERENCES Tag(ID),
        FOREIGN KEY (OrdID) REFERENCES Ordine(TagID)

    ) ENGINE = InnoDB;


    CREATE TABLE Genere(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,
        famID int UNSIGNED NOT NULL,

        nomeScientifico VARCHAR(40) NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID),
        FOREIGN KEY (famID) REFERENCES Famiglia(TagID)

    ) ENGINE = InnoDB;


    CREATE TABLE Conservazione(

        codice varchar(2) NOT NULL PRIMARY KEY,

        nome varchar(20) NOT NULL UNIQUE,
        probEstinzione int,
        descrizione text

    ) ENGINE = InnoDB;

    CREATE TABLE Specie(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,
        genID int UNSIGNED NOT NULL,

        nomeScientifico varchar(40) NOT NULL,
        percorsoImmagine varchar(80) NOT NULL,

        conservazioneID varchar(2) NOT NULL,

        pesoMedio int UNSIGNED NOT NULL,
        altezzaMedia int UNSIGNED NOT NULL,
        descrizione text NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID),
        FOREIGN KEY (genID) REFERENCES Genere(tagID),
        FOREIGN KEY (conservazioneID) REFERENCES Conservazione(Codice)

    ) ENGINE = InnoDB;


    CREATE TABLE ZonaGeografica(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,

        nome varchar(40) NOT NULL,
        continente enum('Africa','America del nord', 'Sud America',
            'Asia', 'Europa', 'Oceania', 'Antartide'),

        FOREIGN KEY (tagID) REFERENCES Tag(ID)

    ) ENGINE = InnoDB;


    CREATE TABLE Residenza(

        specieID int UNSIGNED NOT NULL,
        zonaID int UNSIGNED NOT NULL,

        periodoInizio date NOT NULL,
        periodoFine date NOT NULL CHECK ( periodoFine > periodoInizio ), /* Data fine deve venire dopo di inizio*/

        CONSTRAint residenzaID PRIMARY KEY (specieID,zonaID),

        FOREIGN KEY (specieID) REFERENCES Specie(tagID) ON DELETE CASCADE,
        FOREIGN KEY (zonaID) REFERENCES ZonaGeografica(tagID)

    ) ENGINE = InnoDB;


    CREATE TABLE Contenuto(

        ID int UNSIGNED NOT NULL PRIMARY KEY,
        UserID int UNSIGNED NOT NULL, /* Primo utente in Utenti sarà deleted.*/

        isArchived bool NOT NULL,
        content text NOT NULL,
        data date NOT NULL,

        FOREIGN KEY (UserID) REFERENCES Utente(ID)

    ) ENGINE = InnoDB;


    CREATE TABLE Approvazione(

        utenteID int UNSIGNED NOT NULL,
        contentID int UNSIGNED NOT NULL,

        likes bool NOT NULL,

        CONSTRAint approvazioneID PRIMARY KEY (utenteID,contentID),

        FOREIGN KEY (utenteID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (contentID) REFERENCES Contenuto(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Segnalazione(

        contentID int UNSIGNED NOT NULL,
        utenteID int UNSIGNED NOT NULL,

        modResponsabile int UNSIGNED NOT NULL,

        causale text,
        elaborato bool NOT NULL default (false),

        CONSTRAint segnalazioneID PRIMARY KEY (contentID,utenteID),

        /* Se elimino il post o l'utente la segnalazione va eliminata, non è oiù rilevante. */
        FOREIGN KEY (contentID) REFERENCES Contenuto(ID) ON DELETE CASCADE ,
        FOREIGN KEY (utenteID) REFERENCES Utente(ID) ON DELETE CASCADE,
        /* Non è possibile eliminare il moderatore responsabile se ha ancora Segnalazioni
           da gestire, quindi default behaviour. */
        FOREIGN KEY  (modResponsabile) REFERENCES Moderatore(UserID)

    ) ENGINE = InnoDB;


    CREATE TABLE Post(

        contentID int UNSIGNED NOT NULL PRIMARY KEY,
        title varchar(200) NOT NULL,

        FOREIGN KEY (contentID) REFERENCES Contenuto(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE ImmaginiPost(

        postID int UNSIGNED NOT NULL,
        percorsoImmagine varchar(200) NOT NULL,

        CONSTRAINT immaginiPostID PRIMARY KEY (postID,percorsoImmagine),

        FOREIGN KEY (postID) REFERENCES Post(contentID) ON DELETE CASCADE


    ) ENGINE = InnoDB;


    CREATE TABLE Commento(
        contentID int UNSIGNED NOT NULL,
        postID int UNSIGNED NOT NULL,

        CONSTRAint commentoID PRIMARY KEY (contentID,postID),

        FOREIGN KEY (contentID) REFERENCES Contenuto(ID) ON DELETE CASCADE,
        FOREIGN KEY (postID) REFERENCES Post(contentID) ON DELETE CASCADE

     ) ENGINE = InnoDB;


    CREATE TABLE Notifica(
        utenteID int UNSIGNED NOT NULL,
        utenteCausaID int UNSIGNED NOT NULL check (utenteCausaID != utenteID),
        contenutoID int UNSIGNED NOT NULL,

        CONSTRAint NotificaID PRIMARY KEY (utenteID,utenteCausaID,contenutoID),

        FOREIGN KEY (utenteID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (utenteCausaID) REFERENCES Utente(ID),
        FOREIGN KEY (contenutoID) REFERENCES Contenuto(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Citazione(
        tagID int UNSIGNED NOT NULL,
        postID int UNSIGNED NOT NULL,

        CONSTRAint citazioneID PRIMARY KEY (tagID,postID),

        FOREIGN KEY (tagID) REFERENCES Tag(ID),
        FOREIGN KEY (postID) REFERENCES Post(contentID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


