<?php
require_once __ROOT__.'\model\DatabaseAccess.php';
require_once 'PostCard.php';

class Feed extends Component {

    private $basePage;

    // criteria can be popularity time controviersial
    public function __construct(string $criteria) {
        // construct parent
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\feed\Feed.xhtml'));

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
        $results = DatabaseAccess::executeQuery($query . $orderby . ' limit 10;');
        $this->basePage = new BasePage($this->baseLayout());

        foreach ($results as $result) {
            $this->basePage->addComponent(new PostCard($result['contentID']));
        }
    }

    public function build() {
        return $this->basePage->build();
    }
}