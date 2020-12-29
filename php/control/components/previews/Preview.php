<?php

    require_once __ROOT__ . '\control\components\summaries\PageFiller.php';
    interface Preview extends PageFiller {

        /** Every preview has to build itself?* @param int $id */
        public function __construct(int $id, string $reference = null);

    }