<?php
    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\catalogo\PreviewsPage.php';

    // TODO : Move in browser.
    class GenericBrowser extends Component {

        private $previewPage;

        private $currentPage;
        private $numberPages;

        private $nextPageReference;

        public function __construct(array $elements, string $previewLayout, string $nextPageReference, int $currentPage = 0,
                                    int $elementsPerPage = 10, string $HTML = null, string $browsePageHTML = null){

            parent::__construct( $HTML ?? file_get_contents(__ROOT__.'\view\modules\browsing\Browser.xhtml'));

            $this->nextPageReference = $nextPageReference .'?page=';

            $this->currentPage = $currentPage;
            $this->numberPages = count($elements) / $elementsPerPage;

            /** Array of the current elements we consider (as we are in a given page). */
            $newElementsList = array_slice($elements, $this->numberPages * $currentPage,
                $this->numberPages * $currentPage + $elementsPerPage);

            $this->previewPage = new PreviewsPage($newElementsList, $previewLayout, $browsePageHTML);

        }

        public function resolveData(){

            $resolveData = [];

            /** Elements page.*/
            $resolveData['{elements}'] = $this->previewPage->returnComponent();

            /** Page browsing */
            $browsingList = '';

            /** We check that we actually need a given number of pages. */
            if($this->numberPages >= 1) {
                for ($i = 0; $i < $this->numberPages; $i++){

                    if ($i == $this->currentPage) // Se ci troviamo in pagina correntemente selezionata. TODO: togliere "a"
                        $browsingList .= '<a href= "" class="w3-button w3-red">' . ($i + 1) . '</a>';
                    else // stile alternativo. (link selezionabile che porta alla pagina successiva).
                        $browsingList .= '<a href="' . $this->nextPageReference . $i . '" class="w3-button">' . ($i + 1) . '</a>';

                }
            }

            $resolveData['{navigation}'] = $browsingList;
            return $resolveData;

    }
}