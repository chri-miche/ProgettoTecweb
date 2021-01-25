<?php
require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DatabaseAccess.php";
require_once "PostCard.php";

class Feed extends Component {

    private $basePage;
    private $criteria;

    // criteria can be popularity time controviersial
    public function __construct(string $criteria) {
        // construct parent
        parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "feed" . DIRECTORY_SEPARATOR . "Feed.xhtml"));

        $query = 'select * from vw_post ';

        $orderby = 'order by ';
        switch ($criteria) {
            case 'popularity':
                $orderby .= 'likes';
                break;
            case 'time':
                $orderby .= 'data';
                break;
            case 'controversial':
                $orderby .= 'commenti';
                break;
            default:
                $orderby = '';
        }

        $results = DatabaseAccess::executeQuery($query . $orderby . ' desc limit 10;');
        $this->basePage = new BasePage($this->baseLayout());

        foreach ($results as $result)
            $this->basePage->addComponent(new PostCard($result['contentID']));

        $this->criteria = $criteria;
    }

    public function build() {

        $HTML = $this->basePage->build();
        $HTML = str_replace('{feed}', $this->criteria, $HTML);
        $HTML = str_replace('href="Home.php?feed=' . $this->criteria . '" aria-selected="false"', 'href="#panel-'. $this->criteria . '" aria-selected=true', $HTML);
        return $HTML;
    }
}