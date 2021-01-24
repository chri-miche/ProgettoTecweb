<?php

require_once __ROOT__.'\model\DAO\PostDAO.php';
require_once __ROOT__.'\model\DAO\CommentoDAO.php';
require_once __ROOT__.'\model\DAO\SpecieDAO.php';

require_once __ROOT__.'\control\BasePage.php';
require_once __ROOT__.'\control\components\catalogo\GenericBrowser.php';


class SearchTab extends BasePage {

    private $keyword;
    private $entity;

    public function __construct($keyword, $entity) {
        parent::__construct(file_get_contents(__ROOT__."/view/modules/search/SearchTab.xhtml"));

        $this->keyword = addslashes($keyword);
        $this->entity = $entity;

        $giveVOArray = array();

        switch ($entity) {
            case "commento":

                $commentArrayVO = (new CommentoDAO())->search("%$this->keyword%");
                /** @var CommentoVO $comment*/
                foreach ($commentArrayVO as $commentVO) $giveVOArray [] = $commentVO->getPostVO();

                $layout = file_get_contents(__ROOT__."/view/modules/search/CommentCard.xhtml"); break;

            case "specie":

                $giveVOArray = (new SpecieDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__ROOT__."/view/modules/search/SpecieCard.xhtml"); break;

            case "post":
            default:

                $giveVOArray = (new PostDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__ROOT__."/view/modules/feed/PostCard.xhtml");

        }

        $this->addComponent(new GenericBrowser($giveVOArray, $layout, "Search.php"));
    }

    public function resolveData() {
        return array(
            "{keyword}" => $this->keyword,
            "{post}" => $this->entity === 'post' ? '" aria-disabled="true" aria-selected="true" tabindex="-1" type="button' : '" type="submit',
            "{specie}" => $this->entity === 'specie' ? '" aria-disabled="true" aria-selected="true" tabindex="-1" type="button' : '" type="submit',
            "{commento}" => $this->entity === 'commento' ? '" aria-disabled="true" aria-selected="true" tabindex="-1" type="button' : '" type="submit',
            'action="Search.php?entity=' . $this->entity  . '"' => '',
            "{entity}" => $this->entity,
        );
    }

}