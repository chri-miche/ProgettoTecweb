
/* Jacopo Fichera - 05/12/2020.
   Creazione del database. Rilascio delle possibli tabelle.
   (Pirma vengono droppate le entità più deboli).*/

    DROP TABLE IF EXISTS Commento;

    DROP TABLE IF EXISTS ImmaginiPost;
    DROP TABLE IF EXISTS Post;

    DROP TABLE IF EXISTS Approvazione;

    DROP TABLE IF EXISTS Contenuto;

    DROP TABLE IF EXISTS Seguito;
    DROP TABLE IF EXISTS Utente;

    DROP TABLE IF EXISTS Specie;
    DROP TABLE IF EXISTS Genere;
    DROP TABLE IF EXISTS Famiglia;
    DROP TABLE IF EXISTS Ordine;


    DROP TABLE IF EXISTS Conservazione;

    DROP TABLE IF EXISTS Tag;

    /*TODO: Change all keys to snakecase*/
    CREATE TABLE Utente(

        ID int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
        nome VARCHAR(25) NOT NULL,

        email VARCHAR(40) NOT NULL UNIQUE,
        password VARCHAR(14) NOT NULL,

        isAdmin BOOLEAN NOT NULL DEFAULT FALSE,

        immagineProfilo varchar(40) NOT NULL DEFAULT ('imgs/default.png')

    ) ENGINE = InnoDB;

    /** Sistemare un po' i nomi, non troppo chiari.*/
    CREATE TABLE Seguito(

        SeguitoID int UNSIGNED NOT NULL,
        SeguaceID int UNSIGNED NOT NULL,

        CONSTRAint SeguitoKey PRIMARY KEY (SeguitoID,SeguaceID),

        FOREIGN KEY (SeguaceID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (SeguitoID) REFERENCES Utente(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;

    CREATE TABLE Tag(

        ID int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,

    ) ENGINE = InnoDB;



    CREATE TABLE Ordine(

        TagID int UNSIGNED NOT NULL PRIMARY KEY,
        nomeScientifico VARCHAR(40) NOT NULL UNIQUE,

        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Famiglia(

        TagID int UNSIGNED NOT NULL PRIMARY KEY,
        OrdID int UNSIGNED NOT NULL,

        nomeScientifico VARCHAR(40) NOT NULL,

        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE,
        FOREIGN KEY (OrdID) REFERENCES Ordine(TagID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Genere(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,
        famID int UNSIGNED NOT NULL,

        nomeScientifico VARCHAR(40) NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID) ON DELETE CASCADE,
        FOREIGN KEY (famID) REFERENCES Famiglia(TagID) ON DELETE CASCADE

    ) ENGINE = InnoDB;

    CREATE TABLE Conservazione(

        codice varchar(2) NOT NULL PRIMARY KEY,
        nome varchar(20) NOT NULL,
        probEstinzione int,
        descrizione text

    ) ENGINE = InnoDB;

    CREATE TABLE Specie(

        tagID int UNSIGNED NOT NULL PRIMARY KEY,
        genID int UNSIGNED NOT NULL,

        nomeScientifico varchar(40) NOT NULL,
        nomeComune varchar(40), /* Potrebbe non averlo.*/

        percorsoImmagine varchar(80) NOT NULL,

        conservazioneID varchar(2) NOT NULL,

        pesoMedio int UNSIGNED NOT NULL,
        altezzaMedia int UNSIGNED NOT NULL,
        descrizione text NOT NULL,

        FOREIGN KEY (tagID) REFERENCES Tag(ID) ON DELETE CASCADE,
        FOREIGN KEY (genID) REFERENCES Genere(tagID) ON DELETE CASCADE,
        FOREIGN KEY (conservazioneID) REFERENCES Conservazione(Codice)

    ) ENGINE = InnoDB;

    CREATE TABLE Contenuto(

        ID int UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        UserID int UNSIGNED NOT NULL DEFAULT 1, /* Primo utente in Utenti sarà deleted.*/

        isArchived bool NOT NULL,
        content text NOT NULL,
        data date NOT NULL,

        /** Fare si che quando si elimina un conrenuto essa venga indirizzato allo standard user 'deleted'. */
        FOREIGN KEY (UserID) REFERENCES Utente(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Approvazione(

        utenteID int UNSIGNED NOT NULL,
        contentID int UNSIGNED NOT NULL,

        likes bool NOT NULL,

        CONSTRAint approvazioneID PRIMARY KEY (utenteID,contentID),

        FOREIGN KEY (utenteID) REFERENCES Utente(ID) ON DELETE CASCADE,
        FOREIGN KEY (contentID) REFERENCES Contenuto(ID) ON DELETE CASCADE

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

