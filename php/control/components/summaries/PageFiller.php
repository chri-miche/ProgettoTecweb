<?php

    require_once __ROOT__ . '\control\components\Component.php';
    // TODO: Expain.
    abstract class PageFiller extends Component {

        abstract public function resolveData();

    }