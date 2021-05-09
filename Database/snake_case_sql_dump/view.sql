
DROP VIEW IF EXISTS vw_post;
create view vw_post as
select
    post.content_id,
    IFNULL(sum(a.likes), 0) as likes,
    contenuto.data,
    (select count(1) from commento where commento.post_id = post.content_id) as commenti
from post
join contenuto
    on contenuto.id = post.content_id
join approvazione a
    on a.content_id = post.content_id
group by a.content_id;


DROP VIEW IF EXISTS bird_summary;
CREATE VIEW bird_summary AS
    SELECT  s.tag_id, s.nome_scientifico, s.percorso_immagine,
            o.tag_id as ord_id, o.nome_scientifico as nome_ordine,
            f.tag_id as fam_id, f.nome_scientifico as nome_famiglia,
            g.tag_id as gen_id, g.nome_scientifico as nome_genere
    FROM specie AS s
        INNER JOIN genere AS g on s.gen_id = g.tag_id
        INNER JOIN famiglia AS f on g.fam_id = f.tag_id
        INNER JOIN ordine o on f.ord_id = o.tag_id