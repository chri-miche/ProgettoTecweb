<?php
require_once __DIR__ . "/standardLayoutIncludes.php";
require_once __DIR__ . "/Application/SessionUser.php";

require_once __DIR__. "/Application/genericBrowser/GenericBrowser.php";

$sessionUser = new SessionUser();


$id = $_GET['id'] ?? header('Location: index.php');
$pageUser = (new UserDAO())->get($id);

$basePage = file_get_contents(__DIR__ . "/Application/BaseLayout.xhtml");
$page = new BasePage($basePage);

$page->addComponent(new SiteBar('User'));
$page->addComponent(new BreadCrumb(array('Utente' => '')));

$userPreviewLayout = file_get_contents(__DIR__. "/Application/profile/UserCard.xhtml");
$userDao = new UserDAO();

$userVO = $userDao->get($id);

if(!$userVO->getId()) header('Location: index.php');

$userList = $userDao->getFriends($userVO);

$pageNumber = $_GET['page'] ?? 0;
$page->addComponent(new GenericBrowser($userList,$userPreviewLayout, "user_friends.php?id=$id&", $pageNumber, 8));

echo $page;