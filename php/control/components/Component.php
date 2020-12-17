<?php


    /** Oggetto costruibile (elemento di pagina).*/
    abstract class Component {

        private $HTML;
        private $built;

        public function __construct(string $HTMLcontent = null) {
            //TODO: Non funziona se devo cambiare tanti tag con tanti builder.
            // Dovrei passare già la pagina caricata.
            if($HTMLcontent && $this->validForThisBuild($HTMLcontent)) {

                $this->HTML = $HTMLcontent;

            }

            $this->built = false;

        }

        protected abstract function addContent();

        public abstract function validForThisBuild(string $HTML);

        public function setComponent(string $newBase){

            if($newBase && $this->validForThisBuild($newBase)) {

                $this->HTML = $newBase;
                $this->newBuild($this->HTML);

            }
        }

        public abstract function newBuild(string $HTML);//?

        public abstract function deleteBuild();//?

        private function build(){

            $var = $this->addContent();
            $this->HTML = str_replace($var['tag'], $var['html'], $this->HTML);

            return $this->HTML;

        }

        public function getBuild(){

            if($this->HTML) {

                if ($this->built)
                    return $this->HTML;

                else {
                    $this->build(); $this->built = true;
                    return $this->HTML;
                }
            }

            return false;

        }

    }