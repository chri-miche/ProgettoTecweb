<?php

    require_once __ROOT__ . '\control\components\Component.php';
    require_once __ROOT__ . '\control\components\catalogo\GenericBrowser.php';

    /** Il catalogo prende in input un array di uccelli già disposto e un array di
        ordini (id e nome) famiglia (id e nome) e di genere (id e nome).*/
    class Catalogo extends Component {

        private $birds;

        private $ordineList;
        private $currentOrdine;

        private $famigliaList;
        private $currentFamiglia;

        private $genereList;
        private $currentGenere;

        private $birdBrowser;

        /**
         * Catalogo constructor.
         * @param array $birds
         * @param string $selfReference
         * @param int $page
         * @param int $elemPerPage
         * @param array|null $ordini
         * @param array|null $famiglie
         * @param array|null $generi
         * @param int|null $ordinePosition
         * @param int|null $famigliaPosition
         * @param int|null $generePosition
         * @param string|null $HTML
         */
        public function __construct(array $birds,
                                string $selfReference, int $page = 0, int $elemPerPage = 10,
                                array $ordini = null, array $famiglie = null, array $generi = null,
                                int $ordinePosition = null, int $famigliaPosition = null, int $generePosition = null, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\catalogo\Catalogo.xhtml'));

            $this->ordineList = $ordini;
            $this->currentOrdine = $ordinePosition;

            $this->famigliaList = $famiglie;
            $this->currentFamiglia = $famigliaPosition;

            $this->genereList = $generi;
            $this->currentGenere = $generePosition;

            $previewLayout = file_get_contents(__ROOT__.'\view\modules\catalogo\BirdCard.xhtml');
            $this->birdBrowser = new GenericBrowser($birds, $previewLayout,  $selfReference, $page, $elemPerPage);
        }

        public function build(){

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;

        }

        public function resolveData() {

            $resolvedData = [];

            $resolvedData['{navigationSelection}'] = $this->resolveNavigation();
            $resolvedData['{dropdownSelection}'] = $this->resolveDropDown();

            $resolvedData['{contentsPage}'] = $this->birdBrowser->returnComponent();

            return $resolvedData;
        }

        private function resolveDropDown(){
            // TODO: str replace?
            $baseHTML = '<div class="dropdown-hover">';

            if(!isset($this->genereList)) {
                $baseHTML .= '<button class="button" disabled="disabled">+</button>
                                <div class="dropdown-content" style="width: 300px">';
                $baseHTML .= '<button type="submit" value="true" name="genereEnabled"> Genere</button>';

                if(!isset($this->famigliaList)) {

                    $baseHTML .= '<button type="submit" value="true" name="famigliaEnabled">  Famiglia</button>';

                    if(!isset($this->ordineList))
                        $baseHTML .='<button type="submit" value="true" name="ordineEnabled"> Ordine</button>';
                     else $baseHTML .= '<input hidden="hidden" value="true" name="ordineEnabled"/>';

                } else
                    $baseHTML .= '<input  hidden="hidden" value="true" name="famigliaEnabled"/>';

                $baseHTML .= '</div>';

            } else
                $baseHTML .= '<input  hidden="hidden" value="true" name="genereEnabled"/>';

            return $baseHTML . '</div>';

        }

        private function resolveNavigation(){

            $resolvedHTML = '';

            if(isset($this->ordineList)) {
                /* Mostriamo gli ordini da scegliere se è possibile tale scelta.
                Però prima si guarda se si ha dentro la family navigation o la genere navigation. */
                if (isset($this->famigliaList) || isset($this->genereList))
                    /** Siamo in condizione di non selezionabilità.*/
                    // Show the cardboard of the current selection. It also gives the input of the selction as hidden
                    $resolvedHTML .= "<div>" . reset($this->ordineList) . "</div> 
                        <input hidden='hidden' value='" . key($this->ordineList) . "' name='ordineValue' />
                        <input hidden='hidden' value='' name='ordineEnabled' />";

                else {
                    /** Siamo in condizione di selezionabilità quindi mettiamo tutto.*/
                    // TODO: Make a function out of it.
                    /* Pulsante per rimuovere la voce.*/
                    $resolvedHTML .= '<button type="submit"  value="true" name="ordineDisable"> - </button>';
                    /* Tutte le possibili selezioni del menu.*/
                    $resolvedHTML .= '<select name="ordineValue" id="ordineValue">';
                    echo $this->currentOrdine;
                    foreach ($this->ordineList as $key => $value)
                        if($key == $this->currentOrdine)
                            $resolvedHTML .= "<option value='$key' selected='selected'> $value </option>";
                        else
                            $resolvedHTML .=  "<option value='$key'> $value </option>";

                    $resolvedHTML .= '</select>';

                }
            }

            if(isset($this->famigliaList)){
                /* Mostriamo le famiglie da scegleiere se è possibile tale scelta.*/
                if(isset($this->genereList)) /** L'utente ha già selezionato dei generi*/
                    $resolvedHTML .= "<div>". reset($this->famigliaList) ."</div> 
                        <input hidden='hidden' value='". key($this->famigliaList) ."' name='famigliaValue' />
                        <input hidden='hidden' value='' name='famigliaEnabled' />";

                else{
                    $resolvedHTML .= '<button type="submit" value="true" name="famigliaDisable"> - </button>';
                    /* Tutte le possibili selezioni del menu.*/
                    $resolvedHTML .= '<select name="famigliaValue" id="famigliaValue">';

                    foreach ($this->famigliaList as $key => $value)
                        if($key == $this->currentFamiglia)
                            $resolvedHTML .= "<option value='$key' selected='selected'> $value </option>";
                        else
                            $resolvedHTML .=  "<option value='$key'> $value </option>";

                    $resolvedHTML .= '</select>';
                }

            }

            if(isset($this->genereList)){
                /* Mostriamo le famiglie da scegleiere se è possibile tale scelta.*/

                $resolvedHTML .= '<button type="submit" value="true" name="genereDisable"> - </button>';
                /* Tutte le possibili selezioni del menu.*/
                $resolvedHTML .= '<select name="genereValue" id="genereValue">';
                foreach ($this->genereList as $key => $value)
                    if($key == $this->currentGenere)
                        $resolvedHTML .= "<option value='$key' selected='selected'> $value </option>";
                    else
                        $resolvedHTML .=  "<option value='$key'> $value </option>";

                $resolvedHTML .= '</select>';

            }

            return $resolvedHTML ;
        }

    }