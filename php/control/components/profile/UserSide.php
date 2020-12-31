<?php


    require_once __ROOT__.'\model\TagElement.php';
    require_once __ROOT__.'\model\UserElement.php';

    require_once __ROOT__.'\control\components\Component.php';

    class UserSide extends Component {

        private $users;
        private $userReference;

        private $tags;
        private $tagReference;

        // TODO: Set default values.
        public function __construct(int $id, int $maxFriendElements = 10, int $maxTagElements = 30, string $HTML = null, string $userReference = null, string $tagReference = null) {
            parent::__construct(isset ($HTML) ?  $HTML : file_get_contents(__ROOT__.''));

            $this->users= UserElement::getFriends($id, $maxFriendElements);
            $this->userReference = $userReference;

            // TODO : Implements TagElement getInterests
            $this->tags = TagElement::getInterests($id, $maxTagElements); // Get full array of data to avoid multple queries.
            $this->tagReference = $tagReference;

        }

        public function build() {
            // TODO: Implement build() method.
            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);


        }



        public function resolveData() {

            $list = '';
            /** Creates tags list of every followedtag. Limit to a number?.*/
            foreach ($this->tags as $tag) $list .= (new LinkRow($this->tagReference. '?id='. $tag->ID, $tag->nome))->build();
            $resolveData['{tags}'] = $list;


            $list = '';
            // TODO: Limit the seen users in side. And give a button to see all that brings you to the page of friends.
            foreach ($this->users as $friend) $list .=(new LinkRow($this->userReference .'?id='. $friend->ID, $friend->nome, $friend->immagineProfilo))->build();
            $resolveData['{users}'] = $list;

            return $resolveData;


        }


    }