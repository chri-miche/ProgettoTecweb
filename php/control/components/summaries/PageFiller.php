<?php


    require_once __ROOT__ . '\control\components\previews\Preview.php';
    // TODO: Expain.
    interface PageFiller extends Component {

        public function resolveData();

    }