<?php

    require_once __ROOT__ . '\control\components\Component.php';
    // TODO: Expain.
    abstract class PageFiller extends Component {

        abstract public function resolveData();

        /*
        In questa maniera, basterà semplicemente ridefinire il metodo resolveData nelle classi figlie
        per ottenere la build già fatta
        In caso servisse una buld diversa, si può comunque overridare la funzione build, chiamando poi
        parent::build per l'applicazione dell'interpolazione

        */
        public function build()
        {


            $HTML = $this->baseLayout();
            foreach ($this->resolveData() as $key => $value) {
                $HTML = str_replace($key, $value, $HTML);
            }
            return $HTML;
        }

    }