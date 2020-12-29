<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    //TODO: Il tagPreview è anche un summarie di Tag? Yes it is.
    class TagPreview implements Preview {

        private $tag;

        private $HTML;
        private $reference;

        public function __construct(int $id, string $reference = null) {

            // TODO: Get from constructor.
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\TagPreview.xhtml');

            $this->reference = $reference;

            $this->tag = new TagElement($id);

        }


        public function build() {
            // Se il tag esiste.
            if($this->tag->exists()){

                foreach ($this->resolveData() as $key => $value)
                    $this->HTML = str_replace($key, $value, $this->HTML);

                return $this->HTML;

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

