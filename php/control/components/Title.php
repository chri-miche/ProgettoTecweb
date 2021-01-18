<?php

    require_once __ROOT__ . '\control\components\Component.php';

    class Title extends PageFiller {

        private $data;

        public function __construct(string $title, string $secondTitle = null, string $description = null, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\Title.xhtml'));

            $this->data = array(
                "{title}" => $title,
                "{subtitle}" => $secondTitle,
                "{description}" => $description,
            );

        }

        public function resolveData()
        {
            return $this->data;
        }
    }