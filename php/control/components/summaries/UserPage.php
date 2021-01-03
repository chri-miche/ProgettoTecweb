<?php

    require_once __ROOT__ .'\control\components\profile\UserSide.php';
    require_once __ROOT__ .'\control\components\profile\UserDetails.php';
    require_once __ROOT__ .'\control\components\browsers\Browser.php';
    require_once __ROOT__ .'\control\components\previews\PostPreview.php';

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

            // Resolve the right bar.
            /** Potrebbe essere un'idea?.*/
            $resolvedData['{right}'] = (new UserDetails($this->user, $this->selfReference))->returnComponent(); // .

            // TODO: Make it better.
            $resolvedData['{right}'] .= (new Browser(PostElement::getUserPosts($this->user->ID), new PostPreview(
                new PostElement()), $this->selfReference.'?id=', 'PostPage.php'))->returnComponent();

            return $resolvedData;

        }


    }