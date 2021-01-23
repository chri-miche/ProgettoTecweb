<?php

require_once __ROOT__.'\model\DAO\PostDAO.php';
require_once __ROOT__.'\model\VO\CommentoVO.php';


class CommentoDAO extends DAO {

    /**
     * @inheritDoc
     */
    public function get($id) {

        $result = $this->performCall(array($id), 'get_commento');

        /** Se fallisce la ricerca ritorniamo un elemento vuoto.*/
        if(isset($result['failure'])) return new CommentoVO();

        $result['postVO'] = (new PostDAO())->get($result['post']); unset($result['post']);

        return new CommentoVO(...$result);
    }

    /**
     * @inheritDoc
     */
    public function getAll() {


        $VOArray = array();
        $result = $this->performMultiCAll(array(), 'get_all_commento');

        if(!isset($result['failure']))
            foreach ($result as $element){

                $postVO = (new PostDAO())->get($element['post']);
                unset($element['post']);

                $element['postVO'] = $postVO;
                $VOArray [] = new CommentoVO(...$element);
            }

        return $VOArray;
    }

    public function getAllOfPost(int $postId){

        $VOArray = array();
        $parentDAO = new PostDAO();

        /**@var PostVO $parentVO*/
        $parentVO =$parentDAO->get($postId);
        if(!$parentVO->getId()) return $VOArray;

        /** PRE: Abbiamo un post ben formato esistente. */
        $result = $this->performMultiCAll(array($postId), 'get_all_commento_from_post');

        if(!isset($result['failure'])) /** Evito più ritorni del dovuto. */
            foreach ($result as $element)
                $VOArray [] = new CommentoVO(...$element, ...$parentVO);

        return $VOArray;

    }

    public function search(string $element): array {

        $VOArray = array();

        $result = $this->performMultiCAll(array($element), 'search_all_commento');
        if (isset($result['failure'])) return $VOArray;

        foreach ($result as $comment) {

            $postVO = (new PostDAO())->get($comment['post']);
            unset($comment['post']);

            $comment['postVO'] = $postVO;
            $VOArray [] = new CommentoVO(...$comment);

        }

        return $VOArray;
    }

    public function checkId(VO &$element) : bool {
        return $this->idValid($element, 'commento_id');
    }

    /**
     * @inheritDoc
     * @var CommentoVO $element */
    public function save(VO &$element): bool {

        if(is_null($element->getPostVO()->getId())) return false;

        $parentDAO = new PostDAO();
        $parentVO = $element->getPostVO();

        /** Se effettivamente esiste a cui si fa rifermento.*/
        if($parentDAO->checkId($parentVO)) {

            /** Se esiste l id del oggetto corrente, quello va semplicemente aggiornata.*/
            if ($this->checkId($element)) {

                $result = $this->performNoOutputModifyCall($element->smartDump(), 'update_commento');
                return isset($result['failure']);

            } else {

                $result = $this->performCall($element->smartDump(true), 'create_commento');
                if(!isset($result['failure'])) $element = new $element(...$result, ...$element->varDumps(true));
                /* Ritorna vero se è stato costruito l oggetto falso altrimenti.*/
                return !$element->getId() === null;
            }
        }
        /** Elemento padre non valido, viene perciò eliminato.*/
        $element->setPostVO(new PostVO());
        return false;
    }

    /**
     * @inheritDoc */
    public function delete(VO &$element) : bool {
        return $this->defaultDelete($element, 'delete_content');
    }

}