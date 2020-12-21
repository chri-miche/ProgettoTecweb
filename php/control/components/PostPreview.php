<?php


    /** Makes the preview (title half image and author of each post + votes.
        On clikc you got to the selected page. 10 are displayed per page.*/
    class PostPreview implements Component {
    /**  Hot -> we have to look for the newest with most voted (time/votes)
     *   Interests -> we check the user tags prefered and users he follows,
     *   New -> by newest psots
     *   Best ->Highest rated posts of the Week.
        TODO: Consider this: We don't need the postPreview to have an array of ids
         but maybe the best way is to have a PostPreview class  single id + a class of
         BrowsePosts compinent that takes based on these stuff or maybe (search) cause it's basically a search.
     */

        /**Nah  TODO: Consider if PostElement can be abstract class too and have FullPost implementation or Preview.
            struct {
         *      PostElement : post;
         *      UserElement : writer
         * } posts
         *
         */
        private $HTML;

        private $post;
        private $creator;

        public function __construct(int $pid) {
            //  Get the HTML from builder? Might be the way to go.
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\PostPreview.xhtml');

            if(PostElement::checkID($pid)){
                $this->post = new PostElement($pid);
                if(UserElement::checkID($this->post->userID))
                    $this->creator = new UserElement($this->post->userID);

            }
        }

        public function build() {
            // TODO: Avoid dependency.

            $this->HTML = file_get_contents(__ROOT__.'\view\modules\PostPreview.xhtml');

            $this->HTML = str_replace("{TITOLO}", $this->post->title,  $this->HTML);
            $this->HTML = str_replace('{NOME_UTENTE}', '<a href="user.php?id='. $this->creator->ID .'">
                                '. $this->creator->nome. '</a>',  $this->HTML);

            return  $this->HTML;


        }

    }