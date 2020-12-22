/*CREATE DATABASE WebBirdDB;
USE WebBirdDB;
*/

Insert into Tag(ID, nome, LabelID) VALUES (1, 'Pinguini', null);
Insert into ordine(TagID, nomeScientifico) VALUES (1, 'Pengus Maestos');
insert into utente(ID, nome, email, password, immagineProfilo) values
    (1, 'Jacopo', 'jacopo.fichera98@gmail.com', 'risiko', 'immagine.jpg');

insert into utente(ID, nome, email, password, immagineProfilo) values
    (3, 'Spdierman', 'spider@man.it', 'risiko', 'img.jpg'),
    (4, 'John Wick', 'john@wick.gun', 'risiko', 'img.jpg'),
    (5, 'Adam Sandler', 'adam@sandler.funny', 'risiko', 'img.jpg');


Insert into moderatore(UserID, isAdmin) value
    (1, true);

insert into contenuto(ID, UserID, isArchived, content, data) values
    (0, 1, false, 'Abracadabra qui ci sta del testo uhahaha','1960-10-12'),
    (1, 3, false, 'Abba','1960-10-12');


insert into post(contentID, title) value
    (0, 'Prova 1'),
    (1, 'Prova 2');

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

insert into tag(ID, nome, LabelID) VALUES (0, 'Fenicottero', 1);


insert into citazione(tagID, postID) VALUES (1, 0), (9,0), (1, 1);

