<?php

    // Ci sto lavorando ma se vuoi farlo tu, sei libero di farlo.
    class BreadCrumb implements Component {

        private $previous;
        private $HTML;

        /** Prende in input la pagina corrente e l'array di pagine precedenti.
         * @param array $crumb
         * @param string|null $HTML  */
        public function __construct(array $crumb, string $HTML = null/** Metto default qui. */) {

            $this->HTML = $HTML;
            $this->previous = $crumb;

        }

        function build() {
            //https://codepen.io/iamglynnsmith/pen/BRGjgW
            // TODO: Move this stuff to file and just make a strreplace.
            $ret = "<div class='w3-container w3-blue' style='width:100%; height: fit-content;'>";


            foreach ($this->previous as $prev){ $ret .= " >  ". $prev ; }

            return  $ret . "</div></div>";

        }
    }