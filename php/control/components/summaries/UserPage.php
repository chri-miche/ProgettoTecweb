<?php

    require_once __ROOT__ .'\control\components\profile\UserSide.php';
    require_once __ROOT__ .'\control\components\profile\UserDetails.php';

    require_once __ROOT__ .'\control\components\catalogo\GenericBrowser.php';

    require_once __ROOT__ .'\model\UserElement.php';
    require_once __ROOT__ .'\model\PostElement.php';
    // User page has: A left side bar (user side) a right content field ( description + Posts)
    class UserPage extends Component {

        // Id of the user on the current page.
        private $user; // TODO: Consider, might not be needed.
        private $selfReference;

        // TODO: Get current selected posts page. Or add link to see all posts? If we add link to see all posts we display
        //  just one BrowsePage with a number of elements. Link rediredts to Browser of pages of a selected user?.
        public function __construct(int $id, string $selfReference, string $HTML = null){
            parent::__construct(isset ($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\user\UserLayout.xhtml'));

            $this->user = new UserElement($id);

            $this->selfReference = $selfReference;

        }

        public function build(){

            $HTML = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $HTML = str_replace($key, $value, $HTML);

            return $HTML;
        }

        public function resolveData(){

            $resolvedData = [];

            // Resolve the left bar.
            $resolvedData['{left}'] = (new UserSide($this->user))->returnComponent();

            $resolvedData['{right}'] = (new UserDetails($this->user, $this->selfReference))->returnComponent();

            /** Title of the coming stuff. We have now a list of posts previews. At the end of the limited previews we
             add a reference to "see all Posts" in order to browse them all. */
            // TODO: Move style to css.
            $resolvedData['{right}'] .= '<div style=" text-align: center; padding: 0.5em; margin: 1em 5em;" class="primary-color"> Post pubblicati dall utente: </div>';



            $posts = PostElement::getUserPosts($this->user->ID, 10);

            $showedPosts = [];
            foreach ($posts as $post) $showedPosts[] = $post->getData();

            $postLayout = file_get_contents(__ROOT__.'\view\modules\user\PostCard.xhtml');

            if(sizeof($showedPosts) > 0) {
                // We build a single page preview.
                $resolvedData['{right}'] .= (new PreviewsPage($showedPosts, $postLayout))->returnComponent();
                // TODO: Move style.
                $resolvedData['{right}'] .= '<div class="" style=" margin: 1em 5em;text-align:center"> 
                    <a href="postUtente.php?usid='. $this->user->ID.'"> Vedi tutti i post dell utente </a> </div> ';

            } else {

                $resolvedData['{right}'] .= '<div style="width: 100%; text-align: center"><img src="\res\images\wow-such-empty.png" style="height: 100%"> </img></div>';

            }

            return $resolvedData;

        }


    }