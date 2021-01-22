<?php


class ImagesSlideshow extends BasePage
{
    public function __construct($idPost)
    {
        parent::__construct("<component />");

        $result = DatabaseAccess::executeQuery("select percorsoImmagine from immaginipost i where postID = '$idPost';");
        $links = array_map(function ($value) {
            return $value['percorsoImmagine'];
        }, $result);
        $size = sizeof($links);
        $index = 0;
        if ($size === 0) {
            $this->addComponent(new class(file_get_contents(__ROOT__ . '/view/modules/post/NoImage.xhtml')) extends Component {});
        }
        foreach ($links as $link) {

            $this->addComponent(new class(file_get_contents(__ROOT__ . '/view/modules/post/Image.xhtml'), $link, $index, $size) extends Component {

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
            $index++;
        }
    }



}