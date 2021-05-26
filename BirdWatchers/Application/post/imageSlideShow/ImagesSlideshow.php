<?php

class ImagesSlideshow extends BasePage {
    public function __construct($idPost) {
        parent::__construct("<component />");

        $result = DatabaseAccess::executeQuery("select percorso_immagine from immaginipost i where post_id = '$idPost';");
        $links = array_map(function ($value) {
            // esempio: 'img src="Utente10/8obsdx.png"' con preg_replace diventa Utente10obd...
            // perche' /8 = l'ottavo match
            // non ci era mai capitato di avere un'immagine che iniziasse con un numero, quindi questo problema
            // ci era scappato
            // preg_quote non risolve il problema perche' leva via anche tutti i tag html
            // bisogna fare l'escape proprio per questo specifico caso
            return str_replace('\\', '/', $value['percorso_immagine']);
        }, $result);
        $size = sizeof($links);
        $index = $size - 1;
        if ($size === 0) {
            $this->addComponent(new class(file_get_contents(__DIR__ . "/NoImage.xhtml")) extends Component {});
        } else if ($size === 1) {
            $this->addComponent(new class('<div id="image-0" class="image-slideshow"><div class="analog-container"><img src="{link}" alt="" /></div></div>', $links[0]) extends Component {
                private $data;

                public function __construct(string $HTML, $link)
                {
                    parent::__construct($HTML);
                    $this->data = array(
                        "{link}" => $link
                    );
                }

                public function resolveData()
                {
                    return $this->data;
                }
            });
        } else {
            foreach ($links as $link) {

                $this->addComponent(new class(file_get_contents(__DIR__ . "/Image.xhtml"), $link, $index, $size) extends Component {

                    private $data;

                    public function __construct(string $HTML, $link, $index, $size)
                    {
                        parent::__construct($HTML);
                        $this->data = array(
                            "{link}" => $link,
                            "{previous}" => ($index - 1 + $size) % $size,
                            "{next}" => ($index + 1) % $size,
                            "{index}" => $index
                        );
                    }

                    public function resolveData()
                    {
                        return $this->data;
                    }
                });
                $index--;
            }
        }
    }



}