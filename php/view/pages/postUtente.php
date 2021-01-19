<?php
    define('__ROOT__', dirname(dirname(dirname(__FILE__))));

    // TODO: Identico per amici e tag.

    require_once __ROOT__ . '\view\pages\standardLayoutIncludes.php';

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\control\components\catalogo\GenericBrowser.php';
    require_once __ROOT__.'\control\components\profile\UserDetails.php';

    /* Visualizzazione.*/
    // Base component of page layout.
    $basePage = file_get_contents(__ROOT__.'\view\BaseLayout.xhtml');
    $page = new BasePage($basePage);

    /** Se non siamo nella pagina di post di un utente non possiamo visualizzare niente e quindi
      * veniamo reindirizzati alla pagina principlae (dovrebbe essere un caso praticamente impossibile
      * tranne se l'utente ovviamente mette un id nel url sbagliato).*/
    $usid = $_GET['usid'] ?? header('Location: Home.php');



    $page->addComponent(new SiteBar('PostUtente'));
    // TODO: Mettere nome dell'utente nella breadcrumb?
    $page->addComponent(new BreadCrumb(array('Utente' => 'UserPage.php?id='.$usid, 'I suoi Post'=>'')));

    // TODO: Change to a "cardborard" of the user (as summary).
    $page->addComponent(new UserDetails(new UserElement($usid), "postUtente.php?usid=", false));

    /* Browser ed è fatta.*/
    $tagPreviewLayout = file_get_contents(__ROOT__.'\view\modules\user\PostCard.xhtml');

    // TODO: Optimize the query number result.
    // TODO: Make DAO.
    $postList = PostElement::getUserPosts($usid);

    $showValue = array();
    foreach ($postList as $post)
        $showValue[] = $post->getData();


    $page->addComponent(new GenericBrowser($showValue, $tagPreviewLayout,
        'postUtente.php?usid'. $usid, $_GET['page'] ?? 0));

    echo $page;


?>