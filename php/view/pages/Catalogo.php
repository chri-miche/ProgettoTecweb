<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));
    /* Get all params. Create view and print elements.
        Browser (of tags) -> BrowsePosts(of posts with that tag) -> Post Preview */
    require_once __ROOT__.'\control\BasePage.php';

    require_once __ROOT__.'\control\components\SideBar.php';
    require_once __ROOT__.'\control\components\Report.php';
    require_once __ROOT__.'\control\components\SearchBar.php';
    require_once __ROOT__.'\control\components\BreadCrumb.php';

    require_once __ROOT__.'\control\components\BrowseElements.php';
    require_once __ROOT__.'\control\components\BrowsePosts.php';
    require_once __ROOT__ . '\control\components\previews\TagPreview.php';

    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');
    $page = new BasePage($basePage);

    $page->setSideBar(new SideBar());



    $page->addComponent(new SearchBar());

    $page->addComponent(new TagPreview(9));
    $page->addComponent(new TagPreview(9));
    $page->addComponent(new TagPreview(9));
    $page->addComponent(new TagPreview(9));
    $page->addComponent(new TagPreview(9));

    echo  $page;
?>