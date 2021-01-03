<?php


    require_once __ROOT__.'\model\TagElement.php';
    require_once __ROOT__.'\model\UserElement.php';

    require_once __ROOT__.'\control\components\Component.php';

    class UserSide extends Component {

        private $user;

        private $users;
        private $userReference;
        private $friendsDisplayLimit;

        private $tags;
        private $tagReference;
        private $tagsDisplayLimit;

        // TODO: Set default values. Rsolve also profile picture and role. Add two more references. Friend list and followed list.
        public function __construct(UserElement $user, int $maxFriendElements = 10, int $maxTagElements = 30, string $HTML = null,
                                    string $userReference = 'UserPage.php', string $tagReference = 'Search.php') {

            parent::__construct(isset ($HTML) ?  $HTML : file_get_contents(__ROOT__.'\view\modules\user\UserSide.xhtml'));

            $this->user = clone $user;

            $this->users= UserElement::getFriends($this->user->ID, $maxFriendElements);
            $this->userReference = $userReference;

            $this->tags = TagElement::getInterests($this->user->ID, $maxTagElements); // Get full array of data to avoid multple queries.
            $this->tagReference = $tagReference;

            $this->friendsDisplayLimit = $maxFriendElements;
            $this->tagsDisplayLimit = $maxTagElements;

        }

        public function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;

        }



        public function resolveData() {

            $resolveData = [];

            $resolveData['{immagineProfilo}'] = $this->user->immagineProfilo;

            $resolveData['{tipologiaUtente}'] = $this->user->getModerator() ? $this->user->getAdmin() ?
                '<div style="width: 100%; background-color: #034b72; padding: 1em"> Amministratore </div>' : 'Moderatore' : null; // TODO: Add style.

            $list = '';
            /** List of followed tags.*/
            for($i = 0; $i < $this->tagsDisplayLimit &&  $i < sizeof($this->tags); $i++)
                $list .= (new LinkRow($this->tagReference. '?id='. $this->tags[$i]->ID, $this->tags[$i]->nome))->build();

            $resolveData['{tags}'] = $list;

            /** Se ho più di quanto posso visualizzare mandami alla pagina: Tutti i tag seguiti.*/
            $resolveData['{tagReference}'] = sizeof($this->tags) >= $this->tagsDisplayLimit ?
                '<a href ="UserTags.php?id='. $this->user->ID .'"> Vedi altri tag </a>' : null;


            $list = '';
            /** List of followed users.*/
            for($i = 0; $i < $this->friendsDisplayLimit && $i < sizeof($this->users); $i++)
                $list .=(new LinkRow($this->userReference .'?id='. $this->users[$i]->ID, $this->users[$i]->nome,
                    $this->users[$i]->immagineProfilo))->build();

            $resolveData['{users}'] = $list;

            /** Se ho più di quanto posso visualizzare mandami alla pagina: Tutti gli utenti seguiti.*/
            $resolveData['{friendsReference}'] = sizeof($this->users) >= $this->friendsDisplayLimit ?
                '<a href="UserFriends.php?id='. $this->user->ID . '"> Vedi altri amici</a>' : null;



            return $resolveData;


        }


    }