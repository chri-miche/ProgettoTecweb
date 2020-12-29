<?php
    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    require_once __ROOT__.'\model\BirdElement.php';

    class BirdSummary extends PageFiller {

        /** Bird data element, the join of all his parents. (maximum data) */
        private $bird;
        /** Array associativo dei campi.*/
        private $birdFields;

        private $HTML;

        private $ordineReference;
        private $famigliaReference;
        private $genereReference;

        public function __construct($id, string $HTML = null, string $specieReference = '#', string $genereReference = 'Specie.php?id=',
                                    string $famigliaReference = 'Genere.php?id=' , string $ordineReference = 'Famiglia.php?id='){

            $this->HTML = file_get_contents(__ROOT__.'\view\modules\BirdSummary.xhtml');
            $this->bird = new BirdElement($id);

            /** Non ricordo le chiavi dell'array*/
            print_r($this->bird);

            /** References to the pages to browse the catalogue.*/
            $this->ordineReference = $ordineReference;
            $this->famigliaReference = $famigliaReference;
            $this->genereReference = $genereReference;

            foreach ($this->bird->getData() as $key => $value){
                $this->birdFields['{'.$key.'}'] = $value;
            }


        }

        function build() {
            // Pagina statica quindi ci sono solo sostituzioni.
            if(!($this->bird->exists()))  return 'Bird does not exist in our database'; // TODO: Make error page?

            /** Swap variables in fields.*/
            foreach ($this->resolveData() as $key => $value)
                $this->HTML = str_replace($key, $value, $this->HTML);

            /** TODO: Create table for the residence.*/

            return $this->HTML;

        }

        public function resolveData(){

            $swapData = [];

            foreach ($this->bird->getData() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;

           /** References to the previous pages.*/
            $swapData['{refOrdine}'] = $this->ordineReference;
            $swapData['{refFamiglia}'] = $this->famigliaReference;
            $swapData['{refGenere}'] = $this->genereReference;

            return $swapData;

        }

    }