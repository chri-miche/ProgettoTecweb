<?php

    require_once __ROOT__ . '\control\components\Component.php';
    // TODO: Expain.
    interface PageFiller extends Component {

        public function resolveData();

    }