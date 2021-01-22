<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\model\DAO\UserDAO.php';
    require_once __ROOT__ . '\model\DAO\FamigliaDAO.php';
    require_once __ROOT__ . '\model\DAO\GenereDAO.php';
    require_once __ROOT__ . '\model\DAO\OrdineDAO.php';
    require_once __ROOT__ . '\model\DAO\SpecieDAO.php';
    require_once __ROOT__.'\model\DAO\ConservazioneDAO.php';

require_once __ROOT__.'\model\DAO\PostDAO.php';


    $cDAO = new ConservazioneDAO();
    //print_r($cDAO->get('VU'));
    $c = new ConservazioneVO('ZZ', 'NEW s MICHE', 50);
    //echo $cDAO->checkId($c);
    //print_r($cDAO->save($c));


    /* FAMIGLIA.*/
    $fDAO = new FamigliaDAO();
    $f = $fDAO->get(1);

    print_r($fDAO->getAll());
    $postDAO = new PostDAO();

    print_r($postDAO->getOfUtente(1));

    $postVO = $postDAO->get(3);

    $postVO->setImmagini(array('immagineuno', 'immaginedue'));
    print_r($postVO);

    echo $postDAO->save($postVO);