<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    //TODO: Il tagPreview è anche un summarie di Tag? Yes it is.
    // TODO: Get the tags already built
    class TagPreview extends Preview {

        private $tag;
        private $reference;

        public function __construct(TagElement $tag, string $reference = null) {

            parent::__construct(file_get_contents(__ROOT__.'\view\modules\TagPreview.xhtml'), $reference);
            $this->reference = $reference;

            $this->tag = clone $tag;

        }


        public function build() {
            // Se il tag esiste.

            $baseLayout = $this->baseLayout();

            if($this->tag->exists()){

                foreach ($this->resolveData() as $key => $value)
                    $baseLayout = str_replace($key, $value, $baseLayout);

                return $baseLayout;

            } else return false;

        }

        public function resolveData() {
            /* Add reference. */
            $swapData ['{reference}'] = $this->reference . $this->tag->ID;

            foreach ($this->tag->getData() as $key => $value)
                if(!is_array($value))  $swapData['{'.$key .'}'] = $value;


            return $swapData;

        }
    }

