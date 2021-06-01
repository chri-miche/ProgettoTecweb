<?php
require_once __DIR__ . "/../databaseObjects/DatabaseAccess.php";
require_once __DIR__ . "/postCard/PostCard.php";

require_once __DIR__ . "/../databaseObjects/user/UserDAO.php";
require_once __DIR__ . "/../databaseObjects/post/PostDAO.php";

class Feed extends Component {

    private $basePage;
    private $criteria;

    // criteria can be popularity time controviersial
    public function __construct(string $criteria, SessionUser $user) {
        // construct parent
        parent::__construct(file_get_contents($user->userIdentified() ? __DIR__ . "/FriendFeed.xhtml" : __DIR__ . "/Feed.xhtml"));
        $results = array();

        $this->basePage = new BasePage($this->baseLayout());

        if ($criteria == 'friends') {
            $currentUserVO = $user->getUser();
            $friendList = (new UserDAO())->getFriends($currentUserVO);

            $postDAO = new PostDAO();
            foreach ($friendList as $friend)
                $results = array_merge($results, $postDAO->getOfUtente($friend->id, 10, 0));

            usort($results, function ($first, $second) {
                if ($first->date == $second->date) return 0;
                return ($first->date > $second->date) ? -1 : 1;
            });

            $results = array_slice($results, 0, 15);
            shuffle($results);

            /* Prendo fino a max 20 di results.*/
            foreach ($results as $result) {
                $this->basePage->addComponent(new PostCard($result->getId()));
            }


        } else {

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

            foreach ($results as $result)
                $this->basePage->addComponent(new PostCard($result['content_id']));
        }
        $this->criteria = $criteria;
    }

    public function build() {

        $HTML = $this->basePage->build();
        $HTML = str_replace('{feed}', $this->criteria, $HTML);
        $HTML = str_replace('href="index.php?feed=' . $this->criteria . '" aria-selected="false"', 'href="#panel-' . $this->criteria . '" aria-selected="true" aria-controls="panel-' . $this->criteria . '" class="disabled"', $HTML);
        return $HTML;
    }
}