<?php

    // Ci sto lavorando ma se vuoi farlo tu, sei libero di farlo.
    class BreadCrumb implements Component {

        private $previous;
        private $HTML;

        /** Prende in input la pagina corrente e l'array di pagine precedenti.
         * @param array $crumb
         * @param string|null $HTML
         */
        public function __construct(array $crumb, string $HTML = null/** Metto default qui. */) {

            $this->HTML = $HTML;
            $this->previous = $crumb;

        }


        // TODO: Check and make it new. ( non global var dependant)
        function build() {

            // TODO: Move this stuff to file and just make a strreplace.
            $ret = "<div class='w3-container w3-green' style='width:100%; height: fit-content'>";
            $ret .= 'Al momento ti trovi in:   ';

            foreach ($this->previous as $prev){ $ret .= " / ". $prev ; }

            return  $ret . "</div>";

        }


    }