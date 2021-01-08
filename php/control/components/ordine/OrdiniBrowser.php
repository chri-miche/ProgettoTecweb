<?php


class OrdiniBrowser extends Component {

    private $basePage;

    public function __construct($pageNum) {
        // construct parent
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\ordine\ordine.xhtml'));

        $this->basePage = new BasePage($this->baseLayout());

        $this->basePage->addComponent(new Title('Ordini', 'Classificazione Uccelli', 'In biologia, ai fini della tassonomia, l ordine è uno
            dei livelli di classificazione scientifica degli organismi viventi, tanto della zoologia quanto della botanica'));

        $this->basePage->addComponent(new Browser(TagElement::ordineTags(), new TagPreview(new TagElement(0)),'\php\view\pages\ordine.php?page=',
            '\php\view\pages\famiglia.php?id=', $pageNum,10 ));

    }

    public function build() {
        return $this->basePage->build();
    }
}