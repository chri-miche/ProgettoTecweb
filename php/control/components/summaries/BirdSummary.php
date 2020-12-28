<?php
    require_once __ROOT__.'\control\components\Component.php';
    require_once __ROOT__.'\model\BirdElement.php';

    class BirdSummary implements Component {

        /** Bird data element, the join of all his parents. (maximum data) */
        private $bird;

        private $HTML;

        private $ordineReference;
        private $famigliaReference;
        private $genereReference;

        public function __construct($id, string $HTML = null, string $specieReference = '#', string $genereReference = 'Specie.php?id=',
                                    string $famigliaReference = 'Genere.php?id=' , string $ordineReference = 'Famiglia.php?id='){

            $this->HTML = file_get_contents(__ROOT__.'\view\modules\BirdSummary.xhtml');

            $this->bird = new BirdElement($id);

            /** Non ricordo le chiavi dell'array*/
            //print_r($this->bird);

            /** References to the pages to browse the catalogue.*/
            $this->ordineReference = $ordineReference;
            $this->famigliaReference = $famigliaReference;
            $this->genereReference = $genereReference;


        }

        function build() {  // Pagina statica quindi ci sono solo sostituzioni.

            if(!($this->bird->exists()))
                return 'Bird does not exist in our database'; // TODO: Make error page?

            $this->HTML = str_replace('{nomeScientifico}', $this->bird->nomeScientifico, $this->HTML);
            $this->HTML = str_replace('{nomeNonScientifico}', $this->bird->nomeNonScientifico, $this->HTML);

            $this->HTML = str_replace('{pesoMedio}', $this->bird->pesoMedio, $this->HTML);
            $this->HTML = str_replace('{altezzaMedia}', $this->bird->altezzaMedia, $this->HTML);

            $this->HTML = str_replace('{statoEstinzione}', $this->bird->nome, $this->HTML);
            $this->HTML = str_replace('{conservazioneID}', $this->bird->conservazioneID, $this->HTML);


            // TODO: Add references.
            $this->HTML = str_replace('{ordine}', $this->bird->nomeOrdine, $this->HTML);


            $this->HTML = str_replace('{famiglia}', $this->bird->nomeFamiglia, $this->HTML);
            $this->HTML = str_replace('{refFamiglia}', $this->famigliaReference . $this->bird->famID, $this->HTML);


            $this->HTML = str_replace('{genere}', $this->bird->nomeGenere, $this->HTML);
            $this->HTML = str_replace('{specie}', $this->bird->nomeScientifico, $this->HTML);


            $this->HTML = str_replace('{descrizione}', $this->bird->descrizione, $this->HTML);

            // TODO: End adding stuff here.

            return $this->HTML;

        }
    }