<?php

    require_once __ROOT__ . '\control\components\summaries\PageFiller.php';
    abstract class Preview extends PageFiller {

        private $reference;
        private $idVal;

        /** Every preview has to build itself?*
         * @param int $id Id dell'elemento da linkare.
         * @param string $HTML
         * @param string|null $reference
         */
        public function __construct(int $id, string $HTML , string $reference = null){

            parent::__construct($HTML);
            $this->idVal = $id; $this->reference = $reference;

        }

    }