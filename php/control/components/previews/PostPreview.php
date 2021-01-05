<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    require_once __ROOT__ . '\model\TagElement.php';
    require_once __ROOT__ . '\model\PostElement.php';
    require_once __ROOT__ . '\model\UserElement.php';


    // TODO: Vogliamo mettere un menu a tendina ogni volta che si seleziona
    //  un tag per fare in modo di permettere di andare sia alla pagina di ricerca che a quella dell'uccello (se esite).


    // TODO: Rifare. “Mai rimandare a domani ciò che puoi fare benissimo dopodomani.” Mark Twain
    class PostPreview extends Preview {

        private $post;
        private $creator;
        private $tags;

        //TODO Fix
        public function __construct(PostElement $post, string $reference = null, string $HTML = null) {
            parent::__construct(isset($HTML)? $HTML :
                file_get_contents(__ROOT__.'\view\modules\PostPreview.xhtml'), $reference);

            $this->post = clone $post;
            echo $this->post->ID;
            echo $this->post->exists();

            if($this->post->exists()) {
                $this->creator = new UserElement($this->post->UserID);
                /** Finds all tags correlated to a post.*/
                $this->tags = TagElement::getCitedByPost($this->post->ID);
            }
        }

        // TODO: Remake.
        public function build() {

            $baseLayout = $this->baseLayout();

            $baseLayout = str_replace("{TITOLO}", $this->post->title,  $baseLayout);
            $baseLayout = str_replace('{NOME_UTENTE}',  $this->creator->nome,  $baseLayout);

            $baseLayout = str_replace("{POSTLINKID}", $this->post->ID , $baseLayout);

            if(isset($this->tags)) {
                $tagText = "";

                foreach ($this->tags as $tag)
                    $tagText .= '<div class = "w3-tag w3-yellow w3-margin-right"><a href="search.php?tgid=' . $tag->ID . '">' . $tag->nome . '</a></div>';

                $baseLayout = str_replace('{TAGS}', $tagText, $baseLayout);

            }

            return  $baseLayout;

        }

        public function resolveData() {


        }

    }