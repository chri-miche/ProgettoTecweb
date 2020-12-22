<?php

    require_once __ROOT__ . '\control\components\previews\PostPreview.php';
    // TODO: Check the PostPreview Class.
    class BrowsePosts implements Component {

        private $postPreviews; /*Array of PostPreviews that gets built.*/

        private $elementsPerPage;
        private $currentPage;

        private $selectionMethod;/* : Hot, New, Interests, Best*/

        public function __construct(int $elementPerPage = 10, int $page = 0, $elements = 'New') {
            /*Needed? Yes if we want to give next page.*/
            $this->elementsPerPage = $elementPerPage;
            $this->currentPage = $page;

            $this->selectionMethod = $elements;

            $app = $this->getOfSelectedMethod();
            foreach ($app as $pid) $this->postPreviews[] = new PostPreview($pid);

        }

        function build() {
            // TODO: Add page button and add mode selector.
            $HTML = '<div class="w3-card-4" style="width: 70%;"><div class="w3-container"><h1> New Posts </h1></div>';

            foreach ($this->postPreviews as $postPreview) $HTML .= $postPreview->build();
            return $HTML . '</div>';

        }

        private function getOfSelectedMethod(){

            if($this->selectionMethod == 'Hot')
                return array(); // TODO: implement.

             else if($this->selectionMethod == 'New')
                return PostElement::getNewest($this->elementsPerPage, $this->elementsPerPage * $this->currentPage);

            else if($this->selectionMethod == 'Interests')
                return array();// TODO: implement the interests.

            else if ($this->selectionMethod == 'Best')
                return array();// TODO :: implement

            else return array(); /**Throw exception ?*/
        }

    }