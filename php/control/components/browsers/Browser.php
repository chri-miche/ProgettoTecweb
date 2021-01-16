<?php

    require_once __ROOT__ . '\control\components\browsers\BrowsePage.php';

    class Browser extends Component {

        private $pageComponent;

        private $currentPage;
        private $numberPages;

        private $nextPageReference;

        public function __construct(array $elements, string $nextPageReference, int $currentPage = 0,
                                    int $elementsPerPage = 10, string $HTML = null, string $browsePageHTML = null){

            parent::__construct(isset($HTML)? $HTML : file_get_contents(__ROOT__.'\view\modules\browsing\Browser.xhtml'));

            $this->nextPageReference = $nextPageReference;

            $this->currentPage = $currentPage;
            $this->numberPages = count($elements) / $elementsPerPage;

            $newElementsList = array_slice($elements, $this->numberPages * $currentPage,
                    $this->numberPages * $currentPage + $elementsPerPage);

            $this->pageComponent = new PreviewsPage($newElementsList, $browsePageHTML);

        }

        public function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;
        }

        public function resolveData(){

            $resolveData = [];

            /** Elements page.*/
            $resolveData['{elements}'] = $this->pageComponent->returnComponent();

            /** Page browsing */
            $browsingList = '';

            for($i = 0; $i < $this->numberPages; $i++)

                if($i == $this->currentPage) $browsingList .= '<a href= "" class="w3-button w3-red">'. ($i + 1) .'</a>';
                else $browsingList .= '<a href="'. $this->nextPageReference .$i .'" class="w3-button">'. ($i + 1) .'</a>';

            $resolveData['{navigation}'] = $browsingList;


            return $resolveData;

        }
    }