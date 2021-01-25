<?php

require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "PostDAO.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "CommentoDAO.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "SpecieDAO.php";

require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "BasePage.php";
require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "catalogo" . DIRECTORY_SEPARATOR . "GenericBrowser.php";


class SearchTab extends BasePage {

    private $keyword;
    private $entity;

    public function __construct($keyword, $entity, $currentPage = 0) {
        parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "search" . DIRECTORY_SEPARATOR . "SearchTab.xhtml"));

        $this->keyword = addslashes($keyword);
        $this->entity = $entity;

        $giveVOArray = array();

        switch ($entity) {
            case "commento":

                $giveVOArray = (new CommentoDAO())->search("%$this->keyword%");
                /** @var CommentoVO $comment*/

                $layout = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "search" . DIRECTORY_SEPARATOR . "CommentCard.xhtml"); break;

            case "specie":

                $giveVOArray = (new SpecieDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "search" . DIRECTORY_SEPARATOR . "SpecieCard.xhtml"); break;

            case "post":
            default:

                $giveVOArray = (new PostDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "feed" . DIRECTORY_SEPARATOR . "PostCard.xhtml");

        }

        $this->addComponent(new GenericBrowser($giveVOArray, $layout, "Search.php?",$currentPage));
    }

    public function resolveData() {
        return array(
            "{keyword}" => $this->keyword,
            "{post}" => $this->entity === 'post' ? '" aria-selected="true" type="button' : '" aria-selected="false" type="submit',
            "{specie}" => $this->entity === 'specie' ? '"  aria-selected="true" type="button' : '" aria-selected="false" type="submit',
            "{commento}" => $this->entity === 'commento' ? '" aria-selected="true" type="button' : '" aria-selected="false" type="submit',
            'action="Search.php?entity=' . $this->entity  . '"' => '',
            "{entity}" => $this->entity,
        );
    }

}