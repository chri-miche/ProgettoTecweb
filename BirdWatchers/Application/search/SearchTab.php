<?php
/** DAO imports.**/
require_once __DIR__ . "/../databaseObjects/post/PostDAO.php";
require_once __DIR__ . "/../databaseObjects/commento/CommentoDAO.php";
require_once __DIR__ . "/../databaseObjects/specie/SpecieDAO.php";

/** Other dependencies*/
require_once __DIR__ . "/../BasePage.php";
require_once __DIR__ . "/../genericBrowser/GenericBrowser.php";

class SearchTab extends BasePage {

    private $keyword;
    private $entity;

    public function __construct($keyword, $entity, $currentPage = 0) {
        parent::__construct(file_get_contents(__DIR__ . "/SearchTab.xhtml"));

        $this->keyword = addslashes($keyword);
        $this->entity = $entity;

        switch ($entity) {
            case "commento":

                $giveVOArray = (new CommentoDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__DIR__ . "/CommentCard.xhtml");
                break;

            case "specie":

                $giveVOArray = (new SpecieDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__DIR__ . "/SpecieCard.xhtml");
                break;

            case "post":
            default:

                $giveVOArray = (new PostDAO())->search("%$this->keyword%");
                $layout = file_get_contents(__DIR__ . "/PostCard.xhtml");

        }

        $this->addComponent(new GenericBrowser($giveVOArray, $layout, "search.php?", $currentPage));
    }

    public function resolveData() {
        return array(
            "{keyword}" => $this->keyword,
            "{post}" => $this->entity === 'post' ? '" class="disabled" aria-selected="true" aria-controls="panel-post" type="button' : '" aria-selected="false" type="submit',
            "{specie}" => $this->entity === 'specie' ? '" class="disabled" aria-selected="true" aria-controls="panel-post" type="button' : '" aria-selected="false" type="submit',
            "{commento}" => $this->entity === 'commento' ? '" class="disabled" aria-selected="true" aria-controls="panel-post" type="button' : '" aria-selected="false" type="submit',
            'action="search.php?entity=' . $this->entity . '"' => '',
            "{entity}" => $this->entity,
        );
    }

}