<?php

    require_once __ROOT__ . '\model\TagElement.php';

    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\browsers\BrowsePage.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';

    // TODO: Make it simply a browser and make the array be passed? This would become "Tag Browser"
    class OrdineBrowser implements Component {

        private $HTML;

        private $ordinePage;

        private $numberPages;
        private $currentPage;

        private $reference;
        private $parentReference;

        public function __construct(string $parentReference, int $page = 0, int $elementsPerPAge = 10, string $HTML = null, string $reference = null) {

            (isset($HTML)) ? $this->HTML = $HTML : $this->HTML = file_get_contents(__ROOT__.'\view\modules\BirdCatalogue.xhtml');
            (isset($reference))? $this->reference = $reference : $this->reference = '\Progetto\ProgettoTecweb\php\view\pages\catalogo\famiglia.php?id=';

            $this->currentPage = $page;
            $this->parentReference = $parentReference;

            $allTags = TagElement::ordineTags();
            $this->numberPages = count($allTags) / $elementsPerPAge;

            /** Creo un nuovo browser ad hoc. Il browser manda anche il link al quale ogni elemento si riferirà*/
            $this->ordinePage = new BrowsePage($allTags, new TagPreview(0), $elementsPerPAge, $page, $this->reference);

        }

        function build() {
            // TODO: Implement build() method.
            $HTML = $this->ordinePage->build();

            /** Page nav. The link has to be given by parent.*/
            $HTML .= '<div class="w3-bar">';
            for($i = 0; $i < $this->numberPages; $i ++){
                if($i == $this->currentPage)  $HTML .= '<a href="'. $this->parentReference .($i + 1) .'" class="w3-button w3-red">'. ($i + 1) .'</a>';
                else $HTML .= '<a href="'. $this->parentReference .$i .'" class="w3-button">'. ($i + 1) .'</a>';
            }
            
            return $HTML .= '</div>';
        }
}