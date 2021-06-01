<?php
require_once __DIR__ . "/../PageFiller.php";
require_once __DIR__ . "/../databaseObjects/specie/SpecieDAO.php";

class BirdSummary extends PageFiller {

    /** Bird data element, the join of all his parents. (maximum data) */
    private $specieVO;
    private $catalogoReference;

    public function __construct(SpecieVO $specie, string $catalogoReference = 'catalogo.php', string $HTML = null) {
        parent::__construct(file_get_contents($HTML ?? __DIR__ . "/BirdSummary.xhtml"));

        $this->specieVO = $specie;
        if (!($this->specieVO->getId())) throw new Error('Uccello non esistente!');

        $this->catalogoReference = $catalogoReference;
    }

    function build() {
        return parent::build();
    }

    public function resolveData() {

        $swapData = [];

        foreach ($this->specieVO->arrayDump() as $key => $value)
            if (!is_array($value)) $swapData['{' . $key . '}'] = $value;

        /** Link al catalogo.*/
        $swapData['{refOrdine}'] = $this->catalogoReference . "?oSelected%5B%5D=1&amp;oValue=" .
            $this->specieVO->getGenereVO()->getFamigliaVO()->getOrdineVO()->getId();
        // Oddio eretico
        $swapData['{refFamiglia}'] = $this->catalogoReference . "?oSelected%5B%5D=1&amp;fSelected%5B%5D=1&amp;fValue=" .
            $this->specieVO->getGenereVO()->getFamigliaVO()->getId();

        $swapData['{refGenere}'] = $this->catalogoReference . "?oSelected%5B%5D=1&amp;fSelected%5B%5D=1&amp;gSelected%5B%5D=1&amp;gValue="
            . $this->specieVO->getGenereVO()->getId();
        return $swapData;

    }

}