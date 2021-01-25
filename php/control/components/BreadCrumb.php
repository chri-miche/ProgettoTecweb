<?php


    class BreadCrumb extends Component {

        private $previous;

        /** Prende in input la pagina corrente e l'array di pagine precedenti.
         * crumb = array associativo Descrizione => Pagina.php
         * @param array $crumb
         * @param string|null $HTML  */
        public function __construct(array $crumb, string $HTML = null) {

            parent::__construct( $HTML ?? file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "BreadCrumb.xhtml"));
            $this->previous = $crumb;

        }

        function build() {

            $ret = "";

            foreach ($this->previous as $key => $value) {

                $span = "<span> " . $key . "</span>";

                // non metto il link sull'home perché c'è già nella sitebar
                if ($value !== '') { $span = " <a class='breadcrumb-item' href='" . $value . "'>". $span . "</a>"; }

                $ret .= $span;

            }

            return str_replace("<breadCrumb />", $ret, $this->baseLayout());
        }
    }