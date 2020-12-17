<?php

    session_start();
    $HTMLpage = file_get_contents("../xhtml/BaseLayout.xhtml");

    $HTMLpage = preparePage(new SearchContent());

    $HTMLpage = str_replace("<sideBar />", Builder::buildSideBar(), $HTMLpage);

    $HTMLpage = str_replace("<searchBar />", Builder::buildSearchBar(true), $HTMLpage);

    $HTMLpage = str_replace("<content />", Builder::buildSearchResults(), $HTMLpage);

?>