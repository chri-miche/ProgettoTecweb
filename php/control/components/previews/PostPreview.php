<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';

    require_once __ROOT__ . '\model\Citazione.php';
    require_once __ROOT__ . '\model\TagElement.php';
    // TODO: Vogliamo mettere un menu a tendina ogni volta che si seleziona
    //  un tag per fare in modo di permettere di andare sia alla pagina di ricerca che a quella dell'uccello (se esite).

    /** Makes the preview (title half image and author of each post + votes.
        On clikc you got to the selected page. 10 are displayed per page.*/
    class PostPreview implements Preview {

        private $HTML;


        private $post;
        private $creator;
        private $tags;

        public function __construct(int $pid, string $reference = null) {
            //  Get the HTML from builder? Might be the way to go.
            $this->HTML = file_get_contents(__ROOT__.'\view\modules\PostPreview.xhtml');

            if(PostElement::checkID($pid)){
                $this->post = new PostElement($pid);
                if(UserElement::checkID($this->post->userID))
                    $this->creator = new UserElement($this->post->userID);

                /** Finds all tags correlated to a post.*/

                $tags = $this->post->relatedTagIds();

                foreach ($tags as $tag)
                    $this->tags[] = new TagElement($tag);
            }
        }

        public function build() {
            
            $this->HTML = str_replace("{TITOLO}", $this->post->title,  $this->HTML);
            $this->HTML = str_replace('{NOME_UTENTE}', '<a href="user.php?id='. $this->creator->ID .'">
                                '. $this->creator->nome. '</a>',  $this->HTML);

            $this->HTML = str_replace("{POSTLINKID}", $this->post->ID , $this->HTML);

            if(isset($this->tags)) {
                $tagText = "";

                foreach ($this->tags as $tag)
                    $tagText .= '<div class = "w3-tag w3-yellow w3-margin-right"><a href="search.php?tgid=' . $tag->ID . '">' . $tag->nome . '</a></div>';
                $this->HTML = str_replace('{TAGS}', $tagText, $this->HTML);
            }

            return  $this->HTML;

        }

    }