<?php

    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\previews\Preview.php';

    // TODO: Add container that has all elements.
    class BrowsePage extends  Component {

        /** List of all previews.*/
        private $previews;

        private $elementsPerPage;
        private $currentPage;

        public function __construct(array $ids, Preview $type, int $elementPerPage, int $page, string $reference, string $HTML = null) {

           parent::__construct(isset($HTML) ? $HTML : '<div style="display: flex; flex-wrap: wrap;  padding-left: 3em; padding-right: 3em;"> {posts} </div>');

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

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;

        }

        function resolveData(){

            $resolvedData = [];

            $previewHTMl = '';
            foreach ($this->previews as $preview)
                $previewHTMl .= $preview->build();

            $resolvedData['{posts}'] = $previewHTMl;
            return$resolvedData;

        }

    }