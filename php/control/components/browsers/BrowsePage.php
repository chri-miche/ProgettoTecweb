<?php

    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\previews\Preview.php';

    class BrowsePage extends  Component {

        /** List of all previews.*/
        private $previews;

        private $elementsPerPage;
        private $currentPage;

        public function __construct(array $ids, Preview $type, int $elementPerPage, int $page, string $reference, string $HTML = null) {

           parent::__construct(isset($HTML) ? $HTML : 'Nothing to see here.');

            $this->elementsPerPage = $elementPerPage;
            $this->currentPage = $page;

            $this->previews = array(); // List of each preview element. How to build it?

            if(isset($ids)){/** $ids are defined */
                /** i starts where we left. It displays max $elementsPerPage*/
                for($i = $page * $elementPerPage; $i < ($page * $elementPerPage) + $elementPerPage; $i++){
                    if(isset($ids[$i])) $this->previews [] = new $type($ids[$i], $reference);
                }
            }

        }

        function build() {

            $HTML = '';
            if(!isset($this->previews))  return 'Non ci sono elmeenti da visualizzare';

            foreach ($this->previews as $preview)
                $HTML .= $preview->build();

            return $HTML;

        }

    }