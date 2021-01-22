/*CREATE DATABASE WebBirdDB;
USE WebBirdDB;
*/

Insert into Tag(ID, nome, LabelID) VALUES (1, 'Pinguini', null);
Insert into ordine(TagID, nomeScientifico) VALUES (1, 'Pengus Maestos');
insert into utente(ID, nome, email, password, immagineProfilo) values
    (1, 'Jacopo', 'jacopo.fichera98@gmail.com', 'risiko', 'immagine.jpg'),
    (3, 'Spdierman', 'spider@man.it', 'risiko', 'img.jpg'),
    (4, 'John Wick', 'john@wick.gun', 'risiko', 'img.jpg'),
    (5, 'Adam Sandler', 'adam@sandler.funny', 'risiko', 'img.jpg');

Insert into moderatore(UserID, isAdmin) value (1, true);

insert into contenuto(UserID, isArchived, content, data) values
    (1, false, 'Abracadabra qui ci sta del testo uhahaha','1960-10-12'),
    (3, false, 'Abba','1960-10-12');

select * from contenuto;
insert into post(contentID, title) valueS
    (3, 'Prova 1'),
    (4, 'Prova 2');

insert into approvazione(utenteID, contentID, likes) VALUES
    (1, 0, -1),
    (3, 0, -1),
    (4, 0, -1),
    (5, 0, -1);

insert into immaginipost(postID, percorsoImmagine)
    values  (0, 'AAA'), (0, 'perc2'), (0, 'bbb');


insert into segnalazione(contentID, utenteID, modResponsabile, causale)
    values  (0, 3, 1, 'Brutto');

insert into label(ID, text) VALUES
    (0, 'Un uccello molto rosa');

insert into tag(ID, nome, LabelID) VALUES (0, 'Fenicottero', null);

INSERT INTO tag(ID, nome, LabelID) VALUES  (8, 'Galliformes', null),
    (10, 'Gaviiformes', null),(11, 'Sphenisciformes', null),(12, 'Procellariiformes', null),(13, 'Podicipediformes', null),(14, 'Phaethontiformes', null),(15, 'Phoenicopteriformes', null),(16, 'Ciconiiformes', null),
    (17, 'Pelecaniformes', null),(18, 'Suliformes', null),(19, 'Accipitriformes', null),(20, 'Falconiformes', null),(21, 'Otidiformes', null),(22, 'Mesitornithiformes', null),(23, 'Cariamiformes', null);

INSERT INTO ordine(TagID, nomeScientifico) VALUES (8, 'Galliformes'), (10, 'Gaviiformes'),
(11, 'Sphenisciformes'),(12, 'Procellariiformes'),(13, 'Podicipediformes'),(14, 'Phaethontiformes'),(15, 'Phoenicopteriformes'),(16, 'Ciconiiformes'),
(17, 'Pelecaniformes'),(18, 'Suliformes'),(19, 'Accipitriformes'),(20, 'Falconiformes'),(21, 'Otidiformes'),(22, 'Mesitornithiformes'),(23, 'Cariamiformes');

INSERT INTO tag(ID, nome, LabelID) VALUES
    (24,'Phoenicopteridae', null), (25,'Threskiornithidae', null), (26,'Ardeidae', null), (27,'Scopidae', null), (28,'Pelecanidae', null), (29,' Balaenicipitidae', null);

INSERT INTO famiglia(TagID, OrdID, nomeScientifico, caratteristicheComuni) VALUES
    (24,15 ,'Phoenicopteridae', null), (25, 17,'Threskiornithidae', null), (26,17,'Ardeidae', null), (27,17,'Scopidae', null), (28,17,'Pelecanidae', null), (29,17,' Balaenicipitidae', null);


select *from tag;

INSERT INTO tag(ID, nome, LabelID) VALUES
    (30, 'Balaeniceps', null),(31, 'Pelecanus', null),(32, 'Scopus', null),(35, 'B. rex', null),(36, 'Pelecanus conspicillatus', null),(37, 'Pelecanus crispus', null),(38, 'Pelecanus erythrorhynchos', null);

INSERT INTO genere(tagID, famID, nomeScientifico) VALUES
    (30, 28, 'Balaeniceps'),(31, 28, 'Pelecanus'),(32, 28,'Scopus');

INSERT INTO conservazione(codice, nome, probEstinzione) VALUES
    ('VU', 'Vulnerabile', 30);

INSERT INTO specie(tagID, genID, nomeScientifico, percorsoImmagine, conservazioneID, pesoMedio, altezzaMedia, descrizione) VALUES
    (35, 30, 'B. rex', '', 'VU', 200, 180, 'Bel uccello' );

INSERT INTO specie(tagID, genID, nomeScientifico, percorsoImmagine, conservazioneID, pesoMedio, altezzaMedia, descrizione) VALUES
    (36, 31, 'Pelecanus conspicillatus', '', 'VU',200, 180, 'Bel uccello' ),(37, 31, 'Pelecanus crispus', '', 'VU',200, 180, 'Bel uccello' ),(38, 31, 'Pelecanus erythrorhynchos', '', 'VU',200, 180, 'Bel uccello' );


ALTER TABLE tag MODIFY nome VARCHAR(40);

INSERT INTO interesse(tagID, userID) VALUE (30, 1), (35, 1 );

alter table specie add column nomeComune VARCHAR(40);