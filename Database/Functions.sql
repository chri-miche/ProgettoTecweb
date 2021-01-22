
    /** PROCEDURE E FUNZIONI UTENTE:*/
    DROP PROCEDURE IF EXISTS get_user;
    CREATE PROCEDURE get_user(IN in_id int) BEGIN
        SELECT U.id as id, U.nome, U.email, U.password, U.immagineProfilo as immagine, U.isAdmin as admin
        FROM utente U WHERE U.ID = in_id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS get_all_users;
    CREATE PROCEDURE get_all_users() BEGIN
      SELECT U.id as id, U.nome, U.email, U.password, U.immagineProfilo as immagine, U.isAdmin as admin FROM utente U; END;

    DROP PROCEDURE IF EXISTS get_user_from_login;
    CREATE PROCEDURE get_user_from_login(IN in_email VARCHAR(40), IN in_password VARCHAR(14)) BEGIN
        SELECT U.id as id, U.nome, U.email, U.password, U.immagineProfilo as immagine, U.isAdmin as admin
        FROM utente U WHERE U.email = in_email AND U.password = in_password;
    END;

    DROP PROCEDURE IF EXISTS get_all_friends;
    CREATE PROCEDURE get_all_friends(IN id int) BEGIN
       SELECT U.ID as id, U.nome, U.email, U.password, U.immagineProfilo as immagine, U.isAdmin
       FROM utente U JOIN
           (  SELECT S.SeguaceID, S.SeguitoID FROM seguito S WHERE S.SeguaceID = id)
        S ON S.SeguitoID = U.ID; END;

    DROP PROCEDURE IF EXISTS check_email_unique;
    CREATE PROCEDURE check_email_unique(IN check_mail VARCHAR(40), IN usid INT) BEGIN
        DECLARE valid BOOLEAN; BEGIN
            SET valid = IF((SELECT U.ID FROM utente U WHERE U.email = check_mail AND U.ID != usid), false, true);
            SELECT valid; END; END;

    DROP PROCEDURE IF EXISTS check_email_unique_ne;
    CREATE PROCEDURE check_email_unique_ne(IN check_mail VARCHAR(40)) BEGIN
        DECLARE valid BOOLEAN;BEGIN
            SET valid = IF((SELECT U.ID FROM utente U WHERE U.email = check_mail), false, true);
            SELECT valid; END; END;


    DROP PROCEDURE IF EXISTS update_user;
    CREATE PROCEDURE update_user(IN id INT, IN nome VARCHAR(25), IN email VARCHAR(40), IN password VARCHAR(14),
    IN immagine VARCHAR(40), IN admin BOOL) BEGIN
        UPDATE utente U SET U.nome = nome, U.email = email,
        U.password = password, U.immagineProfilo = immagine, U.admin = admin WHERE U.ID = id; END;



    DROP PROCEDURE IF EXISTS create_user;
    CREATE PROCEDURE create_user(IN usernome VARCHAR(25), IN useremail VARCHAR(40), IN userpassword VARCHAR(14),
                                 IN immagine VARCHAR(40), IN usermoderatore BOOL, IN useradmin BOOL) BEGIN
        INSERT INTO utente(nome, email, password, immagineProfilo, admin)
        VALUE (usernome, useremail, userpassword, immagine, useradmin);
        SELECT LAST_INSERT_ID() as id; END;



    /* Procedure di ordine:*/
    DROP PROCEDURE IF EXISTS get_ordine;
    CREATE PROCEDURE get_ordine(IN id int) BEGIN
        SELECT O.TagID as id, O.nomeScientifico
        FROM ordine O WHERE O.TagID = id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS get_all_ordini;
    CREATE PROCEDURE get_all_ordini() BEGIN
        SELECT O.TagID as id, O.nomeScientifico FROM ordine O; END;

    DROP PROCEDURE IF EXISTS check_ordine_id;
    CREATE PROCEDURE check_ordine_id(IN ordine INT) BEGIN
       SELECT COUNT(O.TagID) as idexists FROM ordine O WHERE O.TagID = ordine LIMIT 1; END;

    DROP PROCEDURE IF EXISTS update_ordine;
    CREATE PROCEDURE update_ordine(IN id INT, IN nome VARCHAR(40)) BEGIN
        UPDATE ordine O SET O.nomeScientifico = nome WHERE O.TagID = id;END;


    DROP PROCEDURE IF EXISTS create_ordine;
    CREATE PROCEDURE create_ordine(IN nome VARCHAR(40)) BEGIN
        INSERT INTO tag(id) VALUE (NULL);
        INSERT INTO ordine(TagID, nomeScientifico) VALUE (LAST_INSERT_ID(), nome);

        SELECT LAST_INSERT_ID() AS id; END;


    DROP PROCEDURE IF EXISTS delete_ordine;
    CREATE PROCEDURE delete_ordine(IN del_id INT) BEGIN
        DELETE FROM ordine  WHERE TagID = del_id;
        DELETE FROM tag  WHERE ID = del_id; END;


    /** Procedure per ordine:*/
    DROP PROCEDURE IF EXISTS get_famiglia;
    CREATE PROCEDURE get_famiglia(IN id INT) BEGIN
       SELECT F.TagID as id, F.nomeScientifico, F.OrdID as ordine
       FROM famiglia F WHERE F.TagID = id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS get_all_famiglia;
    CREATE PROCEDURE get_all_famiglia() BEGIN
        SELECT F.TagID as id, F.nomeScientifico, O.TagID as ordine, O.nomeScientifico as nomeScientifico_ordine
        FROM famiglia F INNER JOIN ordine O ON F.OrdID = O.TagID; END;

    DROP PROCEDURE IF EXISTS get_all_famiglia_filter_by_ordine;
    CREATE PROCEDURE get_all_famiglia_filter_by_ordine(IN ordine INT) BEGIN
        SELECT F.TagID as id, F.nomeScientifico FROM famiglia F WHERE F.OrdID = ordine; END;

    DROP PROCEDURE IF EXISTS check_famiglia_id;
    CREATE PROCEDURE check_famiglia_id(IN id int) BEGIN
        SELECT COUNT(G.tagID) as idexists FROM famiglia G WHERE G.tagID = id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS create_famiglia;
    CREATE PROCEDURE create_famiglia(IN new_nome VARCHAR(40), IN new_ordine INT) BEGIN

        INSERT INTO tag(ID) VALUE (NULL);
        INSERT INTO famiglia(TagID, OrdID, nomeScientifico) VALUE  (LAST_INSERT_ID(), new_ordine, new_nome);

        SELECT LAST_INSERT_ID() as id; END;

    DROP PROCEDURE update_famiglia;
    CREATE PROCEDURE update_famiglia(IN id int, IN ordine INT, IN nomeScientifico VARCHAR(40)) BEGIN
        UPDATE famiglia F SET F.nomeScientifico = nomeScientifico, F.OrdID = ordine WHERE F.TagID = id; END;

    /** Procedure di Genere: */
    DROP PROCEDURE IF EXISTS get_genere;
    CREATE PROCEDURE get_genere(IN id INT) BEGIN
        SELECT G.nomeScientifico, G.tagID as id, G.famID as famiglia FROM genere G WHERE G.tagID = id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS get_all_genere;
    CREATE PROCEDURE get_all_genere() BEGIN
        SELECT  G.tagID as id, G.nomeScientifico, F.TagID as famiglia, F.nomeScientifico as nomeScientifico_famiglia,
                O.TagID as ordine, O.nomeScientifico as nomeScientifico_ordine
        FROM genere G INNER JOIN famiglia f on G.famID = f.TagID INNER JOIN ordine o on f.OrdID = o.TagID; END;


    DROP PROCEDURE  IF EXISTS get_all_genere_filter_by_famiglia;
    CREATE PROCEDURE get_all_genere_filter_by_famiglia(IN fam_id INT) BEGIN
        SELECT G.tagID as id, G.nomeScientifico FROM genere G WHERE G.tagID = famID; END;


    DROP PROCEDURE  IF EXISTS get_all_genere_filter_by_ordine;
    CREATE PROCEDURE get_all_genere_filter_by_ordine(IN ord_id INT) BEGIN
        SELECT G.tagID as id, G.nomeScientifico, F.TagID as f_id, F.nomeScientifico as f_nomeScientifico FROM genere G
        INNER JOIN famiglia f on G.famID = f.TagID WHERE F.OrdID = ord_id; END;

    DROP PROCEDURE IF EXISTS check_genere_id;
    CREATE PROCEDURE check_genere_id(IN id INT)
        BEGIN SELECT COUNT(G.tagID) as idexists FROM genere G WHERE G.tagID = id LIMIT 1; END;


    DROP PROCEDURE IF EXISTS update_genere;
    CREATE PROCEDURE update_genere(IN id INT, IN famiglia INT, IN nome VARCHAR(40))
        BEGIN UPDATE genere G SET G.nomeScientifico = nome, G.famID = famiglia WHERE G.tagID = id;END;


    DROP PROCEDURE IF EXISTS create_genere;
    CREATE PROCEDURE create_genere(IN nome_genere VARCHAR(40), IN famiglia INT) BEGIN

        INSERT INTO tag(nome) VALUE (nome_genere); /* Da togliere unicità su nome tag.*/
        INSERT INTO genere (tagID, famID, nomeScientifico) VALUE (LAST_INSERT_ID(), famiglia, nome_genere);

        SELECT LAST_INSERT_ID() as id;END;


    DROP PROCEDURE IF EXISTS delete_genere;
    CREATE PROCEDURE delete_genere(IN del_id INT) BEGIN
        DELETE FROM genere WHERE tagID = del_id;
        DELETE FROM tag  WHERE ID = del_id; END;


    DROP PROCEDURE IF EXISTS  get_specie;
    CREATE PROCEDURE get_specie(IN in_id INT) BEGIN

        SELECT  S.id, S.nomeScientifico, S.nomeComune, S.altezzaMedia,
                S.pesoMedio, S.descrizione, S.immagine, C.codice as conservazione, S.genID as genere
        FROM conservazione C INNER JOIN (

            SELECT  S.tagID as id,S.nomeScientifico, S.nomeComune, S.altezzaMedia,
            S.pesoMedio, S.descrizione, S.percorsoImmagine as immagine, S.conservazioneID, S.genID
            FROM specie S WHERE S.tagID = in_id LIMIT 1
        )

        S ON S.conservazioneID = C.codice;  END;


    DROP PROCEDURE IF EXISTS check_specie_id;
    CREATE PROCEDURE check_specie_id(IN check_id INT)
        BEGIN SELECT COUNT(S.tagID) AS idexists FROM specie S WHERE S.tagID = check_id LIMIT  1; END;

    DROP PROCEDURE IF EXISTS get_all_specie;
    CREATE PROCEDURE  get_all_specie() BEGIN
            SELECT  S.tagID as id, S.nomeScientifico, S.nomeComune, S.pesoMedio,
                    S.altezzaMedia, S.descrizione, S.percorsoImmagine as immagine,
                    G.tagID as genere, G.nomeScientifico as nomeScientifico_genere,
                    F.TagID as famiglia, F.nomeScientifico as nomeScientifico_famiglia,
                    O.TagID as ordine, O.nomeScientifico as nomeScientifico_ordine,
                    c.codice as conservazione, C.nome, C.probEstinzione, C.descrizione
            FROM specie S
            INNER JOIN genere g on S.genID = g.tagID INNER JOIN conservazione c on S.conservazioneID = c.codice
            INNER JOIN famiglia f on g.famID = f.TagID INNER JOIN ordine o on f.OrdID = o.TagID; END;

    DROP PROCEDURE IF EXISTS create_specie;
    CREATE PROCEDURE create_specie(IN in_nomeScientifico VARCHAR(40), IN in_nomeComune VARCHAR(40),IN in_pesoMedio INT,
    IN in_altezzaMedia INT, IN in_descrizione TEXT, IN in_percorsoImmagine VARCHAR(80),IN genere INT, IN conservazione VARCHAR(2)) BEGIN
        INSERT INTO tag(ID) VALUE (NULL);

        INSERT INTO specie (tagID, genID, nomeScientifico, nomeComune, percorsoImmagine,
                            conservazioneID,pesoMedio, altezzaMedia, descrizione)

        VALUE  (LAST_INSERT_ID(), genere,in_nomeScientifico, in_nomeComune, in_percorsoImmagine,
                    conservazione, in_pesoMedio, in_altezzaMedia, in_descrizione);

        SELECT LAST_INSERT_ID() as id; END;

    DROP PROCEDURE IF EXISTS update_specie;
    CREATE PROCEDURE update_specie(IN id INT, IN in_nomeScientifico VARCHAR(40), IN in_nomeComune VARCHAR(40),
        IN in_pesoMedio INT, IN in_altezzaMedia INT, IN in_descrizione TEXT, IN in_percorsoImmagine VARCHAR(80),
        IN genere INT, IN conservazione VARCHAR(2)) BEGIN

            UPDATE specie S SET S.nomeScientifico =in_nomeScientifico, S.nomeComune = in_nomeComune,
                    S.pesoMedio = in_pesoMedio, S.altezzaMedia = in_altezzaMedia, S.descrizione = in_descrizione,
                    S.percorsoImmagine = in_percorsoImmagine, S.genID = genID, S.conservazioneID = conservazione
            WHERE S.tagID = id;END;

    /** Metodi di conservazione: **/

    DROP PROCEDURE IF EXISTS get_conservazione;
    CREATE PROCEDURE get_conservazione(IN id VARCHAR(2))BEGIN
        SELECT C.codice as id, C.nome, C.probEstinzione, C.descrizione FROM conservazione C WHERE C.codice = id LIMIT 1;
    END;

    DROP PROCEDURE IF EXISTS get_all_conservazione;
    CREATE PROCEDURE get_all_conservazione()BEGIN
        SELECT C.codice as id, C.nome, C.probEstinzione, C.descrizione FROM conservazione C; END;

    DROP PROCEDURE IF EXISTS check_conservazione_id;
    CREATE PROCEDURE check_conservazione_id(IN id VARCHAR(2)) BEGIN
        SELECT COUNT(C.codice) as idexists FROM conservazione C WHERE C.codice = id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS create_conservazione;
    CREATE PROCEDURE create_conservazione(IN icodice VARCHAR(2), IN inome VARCHAR(20),
    IN iprobEstinzione INT, IN idescrizione TEXT) BEGIN
        INSERT INTO conservazione (codice, nome, probEstinzione, descrizione)
        VALUE (icodice, inome, iprobEstinzione, idescrizione);

        SELECT LAST_INSERT_ID() as newid; END;

    DROP PROCEDURE IF EXISTS update_conservazione;
    CREATE PROCEDURE update_conservazione(IN icodice VARCHAR(2), IN inome VARCHAR(20),
    IN iprobEstinzione INT, IN idescrizione TEXT) BEGIN
        UPDATE conservazione C  SET C.codice = icodice, C.nome = inome, C.descrizione = idescrizione,
        C.probEstinzione = iprobEstinzione, C.descrizione = idescrizione WHERE C.codice = icodice; END;


    /** Functions for Post.**/

    DROP PROCEDURE IF EXISTS get_post;
    CREATE PROCEDURE get_post(IN id INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, P.title
        FROM post P INNER JOIN contenuto C ON C.ID = P.contentID WHERE C.ID = id LIMIT 1;
    END;

    DROP PROCEDURE IF EXISTS get_all_post;
    CREATE PROCEDURE get_all_post() BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, P.title
        FROM post P INNER JOIN contenuto C ON C.ID = P.contentID;
    END;

    DROP PROCEDURE IF EXISTS get_many_post;
    CREATE PROCEDURE get_many_post(IN ilimit INT, IN offset INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, P.title
        FROM post P INNER JOIN contenuto C ON C.ID = P.contentID LIMIT offset, ilimit; END;

    DROP PROCEDURE IF EXISTS get_images_of_post;
    CREATE PROCEDURE get_images_of_post(IN id INT) BEGIN
        SELECT I.percorsoImmagine as immagine FROM immaginipost I WHERE I.postID = id; END;

    DROP PROCEDURE IF EXISTS add_missing_immagine;
    CREATE PROCEDURE add_missing_immagine(IN id INT, IN path VARCHAR(200)) BEGIN
        DECLARE img_exists BOOL; BEGIN
            SET img_exists = IF(
                (SELECT I.postID FROM immaginipost I WHERE I.postID = id AND I.percorsoImmagine = path LIMIT 1),
                true, false);

            IF !img_exists THEN INSERT INTO immaginipost (postID, percorsoImmagine)  VALUE (id, path); END IF;
        END;
    END;

    DROP PROCEDURE IF EXISTS delete_immagine;
    CREATE PROCEDURE delete_immagine(IN id INT, IN path VARCHAR(200)) BEGIN
        DELETE FROM immaginipost WHERE postID = id AND percorsoImmagine = path;END;

    DROP PROCEDURE IF EXISTS check_post_id;
    CREATE PROCEDURE check_post_id(IN in_id INT) BEGIN
         SELECT COUNT(P.contentID) AS idexists FROM post P WHERE P.contentID = in_id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS update_post;
    CREATE PROCEDURE update_post(IN in_id INT, IN in_userID INT, IN in_isArchived BOOL, IN in_content TEXT,
    IN in_date VARCHAR(300), IN in_title VARCHAR(200)) BEGIN

        UPDATE contenuto C SET C.content = in_content, C.isArchived = in_isArchived, C.UserID = in_UserID WHERE C.ID = in_id;
        UPDATE post P SET P.title = in_title WHERE P.contentID = in_id; END;

    DROP PROCEDURE IF EXISTS create_post;
    CREATE PROCEDURE create_post(IN in_userID INT, IN in_isArchived BOOL, IN in_content TEXT,
    IN in_date VARCHAR(300), IN in_title VARCHAR(200)) BEGIN
        INSERT INTO contenuto (UserID, isArchived, content, data)
        VALUE (in_userID, in_isArchived, in_content, NOW());
        INSERT INTO post(contentID, title) VALUE (LAST_INSERT_ID(), in_title);

        SELECT LAST_INSERT_ID() as id; END;

    DROP PROCEDURE IF EXISTS get_of_utente_post_all;
    CREATE PROCEDURE get_of_utente_post_all(IN in_id INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, P.title
        FROM post P INNER JOIN ( SELECT * FROM contenuto C WHERE C.UserID = in_id) AS C ON C.ID = P.contentID;
    END;


    DROP PROCEDURE IF EXISTS get_of_utente_post_limited;
    CREATE PROCEDURE get_of_utente_post_limited(IN in_id INT, IN in_limit INT, IN in_offset INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, P.title
        FROM post P INNER JOIN (
            SELECT * FROM contenuto C WHERE C.UserID = in_id LIMIT in_offset, in_limit
        ) AS C ON C.ID = P.contentID; END;


    DROP PROCEDURE IF EXISTS get_post_tag_all;
    CREATE PROCEDURE get_post_tag_all(IN in_id INT) BEGIN
        SELECT T.ID, T.label FROM tag T INNER JOIN (
            SELECT C.tagID FROM citazione C WHERE C.postID = in_id
        ) C ON C.tagID = in_id; END;

    DROP PROCEDURE IF EXISTS save_post_tag;
    CREATE PROCEDURE save_post_tag(IN post_id INT, IN tag_id INT) BEGIN
        INSERT INTO citazione (tagID, postID) VALUE (tag_id, post_id); END;

    DROP PROCEDURE IF EXISTS delete_post_tag;
    CREATE PROCEDURE delete_post_tag(IN post_id INT, IN tag_id INT) BEGIN
        DELETE FROM citazione WHERE tagID = tag_id AND postID = post_id; END;


    DROP PROCEDURE IF EXISTS get_commento;
    CREATE PROCEDURE get_commento(IN in_id INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, A.postID as post FROM contenuto C INNER JOIN
        (SELECT C.contentID, C.postID FROM commento C WHERE C.contentID = in_id LIMIT 1) A ON A.contentID = C.ID;
    END;

    DROP PROCEDURE IF EXISTS get_all_commento_from_post;
    CREATE PROCEDURE get_all_commento_from_post(IN post_id INT) BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, A.postID as post
        FROM contenuto C INNER JOIN commento A ON  C.ID = A.contentID WHERE A.postID = post_id; END;

    DROP PROCEDURE IF EXISTS get_all_commento;
    CREATE PROCEDURE get_all_commento() BEGIN
        SELECT C.ID as id, C.UserID as userId, C.isArchived, C.content, C.data as date, A.postID as post,

               C2.ID as p_id, C2.UserID as p_userId, C.isArchived as p_isArchived,
               C.content as p_content, C2.data as p_date, P.title as p_title

        FROM contenuto C INNER JOIN commento A ON C.ID = A.contentID
        INNER JOIN post P on A.postID = P.contentID INNER JOIN contenuto C2 ON C2.ID = P.contentID; END;


    DROP PROCEDURE IF EXISTS check_commento_id;
    CREATE PROCEDURE check_commento_id(IN in_id INT) BEGIN
         SELECT COUNT(P.contentID) AS idexists FROM commento P WHERE P.contentID = in_id LIMIT 1; END;

    DROP PROCEDURE IF EXISTS create_commento;
    CREATE PROCEDURE create_commento(IN in_user INT, IN in_isArchived BOOL,
    IN in_content TEXT, IN in_date VARCHAR(30), IN in_post INT) BEGIN
        INSERT INTO contenuto(ID, UserID, isArchived, content, data) VALUES
        (NULL, in_user, in_isArchived, in_content, NOW());

        INSERT INTO commento (contentID, postID) VALUES (LAST_INSERT_ID(), in_post);
        SELECT LAST_INSERT_ID() as id;END;

    DROP PROCEDURE IF EXISTS update_commento;
    CREATE PROCEDURE update_commento(IN in_id INT, IN in_user INT, IN in_isArchived BOOL,
    IN in_content TEXT, IN in_date VARCHAR(30), IN in_post INT) BEGIN

        UPDATE contenuto SET UserID = in_user, isArchived = in_isArchived, content = in_content WHERE ID = in_id;
        UPDATE commento SET postID = in_post WHERE contentID = in_id; END;

    DROP PROCEDURE IF EXISTS delete_content;
    CREATE PROCEDURE delete_content(IN in_id INT) BEGIN
        DELETE FROM contenuto WHERE ID = in_id; END;