<?php

    /** How should a report work?
     * If it was laready reported you cannot report it again so error should pop up.
     * If post does not exist error should pop up. */

    class Report implements Component {

        private  $HTML;

        private $user; private $post;

        /* The new created report.*/
        private $report;

        public function __construct(string $HTML = null){

            ($HTML) ? $this->HTML = $HTML :
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\Post.xhtml');

            $this->user = new SessionUser();
            $this->report = new ReportElement();

            /** TODO: Check if second isset does work, prolly not.*/
            if(isset($_GET['postID']) && isset($this->user)) {

                $this->post = new PostElement($_GET['postID']);
                /** We can create a new report if and only if
                    a user is signed in and the post we want to report exists.*/

                if($this->report->userReported($this->user->ID, $this->post->ID))
                    $this->report->loadElement(array($this->user->ID, $this->post->ID));

            }
        }

        function build() {

            if(isset($this->post)){


            } else {

                return 'No post was selected. Something went wrong!';

            }


        }
    }