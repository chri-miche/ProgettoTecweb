<?php

    require_once __ROOT__.'\control\components\summaries\PageFiller.php';
    /** Creates a banner link with image. (image is optional) */
    class LinkRow implements PageFiller {

        private $HTML;
        private $reference;

        private $title;
        private $image;


        public function __construct(string $referece, string $title, string $image = null, string $HTML = null) {

            $this->HTML = isset($HTML) ? $HTML : file_get_contents(__ROOT__.'\view\modules\LinkRow.xhtml');
            $this->reference = $referece;

            $this->title = $title;
            $this->image = isset($image) ? $image : '';


        }

        function build() {

            foreach ($this->resolveData() as $key => $value)
                $this->HTML = str_replace($key, $value, $this->HTML);

            return $this->HTML;
        }

        public function resolveData() {

            $resolved ['{reference}'] = $this->reference;
            $resolved ['{title}'] = $this->title;
            $resolved ['{image}'] = $this->image;

            return $resolved;

        }
    }