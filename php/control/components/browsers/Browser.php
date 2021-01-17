<?php

    require_once __ROOT__ . '\control\components\browsers\BrowsePage.php';
    require_once "NavigationButton.php";

    class Browser extends Component {

        private $pageComponent;

        private $currentPage;
        private $numberPages;

        private $itemReference;
        private $nextPageReference;

        public function __construct(array $elements,
                                    Preview $type,
                                    string $nextPageReference,
                                    string $reference,
                                    int $currentPage = 0,
                                    int $elementsPerPage = 10,
                                    string $HTML = null,
                                    string $browsePageHTML = null){

            parent::__construct(isset($HTML)? $HTML : file_get_contents(__ROOT__.'\view\modules\browsing\Browser.xhtml'));

            $this->nextPageReference = $nextPageReference;
            $this->itemReference = $reference;

            $this->currentPage = $currentPage;
            $this->numberPages = count($elements) / $elementsPerPage;

            $this->pageComponent = new BrowsePage($elements, $type, $elementsPerPage, $currentPage, $reference, $browsePageHTML);

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
            $resolveData['{elements}'] = $this->pageComponent->build();

            /** Page browsing */
            $browsingList = '';

            for($i = 0; $i < $this->numberPages; $i++) $browsingList .= (new NavigationButton(
                $i + 1,
                $this->nextPageReference . $i,
                $i == $this->currentPage
            ))->build();

                /*
                if($i == $this->currentPage) $browsingList .= '<a href= "" class="w3-button w3-red">'. ($i + 1) .'</a>';
                else $browsingList .= '<a href="'. $this->nextPageReference .$i .'" class="w3-button">'. ($i + 1) .'</a>';
*/
            $resolveData['{navigation}'] = $browsingList;

            return $resolveData;

        }
    }