<?php

class ImagesSlideshow extends BasePage {
    public function __construct($idPost) {
        parent::__construct("<component />");

        $result = DatabaseAccess::executeQuery("select percorsoImmagine from immaginipost i where postID = '$idPost';");
        $links = array_map(function ($value) {
            return $value['percorsoImmagine'];
        }, $result);
        $size = sizeof($links);
        $index = $size - 1;
        if ($size === 0) {
            $this->addComponent(new class(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "NoImage.xhtml")) extends Component {});
        } else if ($size === 1) {
            $this->addComponent(new class('<div id="image-0" class="image-slideshow"><img src="{link}" alt="" /></div>', $links[0]) extends Component {
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

                $this->addComponent(new class(file_get_contents(__ROOT__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "post" . DIRECTORY_SEPARATOR . "Image.xhtml"), $link, $index, $size) extends Component {

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