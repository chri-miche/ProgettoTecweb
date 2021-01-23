<?php

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    /** Creates a banner link with image. (image is optional) */
    class LinkRow extends PageFiller {

        private $reference;

        /** @var VO $genericVO: Implementazione di vo*/
        private $genericVO;

        public function __construct(string $reference, VO $linkTypeVO, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\LinkRow.xhtml'));

            $this->reference = $reference;
            $this->genericVO = $linkTypeVO;

        }


        public function resolveData() {

            $resolvedData['{reference}'] = $this->reference;

            foreach ($this->genericVO->arrayDump() as $key => $value)
                $resolvedData['{'. $key .'}'] = $value;

            return $resolvedData;
        }
    }