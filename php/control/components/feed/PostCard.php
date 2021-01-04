<?php
require_once __ROOT__.'\control\components\summaries\PageFiller.php';


class PostCard extends PageFiller {

    private $HTML;

    private $postElement;

    public function __construct($postId) {
        // construct parent
        parent::__construct(file_get_contents(__ROOT__.'\view\modules\feed\PostCard.xhtml'));
        // get parent's layout
        $this->HTML = $this->baseLayout();
        // get post's attributes
        $this->postElement = new PostElement();
        $this->postElement->loadElement($postId);
    }

    public function build() {
        foreach ($this->resolveData() as $placeholder => $value) {
            $this->HTML = str_replace($placeholder, $value, $this->HTML);
        }
        return $this->HTML;
    }

    public function resolveData() {
        $ritorno = [];

        foreach ($this->postElement->getData() as $key => $value) {
            if ($key === "immagini") {
                $ritorno['{linkImage}'] = $value[0] ?? null; // TODO caso in cui non ci sono immagini
            } else {
                if ($key === 'UserID') {
                    $author = new UserElement($value);

                    $ritorno['{authorName}'] = $author->getData()['nome'];
                }
                $ritorno['{' . $key . '}'] = $value;
            }
        }
        return $ritorno;
    }
}