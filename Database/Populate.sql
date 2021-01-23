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



insert into tag(ID) VALUES (0);

INSERT INTO tag(ID) VALUES  (8),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(21),(22),(23);

INSERT INTO ordine(TagID, nomeScientifico) VALUES (8, 'Galliformes'), (10, 'Gaviiformes'),
(11, 'Sphenisciformes'),(12, 'Procellariiformes'),(13, 'Podicipediformes'),(14, 'Phaethontiformes'),(15, 'Phoenicopteriformes'),(16, 'Ciconiiformes'),
(17, 'Pelecaniformes'),(18, 'Suliformes'),(19, 'Accipitriformes'),(20, 'Falconiformes'),(21, 'Otidiformes'),(22, 'Mesitornithiformes'),(23, 'Cariamiformes');

INSERT INTO tag(ID) VALUES
    (24), (25), (26), (27), (28), (29);

INSERT INTO famiglia(TagID, OrdID, nomeScientifico) VALUES
    (24, 15 ,'Phoenicopteridae'), (25, 17,'Threskiornithidae'), (26,17,'Ardeidae'), (27,17,'Scopidae'), (28,17,'Pelecanidae'), (29,17,' Balaenicipitidae');


select *from tag;

INSERT INTO tag(ID) VALUES (30),(31),(32),(35),(36),(37),(38);

INSERT INTO genere(tagID, famID, nomeScientifico) VALUES (30, 28, 'Balaeniceps'),(31, 28, 'Pelecanus'),(32, 28,'Scopus');

INSERT INTO conservazione(codice, nome, probEstinzione) VALUES ('VU', 'Vulnerabile', 30);

INSERT INTO specie(tagID, genID, nomeScientifico, percorsoImmagine, conservazioneID, pesoMedio, altezzaMedia, descrizione) VALUES
    (35, 30, 'B. rex', '', 'VU', 200, 180, 'Bel uccello' );

INSERT INTO specie(tagID, genID, nomeScientifico, percorsoImmagine, conservazioneID, pesoMedio, altezzaMedia, descrizione) VALUES
    (36, 31, 'Pelecanus conspicillatus', '', 'VU',200, 180, 'Bel uccello' ),(37, 31, 'Pelecanus crispus', '', 'VU',200, 180, 'Bel uccello' ),(38, 31, 'Pelecanus erythrorhynchos', '', 'VU',200, 180, 'Bel uccello' );

