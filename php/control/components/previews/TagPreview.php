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

                $this->HTML = str_replace('{REFERENCE}', $this->reference. $this->tag->ID, $this->HTML);
                $this->HTML = str_replace('{IMG}', $this->tag->immagine, $this->HTML);
                $this->HTML = str_replace('{NOME}',$this->tag->nome, $this->HTML);
                $this->HTML = str_replace('{DESCRIZIONE}', $this->tag->label, $this->HTML);

                return $this->HTML;

            } else return false;

        }

    }

