
    /* Jacopo Fichera - 05/12/2020.
       Creazione del database. Rilascio delle possibli tabelle.
       Pirma vengono droppate le entità più deboli.*/

    /** Da fare: Controllare vincoli e riguardare tutto.*/

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

    drop table if exists Specie;
    drop table if exists Genere;
    drop table if exists Famiglia;
    drop table if exists Ordine;


    drop table if exists Conservazione;

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

        UserID INT UNSIGNED NOT NULL,
        CertID INT UNSIGNED NOT NULL,

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
        OrdID INT UNSIGNED NOT NULL,

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

        specieID int UNSIGNED NOT NULL,
        zonaID int UNSIGNED NOT NULL,

        periodoInizio date NOT NULL,
        periodoFine date NOT NULL CHECK ( periodoFine > periodoInizio ), /* Data fine deve venire dopo di inizio*/

        constraint residenzaID primary key (specieID,zonaID),

        foreign key (specieID) references Specie(tagID),
        foreign key (zonaID) references ZonaGeografica(tagID)

    ) engine = InnoDB;

    create table Contenuto(
        ID int unsigned not null primary key,
        UserID int unsigned not null, /* Primo utente in Utenti sarà deleted.*/

        isArchived bool not null,
        content text not null,
        data date not null,

        foreign key (ID) references Utente(ID)
    ) engine = InnoDB;

    create table Approvazione(
        utenteID int unsigned not null,
        contentID int unsigned not null,

        likes bool not null,

        constraint approvazioneID primary key (utenteID,contentID),

        foreign key (utenteID) references Utente(ID),
        foreign key (contentID) references Contenuto(ID)
    ) engine = InnoDB;

    create table Segnalazione(
        contentID int unsigned not null,
        utenteID int unsigned not null,

        modResponsabile int unsigned not null,

        causale text,
        elaborato bool not null default (false),

        constraint segnalazioneID primary key (contentID,utenteID),

        foreign key (contentID) references Contenuto(ID) on delete cascade,
        foreign key (utenteID) references Utente(ID),
        foreign key  (modResponsabile) references Moderatore(UserID)
    ) engine = InnoDB;

    create table Post(
        contentID int unsigned not null primary key ,
        title varchar(200) not null,

        foreign key (contentID) references Contenuto(ID) on delete cascade
    ) engine = InnoDB;

    create table ImmaginiPost(
        postID int unsigned not null primary key,
        percorsoImmagine varchar(200) not null unique,

        foreign key (postID) references Post(contentID) on delete cascade
    ) engine = InnoDB;

    create table Commento(
        contentID int unsigned not null,
        postID int unsigned not null,

        constraint commentoID primary key (contentID,postID),
        foreign key (contentID) references Contenuto(ID) on delete cascade,
        foreign key (postID) references Post(contentID) on delete cascade
    ) engine = InnoDB;

    create table Notifica(
        utenteID int unsigned not null,
        utenteCausaID int unsigned not null check (utenteCausaID != utenteID),
        contenutoID int unsigned not null,

        constraint NotificaID primary key (utenteID,utenteCausaID,contenutoID),
        foreign key (utenteID) references Utente(ID),
        foreign key (utenteCausaID) references Utente(ID),
        foreign key (contenutoID) references Contenuto(ID)

    ) engine = InnoDB;