<?php
    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\catalogo\PreviewsPage.php';

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

    public function build() {

        $baseLayout = $this->baseLayout();

        foreach ($this->resolveData() as $key => $value)
            $baseLayout = str_replace($key, $value, $baseLayout);

        return $baseLayout;
    }

    public function resolveData(){

        $resolveData = [];

        /** Elements page.*/
        $resolveData['{elements}'] = $this->previewPage->returnComponent();

        /** Page browsing */
        $browsingList = '';

        for($i = 0; $i < $this->numberPages; $i++)

            if($i == $this->currentPage) $browsingList .= '<a href= "" class="w3-button w3-red">'. ($i + 1) .'</a>';
            else $browsingList .= '<a href="'. $this->nextPageReference .$i .'" class="w3-button">'. ($i + 1) .'</a>';

        $resolveData['{navigation}'] = $browsingList;


        return $resolveData;

    }
}