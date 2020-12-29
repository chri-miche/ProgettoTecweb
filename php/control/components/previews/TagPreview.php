<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    //TODO: Il tagPreview è anche un summarie di Tag? Yes it is.
    class TagPreview extends Preview {

        private $tag;

        private $HTML;
        private $reference;

        public function __construct(int $id, string $reference = null) {

            parent::__construct($id, file_get_contents(__ROOT__.'\view\modules\TagPreview.xhtml'), $reference);
            $this->reference = $reference;

            $this->tag = new TagElement($id);

        }

        // TODO: Avoid changing this->HTML. Or make sure to be built.
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

