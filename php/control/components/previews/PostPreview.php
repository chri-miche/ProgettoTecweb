<?php

    require_once __ROOT__ . '\control\components\previews\Preview.php';
    require_once __ROOT__ . '\model\TagElement.php';
    require_once __ROOT__ . '\model\PostElement.php';
    require_once __ROOT__ . '\model\UserElement.php';



    // TODO: remove?
    class PostPreview extends Preview {

        private $post;
        private $creator;


        public function __construct(PostElement $post, string $reference = null, string $HTML = null) {
            parent::__construct(isset($HTML)? $HTML :
                file_get_contents(__ROOT__.'\view\modules\user\PostCard.xhtml'), $reference);

            $this->post = clone $post;

            if($this->post->exists())
                $this->creator = new UserElement($this->post->UserID);

        }

        // TODO: Remake.
        public function build() {

            $baseLayout = $this->baseLayout();

            foreach ($this->resolveData() as $placeholder => $value)
                $baseLayout = str_replace($placeholder, $value, $baseLayout);

            return  $baseLayout;

        }

        public function resolveData() {

            $resolveData = [];

            foreach($this->post->getData() as $key =>$value)
                if($key != 'immagini') $resolveData['{' . $key . '}'] = $value;


            return $resolveData;

        }

    }