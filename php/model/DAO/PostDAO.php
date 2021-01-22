<?php

require_once __ROOT__.'\model\VO\TagVO.php';
require_once __ROOT__.'\model\VO\PostVO.php';
class PostDAO extends DAO {

    /**
     * @inheritDoc */
    public function get($id){

        $result = $this->performCall(array($id), 'get_post');

        if(isset($result['failure']))  return new PostVO();

        $return = new PostVO(...$result);

        $return->setImmagini($this->getImmagini($id));
        $return->setArrayTagVO($this->getTags($id));

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function getAll(){

        $VOArray = array();

        $result = $this->performMultiCAll(array(), 'get_all_post');

        if(!isset($result['failure']))
            foreach ($result as $element){

                $VOApp = new PostVO(...$element);

                $VOApp->setImmagini($this->getImmagini($VOApp->getId()));
                $VOApp->setArrayTagVO($this->getTags($VOApp->getId()));

                $VOArray [] = $VOApp;
            }

        return $VOArray;

    }

    public function getOfUtente(int $userId, int $limit = 0, int $offset = 0) : array {

        $VOArray = array();

        $result = $limit > 0 /** Se è impostato un limite allora lo consideriamo con il suo offset.*/
            ? $this->performMultiCAll(array($userId, $limit, $offset),'get_of_utente_post_limited' )
            : $this->performMultiCAll(array($userId), 'get_of_utente_post_all');

        if(!isset($result['failure']))
            foreach ($result as $element) {

                $VOApp = new PostVO(...$element);
                $VOApp->setImmagini($this->getImmagini($VOApp->getId()));

                $VOArray [] = $VOApp;
            }

        return $VOArray;

    }

    private function getImmagini(int $postId) : array{

        $immagini = array();

        $result = $this->performMultiCAll(array($postId), 'get_images_of_post');
        if(!isset($result['failure'])) foreach ($result as $element) $immagini [] = $element['immagine'];

        return $immagini;
    }

    private function saveImmagini(array $immagini, int $postId) : bool {

        $result = true;

        if(sizeof($immagini) > 0)
            foreach ($immagini as $immagine) {
                $passArray = array();
                $passArray [] = $postId; $passArray [] = $immagine;

                $innerResult = $this->performNoOutputModifyCall($passArray, 'add_missing_immagine');
                $result &= !isset($innerResult['failure']);

            }

        return $result;

    }

    private function deleteImmagini(array $immagini, int $postId){

        $result = true;

        $allImmagini = $this->getImmagini($postId);

        $delete = array_diff($allImmagini, $immagini);
        foreach ($delete as $immagine){

            $passArray = array();
            $passArray [] = $postId; $passArray [] = $immagine;

            $innerResult = $this->performNoOutputModifyCall($passArray, 'delete_immagine');
            $result &= !isset($innerResult['failure']);

        }

        return $result;
    }

    private function getTags(int $postId) : array{

        $tagsVO = array();

        $result = $this->performMultiCAll(array($postId), 'get_post_tag_all');

        if(!isset($result['failure']))
            foreach ($result as $element)
                $tagsVO [] = new TagVO(...$element);

        return $tagsVO;

    }

    private function saveTag(TagVO $tagVO, int $postId){

        $result = $this->performCall(array($postId, $tagVO->getId()), 'save_post_tag' );
        return !isset($result['failure']);

    }

    private function deleteTag(TagVO $tagVO, int $postId){

        $result = $this->performNoOutputModifyCall(array($postId, $tagVO->getId()), 'delete_post_tag' );
        return !isset($result['failure']);

    }


    public function checkId(VO &$element) : bool {
        return $this->idValid($element, 'post_id');
    }

    /**
     * @inheritDoc
     * @param PostVO $element */
    public function save(VO &$element): bool {

        if($this->checkId($element)){

            $result = $this->performNoOutputModifyCall($element->smartDump(),'update_post');
            /** Sistemazione dei tag.*/
            $delete = array_diff($this->getTags($element->getId()), $element->getArrayTagVO());
            $add = array_diff($element->getArrayTagVO(), $this->getTags($element->getId()));

            /* Eliminazione dei tag che non servono più.*/
            foreach ($delete as $tag)
                $this->deleteTag($tag, $element->getId());
            /* Creazione dei nuovi tag.*/
            foreach ($add as $tag)
                $this->saveTag($tag, $element->getId());

            $success = $this->deleteImmagini($element->getImmagini(), $element->getId());

            if(sizeof($element->getImmagini()) > 0) /* Potremmo salvare di nuovo le stesse?*/
                $success &= $this->saveImmagini($element->getImmagini(), $element->getId());

            return !isset($result['failure']) && $success;

        } else {

            $result = $this->performCall($element->smartDump(true), 'create_post');

            $success = true;
            if(!isset($result['failure'])) {

                $element = new $element(...$result, ...$element->varDumps(true));
                foreach ($element->getArrayTagVO() as $tagVO)
                    $this->saveTag($tagVO, $element->getId());

                $success = $this->saveImmagini($element->getImmagini(), $element->getId());
            }

            return !$element->id === null && $success;

        }

    }

    /**
     * @inheritDoc
     */
    public function delete(VO &$element) : bool {
        return $this->defaultDelete($element, 'delete_post');
    }
}