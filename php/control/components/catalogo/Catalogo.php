<?php
    require_once __ROOT__ . '\control\components\Component.php';

    require_once __ROOT__ . '\model\DAO\SpecieDAO.php';

    require_once __ROOT__ . '\control\components\catalogo\GenericBrowser.php';

    class Catalogo extends Component {

        /**@var OrdineVO[] $ordineVoArray*/
        private $ordineVOArray;
        /**@var FamigliaVO[] $famigliaVOArray*/
        private $famigliaVOArray;
        /**@var GenereVO[] $genereVOArray*/
        private $genereVOArray;

        private $oSelected;
        private $ordineSelection;

        private $fSelected;
        private $famigliaSelection;

        private $gSelected;
        private $genereSelection;



        /** id della voce selezionata. */
        private $selectedOrdine;

        private $birdBrowser;

        /***
         * @param array $specieVOArray
         * @param string $selfReference
         * @param int $page
         * @param int $elemPerPage
         * @param array $ordineVOArray
         * @param array $famigliaVOArray
         * @param array $genereVOArray
         * @param bool $oSelected
         * @param bool $fSelected
         * @param bool $gSelected
         * @param int|null $ordineSelection
         * @param int|null $famigliaSelection
         * @param int|null $genereSelection
         * @param string|null $HTML
         */
        public function __construct(array $specieVOArray, string $selfReference = 'catalogo.php', int $page = 0, int $elemPerPage = 10,
                array $ordineVOArray = array(), array $famigliaVOArray = array(), array $genereVOArray = array(),
                bool $oSelected = false, bool $fSelected = false, bool $gSelected = false,
                ?int $ordineSelection = null,?int $famigliaSelection = null,?int $genereSelection = null, string $HTML = null ) {

            parent::__construct($HTML ?? file_get_contents(__ROOT__.'\view\modules\catalogo\Catalogo.xhtml'));

            $this->ordineVOArray = $ordineVOArray;
            $this->ordineSelection = $ordineSelection;
            $this->oSelected = $oSelected;

            $this->famigliaVOArray = $famigliaVOArray;
            $this->famigliaSelection = $famigliaSelection;
            $this->fSelected = $fSelected;

            $this->genereVOArray = $genereVOArray;
            $this->genereSelection = $genereSelection;
            $this->gSelected = $gSelected;

            $previewLayout = file_get_contents(__ROOT__.'\view\modules\catalogo\BirdCard.xhtml');
            $this->birdBrowser = new GenericBrowser($specieVOArray, $previewLayout, $selfReference, $page, $elemPerPage);

        }

        public function resolveData() {

            $resolvedData = [];

            $menuLayout = file_get_contents(__ROOT__.'\view\modules\catalogo\radioSelect.xhtml');

            /** Filtri applicabili.*/
            $menuLayout = str_replace("{buttonsShow}", $this->resolveButtons(), $menuLayout);

            // TODO:
            /** Se un filtro non è applicabile allora è possibile che sia navigabile come selezione.*/
            $menuLayout = str_replace("{selectTipo}", $this->resolveNavigation(), $menuLayout);

            $resolvedData['{menuLayout}'] = $menuLayout;
            $resolvedData['{contentsPage}'] = $this->birdBrowser->returnComponent();

            return $resolvedData;
        }


        private function resolveButtons(){

            $baseHTML = '';



            /** Pulsante per filtrare per ordine è da mostrare se non è selezionato un ordine corrente e se non sono abilitati gli altri due.*/
            // Se ordine non è quindi stato selezionato e non abbiamo famiglia o genere.
            if(!$this->oSelected && !($this->fSelected || $this->gSelected)) {
                $baseHTML .= "<button type='submit' name='oSelected[]' value='1'> Ordine </button>";
            } else if ($this->oSelected) /* Se quindi abbiamo già scelto la voce, dobbiamo mantenerla*/ {
                $baseHTML .= "<button class='selected-button' disabled='disabled'> Ordine </button>";
                $baseHTML .= "<input type='hidden' name='oSelected[]' value='1' />";
            } else{
                $baseHTML .= "<button class='disabled' disabled='disabled'> Ordine </button>";
            }

            /** Pulsante per filtrare per famiglia, da mostrare se non è stato scelto un gnere o se non abbiamo giò scelto lo stesso.*/
            if(!$this->fSelected && !$this->gSelected) {
                $baseHTML .= "<button type='submit' name='fSelected[]' value='1'> Famiglia </button>";
            } else if($this->fSelected) {
                $baseHTML .= "<button class='selected-button' disabled='disabled'> Famiglia </button>";
                $baseHTML .= "<input type='hidden' name='fSelected[]' value='1'/>";
            } else{
                $baseHTML .= "<button class='disabled' disabled='disabled'> Famiglia </button>";

            }

            if(!$this->gSelected) {
                $baseHTML .= "<button type='submit' name='gSelected[]' value='1'> Genere </button>";
            }  else if($this->gSelected) {
                $baseHTML .= "<button class='selected-button' disabled='disabled'> Genere </button>";
                $baseHTML .= "<input type='hidden' name='gSelected[]' value='1'/>";
            }

            $baseHTML = "<fieldset><legend> Aggiungi un filtro </legend> $baseHTML </fieldset>";

            return $baseHTML;

        }

        private function makeSelect(int $id, string $nome, string $label) : string {
            return "<label for='$id'> $label </label><select name='$nome' id='$id'>";
        }

        private function makeOption(int $id, bool $selected, bool $disabled, string $name): string {

            $baseOption = "<option value='$id' {selected}> $name </option>";
            return str_replace("{selected}", $selected ? "selected='selected'": "",$baseOption);

        }

        private function makeFullSelect(array $inArrayVO, ?int $currentSelection, bool $disabled, string $varName, string $selectVar, string $label): string{

            $resolvedHTML = '';
            $resolvedHTML .= "<button type='submit' value='1' name='$selectVar'> - </button>";
            if(!empty($inArrayVO)) {

                $firstVO = $inArrayVO[0];
                $resolvedHTML .= $this->makeSelect($firstVO->getId(), $varName, $label);

                foreach ($inArrayVO as $VO)
                    $resolvedHTML .= $this->makeOption($VO->getId(), !is_null($currentSelection) && $currentSelection == $VO->getId(), $disabled, $VO->getNomeSCientifico());

            } else {
                $resolvedHTML .= $this->makeSelect(-1, $varName, $label);
                $resolvedHTML .= $this->makeOption(-1, true, true, "Nessuna voce disponibile.");
            }


            return $resolvedHTML . "</select>";

        }

        private function resolveNavigation(){

            $resolvedHTML = '';

            $resolvedHTML .= "<fieldset><legend> Selezione voci di filtro </legend>";

            if($this->oSelected)
               $resolvedHTML .= $this->makeFullSelect($this->ordineVOArray, $this->ordineSelection,!empty($this->famigliaVOArray) || !empty($this->genereVOArray), "oValue" , "oSelected[]", "Ordine:");

            if($this->fSelected)
                $resolvedHTML .= $this->makeFullSelect($this->famigliaVOArray, $this->famigliaSelection, !empty($this->genereVOArray), "fValue","fSelected[]", "Famiglia:");

            if($this->gSelected)
                $resolvedHTML .= $this->makeFullSelect($this->genereVOArray, $this->genereSelection, false, "gValue", "gSelected[]", "Genere:");

            if($resolvedHTML != "<fieldset><legend> Selezione voci di filtro </legend>") {
                $resolvedHTML .= "<button type='submit'> Cerca </button></fieldset>";
                return $resolvedHTML;
            }
            return '';
        }

    }