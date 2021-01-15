create view vw_post as
select
    post.contentID,
    IFNULL(a.likes, 0) as likes,
    contenuto.data,
    (select count(1) from commento where commento.contentID = post.contentID) as commenti
from post
left join (select contentID, sum(likes) as likes from approvazione group by contentID) a
	on post.contentID = a.contentID
join contenuto
    on contenuto.ID = post.contentID;

/**
    view per facilitare la query dei post per l'ordinamento nella pagina iniziale
    attributi:
        contentID // id del post
        likes // somma del numero dei like
        data // data del contenuto
        commenti // numero di commenti (fatto con una select di un placeholder per speeeed)
*/

CREATE VIEW bird_summary AS
    SELECT  S.tagID, S.nomeScientifico, S.percorsoImmagine,
            O.TagID as ordID, O.nomeScientifico as nomeOrdine,
            F.TagID as famID, F.nomeScientifico as nomeFamiglia,
            G.tagID as genID, G.nomeScientifico as nomeGenerea
    FROM specie AS S
        INNER JOIN genere AS G on S.genID = G.tagID
        INNER JOIN famiglia AS F on G.famID = F.TagID
        INNER JOIN ordine O on F.OrdID = O.TagID