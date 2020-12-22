<?php

/** OPPURE MEGLIO : TagPreview e mostriamo i tag uccelli etc con nome, descrizione
    e un'immagine. La sfruttiamo per creare catalogo uccelli.*/
class TagPreview implements Preview {

    private $tag;
    private $HTML;

    public function __construct(int $id) {

        $this->HTML = file_get_contents(__ROOT__.'\view\modules\TagPreview.xhtml');
        $this->tag = new TagElement($id);

    }


    public function build() {
        // Se il tag esiste.
        if($this->tag->exists()){

            $this->HTML = str_replace('{IMG}', $this->tag->immagine, $this->HTML);
            $this->HTML = str_replace('{NOME}',$this->tag->nome, $this->HTML);
            $this->HTML = str_replace('{DESCRIZIONE}', $this->tag->label, $this->HTML);

            return $this->HTML;

        } else return false;

    }

}

