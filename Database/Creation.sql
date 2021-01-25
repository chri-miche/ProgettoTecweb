
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

    CREATE TABLE Utente(

        ID int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT comment 'Identificativo',
        nome VARCHAR(25) NOT NULL comment 'Nome',

        email VARCHAR(40) NOT NULL UNIQUE comment 'Email',
        password VARCHAR(14) NOT NULL comment 'Password',

        isAdmin BOOLEAN NOT NULL DEFAULT FALSE comment 'Abilitato all\'amministrazione',

        immagineProfilo varchar(40) NOT NULL DEFAULT ('res/default.png') comment 'Immagine di profilo'

    ) ENGINE = InnoDB;

    CREATE TABLE Tag(

        ID int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY

    ) ENGINE = InnoDB;



    CREATE TABLE Ordine(

        TagID int UNSIGNED NOT NULL PRIMARY KEY comment 'Tag',
        nomeScientifico VARCHAR(40) NOT NULL UNIQUE comment 'Nome scientifico',

        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Famiglia(

        TagID int UNSIGNED NOT NULL PRIMARY KEY comment 'Tag',
        OrdID int UNSIGNED NOT NULL comment 'Ordine',

        nomeScientifico VARCHAR(40) NOT NULL comment 'Nome scientifico',

        FOREIGN KEY (TagID) REFERENCES Tag(ID) ON DELETE CASCADE,
        FOREIGN KEY (OrdID) REFERENCES Ordine(TagID) ON DELETE CASCADE

    ) ENGINE = InnoDB;


    CREATE TABLE Genere(

        tagID int UNSIGNED NOT NULL PRIMARY KEY comment 'Tag',
        famID int UNSIGNED NOT NULL comment 'Famiglia',

        nomeScientifico VARCHAR(40) NOT NULL comment 'Nome scientifico',

        FOREIGN KEY (tagID) REFERENCES Tag(ID) ON DELETE CASCADE,
        FOREIGN KEY (famID) REFERENCES Famiglia(TagID) ON DELETE CASCADE

    ) ENGINE = InnoDB;

    CREATE TABLE Conservazione(

        codice varchar(2) NOT NULL PRIMARY KEY comment 'Codice',
        nome varchar(20) NOT NULL comment 'Nome',
        probEstinzione int comment 'Probabilità di estinzione (%)',
        descrizione text comment 'Descrizione'

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

