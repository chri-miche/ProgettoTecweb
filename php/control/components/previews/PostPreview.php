<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    require_once __ROOT__ . '\model\TagElement.php';
    // TODO: Vogliamo mettere un menu a tendina ogni volta che si seleziona
    //  un tag per fare in modo di permettere di andare sia alla pagina di ricerca che a quella dell'uccello (se esite).

    // TODO: Rifare. “Mai rimandare a domani ciò che puoi fare benissimo dopodomani.” Mark Twain
    class PostPreview extends Preview {

        private $post;
        private $creator;
        private $tags;

        public function __construct(int $pid, string $reference = null, string $HTML = null) {

            //  Get the HTML from builder? Might be the way to go.
            parent::construct(isset($HTML)? $HTML : file_get_contents(__ROOT__.'\view\modules\PostPreview.xhtml'));

            if(PostElement::checkID($pid)){
                $this->post = new PostElement($pid);
                if(UserElement::checkID($this->post->userID)) // Controllo inutile
                    $this->creator = new UserElement($this->post->userID);

                /** Finds all tags correlated to a post.*/

                $tags = $this->post->relatedTagIds();

                foreach ($tags as $tag)
                    $this->tags[] = new TagElement($tag);
            }
        }

        // TODO: Avoid changing this->HTML.
        public function build() {

            $fillHTML = $this->baseLayout();

            $fillHTML = str_replace("{TITOLO}", $this->post->title,  $fillHTML);
            $fillHTML = str_replace('{NOME_UTENTE}', '<a href="user.php?id='. $this->creator->ID .'">
                                '. $this->creator->nome. '</a>',  $fillHTML);

            $fillHTML = str_replace("{POSTLINKID}", $this->post->ID , $fillHTML);

            if(isset($this->tags)) {
                $tagText = "";

                foreach ($this->tags as $tag)
                    $tagText .= '<div class = "w3-tag w3-yellow w3-margin-right"><a href="search.php?tgid=' . $tag->ID . '">' . $tag->nome . '</a></div>';

                $fillHTML = str_replace('{TAGS}', $tagText, $fillHTML);

            }

            return  $fillHTML;

        }

        public function resolveData() {
            // TODO: Implement resolveData() method.


        }

    }