<?php

    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    require_once __ROOT__ . '\model\DAO\UserDAO.php';
    require_once __ROOT__ . '\model\DAO\FamigliaDAO.php';
    require_once __ROOT__ . '\model\DAO\GenereDAO.php';
    require_once __ROOT__ . '\model\DAO\OrdineDAO.php';
    require_once __ROOT__ . '\model\DAO\SpecieDAO.php';
    require_once __ROOT__ . '\model\DAO\ConservazioneDAO.php';

require_once __ROOT__ . '\model\DAO\PostDAO.php';
require_once __ROOT__ . '\model\DAO\CommentoDAO.php';


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

    $postVO->setImmagini(array('fsd', 'uuu'));
    $postVO->setUserId(3);


    $nDAO = new CommentoDAO();
    print_r($nDAO->getAll());

    $comment  = new CommentoVO(4, 4, false);
    $comment->setPostVO($postVO);

    print_r($comment);


    $nDAO->save($comment);

    echo $postDAO->save($postVO);

    $userVO = new UserVO();

    print_r($userVO->arrayDump());