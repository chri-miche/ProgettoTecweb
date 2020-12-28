<?php

    require_once __ROOT__ . '\model\TagElement.php';

    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\browsers\BrowsePage.php';

    require_once __ROOT__ . '\control\components\previews\TagPreview.php';

    // TODO: Make it simply a browser and make the array be passed? This would become "Tag Browser"
    class TagBrowser implements Component {

        private $HTML;

        private $ordinePage;

        private $numberPages;
        private $currentPage;

        private $reference;
        private $parentReference;

        public function __construct($ids, string $parentReference, int $page = 0, int $elementsPerPAge = 10, string $reference = null, string $HTML = null) {

            (isset($HTML)) ? $this->HTML = $HTML : $this->HTML = file_get_contents(__ROOT__.'\view\modules\BirdCatalogue.xhtml');
            (isset($reference))? $this->reference = $reference : $this->reference = '';

            $this->currentPage = $page;
            $this->parentReference = $parentReference;

            $this->numberPages = count($ids) / $elementsPerPAge;

            /** Creo un nuovo browser ad hoc. Il browser manda anche il link al quale ogni elemento si riferirà*/
            $this->ordinePage = new BrowsePage($ids, new TagPreview(0), $elementsPerPAge, $page, $this->reference);

        }

        function build() {
            // TODO: Implement build() method.

            /** Page nav. The link has to be given by parent.*/
            $HTML = '<div class="" style="
                        margin-top: 3vh; width:80%; display: flex; justify-content: center; flex-wrap: wrap; align-self: center">';

            $HTML .= $this->ordinePage->build();
            $HTML .=  '  <div style="width: 100%;"></div>'; //https://stackoverflow.com/questions/45086899/flexbox-item-wrap-to-a-new-line
            $HTML .= '<div  class ="w3-bar w3-margin w3-border-top w3-padding"  style="width: 60%; display: flex; justify-content: center;">';

            for($i = 0; $i < $this->numberPages; $i ++){
                if($i == $this->currentPage)  $HTML .= '<a href= "" class="w3-button w3-red">'. ($i + 1) .'</a>';
                else $HTML .= '<a href="'. $this->parentReference .$i .'" class="w3-button">'. ($i + 1) .'</a>';
            } $HTML .= '</div>';


            return $HTML .= '</div>';
        }
}