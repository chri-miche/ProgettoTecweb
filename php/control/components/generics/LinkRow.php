<?php

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    /** Creates a banner link with image. (image is optional) */
    class LinkRow extends PageFiller {

        private $HTML;
        private $reference;

        private $title;
        private $image;


        public function __construct(string $referece, string $title, string $image = null, string $HTML = null) {

            parent::__construct(isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\LinkRow.xhtml'));

            $this->reference = $referece;

            $this->title = $title;
            $this->image = isset($image) ? $image : '';


        }

        function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $key => $value)
                $baseLayout = str_replace($key, $value, $baseLayout);

            return $baseLayout;
        }

        public function resolveData() {

            $resolved ['{reference}'] = $this->reference;
            $resolved ['{title}'] = $this->title;
            $resolved ['{image}'] = $this->image;

            return $resolved;

        }
    }