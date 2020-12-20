<?php

    /** How should a report work?
     * If it was laready reported you cannot report it again so error should pop up.
     * If post does not exist error should pop up. */

    require_once __ROOT__.'\model\PostElement.php';
    require_once __ROOT__.'\model\UserElement.php';
    require_once __ROOT__.'\model\ReportElement.php';
    require_once __ROOT__.'\control\components\Component.php';

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
            if(isset($_GET['pid']))
                $this->post = new PostElement($_GET['pid']);

        }

        function build() {

            if(isset($this->post) && $this->post->exists()
                        && !$this->post->isArchived) {

                if ($this->user->userIdentified()) {

                    if ($this->report->userReported($this->user->getUser()->ID, $this->post->ID)) {
                        return 'You already reported this post.';

                    } else { return 'Report this post.';}

                }else { return 'Identifiy youself.';}

            } else { return 'The selected post does not exist or has been archived. Something went wrong!'; }


        }
    }