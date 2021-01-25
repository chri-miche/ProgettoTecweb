<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "control" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "summaries" . DIRECTORY_SEPARATOR . "PageFiller.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "SpecieDAO.php";

    class BirdSummary extends PageFiller {

        /** Bird data element, the join of all his parents. (maximum data) */
        private $specieVO;
        private $catalogoReference;

        public function __construct($id, string $catalogoReference = 'catalogo.php', string $HTML = null){

            parent::__construct(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "BirdSummary.xhtml"));

            $this->specieVO = (new SpecieDAO())->get($id);

            /** Porta alla pagina del catalogo.*/
            $this->catalogoReference = $catalogoReference;


        }

        function build() {
            // Pagina statica quindi ci sono solo sostituzioni.
            if(!($this->specieVO->getId())) header('Location: Catalogo.php');

            /** Swap variables in fields.*/
            return parent::build();

        }

        public function resolveData(){

            $swapData = [];

            foreach ($this->specieVO->arrayDump() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;

           /** Link al catalogo.*/
            $swapData['{refOrdine}'] = $this->catalogoReference ."?oSelected%5B%5D=1&&oValue=".
                $this->specieVO->getGenereVO()->getFamigliaVO()->getOrdineVO()->getId();

            $swapData['{refFamiglia}'] = $this->catalogoReference."?oSelected%5B%5D=1&fSelected%5B%5D=1&fValue=".
                $this->specieVO->getGenereVO()->getFamigliaVO()->getId();

            $swapData['{refGenere}'] = $this->catalogoReference ."?oSelected%5B%5D=1&fSelected%5B%5D=1&gSelected%5B%5D=1&gValue="
                . $this->specieVO->getGenereVO()->getId();

            return $swapData;

        }

    }