<?php

abstract class Component {

    /** @var string : Layout HTML della pagina pronta alla sostituzione dei dati. */
    private $HTML; // Can be empty.
    /** @var string: Layout HTML della pagina costruita con le sostituzioni. */
    private $builtHTML; // Anche questa può essere vuota.
    /** @var boolean : Se la pagina corrente è in stato di costruito e quindi valida alla stampa. */
    private $built; //  = false on default.

    /** @param string $HTML : Layout di base della pagina. */
    public function __construct(string $HTML) {
        /* Setta il layout della pagina e imposta la mancata costruzione della pagina.*/
        $this->HTML = $HTML;
        $this->built = false;
    }

    public function returnComponent(): string {

        if (!$this->built) {

            $this->builtHTML = $this->build();
            $this->built = true;
        }

        return $this->builtHTML;

    }


    public function notBuilt() {
        $this->built = false;
    }

    protected function baseLayout() {
        return $this->HTML;
    }

    /** Definizione di default di build: Risolve tutti i dati che deve risolvere
     * e stampa il layout di base della pagina caricato.*/
    public function build() {

        $buildLayout = $this->HTML; // Layout della pagina (caricato in costruzione). Può essere vuoto.

        foreach ($this->resolveData() as $key => $value) /** Modifiche dei dati della pagina corrente.*/ {
            if (!is_array($value)) {
                $buildLayout = str_replace($key, $value, $buildLayout);
            }
        }

        /* Pagina costruita ma non salvata nella classe.*/
        return $buildLayout;
    }

    /** Ogni componente deve poter risolvere i dati? Possiamo fare di deafult vuoto?*/
    public function resolveData() {
        return array();
    }

    public function __toString() {
        return $this->returnComponent();
    }
}

?>