<?php
require_once __DIR__ . "/../Component.php";
require_once __DIR__ . "/previewsPage/PreviewsPage.php";

class GenericBrowser extends Component {

    private $previewPage;

    private $currentPage;
    private $numberPages;

    private $nextPageReference;

    public function __construct(array $elements, string $previewLayout, string $nextPageReference, int $currentPage = 0,
                                int $elementsPerPage = 10, string $HTML = null, string $browsePageHTML = null) {

        parent::__construct($HTML ?? (
            count($elements) > 0 ? file_get_contents(__DIR__ . "/Browser.xhtml")
                : '<img src="res/images/wow-such-empty.png" alt="Questa ricerca non ha prodotto risultati" />'));

        $this->nextPageReference = $nextPageReference . 'page=';

        $this->currentPage = $currentPage;
        $this->numberPages = count($elements) / $elementsPerPage;
        $newElementsList = array_slice($elements, $elementsPerPage * $currentPage, $elementsPerPage);

        $this->previewPage = new PreviewsPage($newElementsList, $previewLayout, $browsePageHTML);

    }

    public function resolveData() {

        $resolveData = [];

        /** Elements page.*/
        $resolveData['{elements}'] = $this->previewPage->returnComponent();

        /** Page browsing */
        $browsingList = '';

        /** We check that we actually need a given number of pages. */
        if ($this->numberPages >= 1)
            for ($i = 0; $i < $this->numberPages; $i++)
                $browsingList .= $i == $this->currentPage ?
                    '<a href= "#" class="selected-button page-select">' . ($i + 1) . '</a>' :
                    '<a href="' . $this->nextPageReference . $i . '" class="page-select">' . ($i + 1) . '</a>';

        $resolveData['{navigation}'] = $browsingList;
        return $resolveData;

    }
}