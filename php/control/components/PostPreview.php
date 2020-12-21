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

        public function __construct() {

        }

        function build() {
            // TODO: Implement build() method.


        }

    }