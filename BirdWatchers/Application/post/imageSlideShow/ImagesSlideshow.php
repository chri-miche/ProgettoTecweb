<?php

require_once __DIR__ . "/../../databaseObjects/DatabaseAccess.php";
require_once __DIR__ . "/../../BasePage.php";

class ImagesSlideshow extends BasePage {
    public function __construct($idPost) {
        parent::__construct("<component />");

        $result = DatabaseAccess::executeQuery("SELECT percorso_immagine FROM immaginipost i WHERE post_id = '$idPost';");
        $links = array_map(function ($value) {
            return str_replace('\\', '/', $value['percorso_immagine']);
        }, $result);

        $size = sizeof($links);
        $index = $size - 1;
        if ($size === 0) {
            $this->addComponent(new class(file_get_contents(__DIR__ . "/NoImage.xhtml")) extends Component {
            });
        } else if ($size === 1) {
            $this->addComponent(new class('<div id="image-0" class="image-slideshow"><div class="analog-container"><img src="{link}" alt="" /></div></div>', $links[0]) extends Component {
                private $data;

                public function __construct(string $HTML, $link) {
                    parent::__construct($HTML);
                    $this->data = array(
                        "{link}" => $link
                    );
                }

                public function resolveData() {
                    return $this->data;
                }
            });
        } else {
            foreach ($links as $link) {

                $this->addComponent(new class(file_get_contents(__DIR__ . "/Image.xhtml"), $link, $index, $size) extends Component {

                    private $data;

                    public function __construct(string $HTML, $link, $index, $size) {
                        parent::__construct($HTML);
                        $this->data = array(
                            "{link}" => $link,
                            "{previous}" => ($index - 1 + $size) % $size,
                            "{next}" => ($index + 1) % $size,
                            "{index}" => $index
                        );
                    }

                    public function resolveData() {
                        return $this->data;
                    }
                });
                $index--;
            }
        }
    }


}