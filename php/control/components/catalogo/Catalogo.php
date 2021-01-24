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

        private $ordineSelection;
        private $famigliaSelection;
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
         * @param string|null $HTML
         * @param int|null $ordineSelection
         * @param int|null $famigliaSelection
         * @param int|null $genereSelection
         */
        public function __construct(array $specieVOArray, string $selfReference = 'catalogo.php', int $page = 0, int $elemPerPage = 10,
                array $ordineVOArray = array(), array $famigliaVOArray = array(), array $genereVOArray = array(),
                ?int $ordineSelection = null,?int $famigliaSelection = null,?int $genereSelection = null, string $HTML = null ) {

            parent::__construct($HTML ?? file_get_contents(__ROOT__.'\view\modules\catalogo\Catalogo.xhtml'));

            /** Ottenimento di Genre, Famiglia, Ordini vettore.
             * Se ha grandezza 1 non è selezionabile.*/
            $this->ordineVOArray = $ordineVOArray;
            $this->ordineSelection = $ordineSelection;

            $this->famigliaVOArray = $famigliaVOArray;
            $this->famigliaSelection = $famigliaSelection;

            $this->genereVOArray = $genereVOArray;
            $this->genereSelection = $genereSelection;

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
            $menuLayout = str_replace("{selections}", $this->resolveNavigation(), $menuLayout);

            $resolvedData['{menuLayout}'] = $menuLayout;
            $resolvedData['{contentsPage}'] = $this->birdBrowser->returnComponent();

            return $resolvedData;
        }


        private function resolveButtons(){

            $baseHTML = '';

            $empty = true;

            /** Pulsante per filtrare per ordine è da mostrare se non è selezionato un ordine corrente e se non sono abilitati gli altri due.*/
            // Se ordine non è quindi stato selezionato e non abbiamo famiglia o genere.
            if(is_null($this->ordineSelection) && empty($this->ordineVOArray) && empty($this->famigliaVOArray)  && empty($this->genereVOArray)) {
                $baseHTML .= "<button type='submit' name='ordineEnabled' value='true'> Ordine </button>";
                $empty = false;
            } else if (!is_null($this->ordineVOArray) || !empty($this->ordineVOArray)) /* Se quindi abbiamo già scelto la voce, dobbiamo mantenerla*/
                $baseHTML .= "<input type='hidden' name='ordineEnabled' value='true' />";

            /** Pulsante per filtrare per famiglia, da mostrare se non è stato scelto un gnere o se non abbiamo giò scelto lo stesso.*/
            if(is_null($this->famigliaSelection) && empty($this->famigliaVOArray) && empty($this->genereVOArray)) {
                $baseHTML .= "<button type='submit' name='famigliaEnabled' value='true'> Famiglia </button>";
                $empty = false;
            } else if(!is_null($this->famigliaSelection) || !empty($this->famigliaVOArray))
                $baseHTML .= "<input type='hidden' name='famigliaEnabled' value='true'/>";

            if(is_null($this->genereSelection) && empty($this->genereVOArray)) {
                $baseHTML .= "<button type='submit' name='genereEnabled' value='true'> Genere </button>";
                $empty = false;
            }  else if(!is_null($this->genereVOArray)|| !empty($this->genereVOArray))
                $baseHTML .= "<input type='hidden' name='genereEnabled' value='true'/>";

            if(!$empty) $baseHTML = "<fieldset><legend> Aggiungi un filtro </legend> $baseHTML </fieldset>";

            return $baseHTML;

        }

        private function makeSelect(int $id, string $nome) : string {

            $baseSelection = "<select name='{nome}' id='{id}'>";

            $baseSelection = str_replace("{nome}", $nome, $baseSelection);
            $baseSelection = str_replace("{id}", $id, $baseSelection);

            return $baseSelection;
        }

        private function makeOption(int $id, bool $selected, bool $disabled, string $name): string {

            $baseOption = "<option value='{id}' {selected} {disabled}> {nome}</option>";

            $baseOption = str_replace("{id}", $id, $baseOption);
            $baseOption = str_replace("{selected}", $selected ? "selected='selected'": "",$baseOption);
            $baseOption = str_replace("{disabled}", $disabled ? "readonly": "", $baseOption);
            $baseOption = str_replace("{nome}", $name, $baseOption);

            return $baseOption;
        }

        private function makeFullSelect(array $inArrayVO, bool $disabled, string $varName): string{

            $resolvedHTML = '';

            $firstVO = $inArrayVO[0];
            $resolvedHTML .= $this->makeSelect($firstVO->getId(),$varName);
            foreach ($inArrayVO as $VO)
                $resolvedHTML .= $this->makeOption($VO->getId(), $this->selectedOrdine == $VO->getId(), $disabled, $VO->getNomeSCientifico());
            return $resolvedHTML . "</select>";

        }

        private function resolveNavigation(){

            $resolvedHTML = '';


            if(!empty($this->ordineVOArray))
               $resolvedHTML .= $this->makeFullSelect($this->ordineVOArray, !empty($this->famigliaVOArray) || !empty($this->genereVOArray), "ordineValue" );

            if(!empty($this->famigliaVOArray))
                $resolvedHTML .= $this->makeFullSelect($this->famigliaVOArray, !empty($this->genereVOArray), "famigliaValue");

            if(!empty($this->genereVOArray))
                $resolvedHTML .= $this->makeFullSelect($this->genereVOArray, false, "genereValue");

            if($resolvedHTML != '') $resolvedHTML .= "<button type='submit'> Cerca </button>";

            return $resolvedHTML ;
        }

    }