<?php

require_once __DIR__ . "/userDetails/UserDetails.php";

require_once __DIR__ . "/../genericBrowser/GenericBrowser.php";

require_once __DIR__ . "/../databaseObjects/user/UserDAO.php";
require_once __DIR__ . "/../databaseObjects/post/PostDAO.php";


class Profile extends Component {

    /** @var UserVO: $user. */
    private $user;
    private $userExists;
    private $selfReference;

    public function __construct(int $id, string $selfReference, string $HTML = null) {
        parent::__construct($HTML ?? file_get_contents(__DIR__ . "/UserLayout.xhtml"));

        $this->user = (new UserDAO())->get($id);
        $this->userExists = !is_null($this->user->getId());

        if (!$this->userExists) throw new Exception('User does not exist');

        $this->selfReference = $selfReference;

    }

    public function build() {
        $html = parent::build();
        /** Se utente non esiste si viene reindirizzati a home.*/

        /** Nuovo layout prevede i dettagli in cima. Sotto due pannelli (1 amici, 1 per post)*/
        $userDetails = (new UserDetails($this->user, $this->selfReference))->returnComponent();
        $html = str_replace('<userDetails />', $userDetails, $html);


        // Barra a destra per i post utente.
        $postVOArray = (new PostDAO())->getOfUtente($this->user->getId(), 8, 0);

        $userPost = '';

        $postLayout = file_get_contents(__DIR__ . "/PostCard.xhtml");
        if (sizeof($postVOArray) > 0) {
            $userPost .= (new PreviewsPage($postVOArray, $postLayout))->returnComponent();
            $userPost .= "<a href='post_utente.php?usid=" . $this->user->getId() . "'>Vedi tutti i post dell'utente.</a>";
        } else
            $userPost .= '<img src="res\Images\data-not-found.webp" title="Nessun post trovato" alt="Questo utente non ha ancora postato nulla" />';

        return str_replace('<userPosts />', $userPost, $html);
    }
}
