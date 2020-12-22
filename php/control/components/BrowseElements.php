<?php


class BrowseElements implements Component {

    private $previews;

    private $elementsPerPage;
    private $currentPage;


    // If I pass the preview type?
    public function __construct($ids, Preview $type, int $elementsPerPage = 10, int $page = 0){

        $this->elementsPerPage = $elementsPerPage;
        $this->currentPage = $page;

        $this->previews = array(); // List of each preview element. How to build it?

        foreach ($ids as $id) {
            // Ogni id crea una nuova preview di tipo Preview specifico.
            // Funziona? Si ma sembra un hack.
            $this->previews [] = new $type($id);
        }
    }


    public function build() {

        $HTML = "";

        foreach ($this->previews as $preview)
            $HTML .= $preview->build();
        return $HTML;
        
    }

}