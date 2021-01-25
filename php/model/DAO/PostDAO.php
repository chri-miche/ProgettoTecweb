<?php

require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "PostVO.php";
class PostDAO extends DAO {

    /**
     * @inheritDoc */
    private function buildPostVO(array $data) : PostVO {

        $userVO = (new UserDAO())->get($data['userId']);
        unset($data['userId']);

        $builtVO = new PostVO(...$data, ...[$userVO]);

        $builtVO->setImmagini($this->getImmagini($builtVO->getId()));

        return $builtVO;

    }

    public function get($id){

        $result = $this->performCall(array($id), 'get_post');

        if(isset($result['failure']))  return new PostVO();

        return $this->buildPostVO($result);
    }

    /**
     * @inheritDoc
     */
    public function getAll(){

        $VOArray = array();
        $result = $this->performMultiCAll(array(), 'get_all_post');

        if(!isset($result['failure']))
            foreach ($result as $element)
                $VOArray [] = $this->buildPostVO($element);

        return $VOArray;

    }

    public function getMany(int $limit = 0, int $offset = 0){

        if(!$limit > 0)  return $this->getAll();

        $VOArray = array();
        $result = $this->performMultiCAll(array($limit, $offset), 'get_many_post');

        if(!isset($result['failure']))
            foreach ($result as $element)
                $VOArray [] = $this->buildPostVO($element);

        return $VOArray;

    }

    /**         $query = "select * from post p left join contenuto c on p.contentID = c.ID left join immaginipost i
    on i.postID = p.contentID join utente u on u.ID = c.UserID where p.title like '%". $this->keyword ."%' or c.content like '%". $this->keyword ."%' group by p.contentID ;"
     **/
    public function search(string $element): array {

        $VOArray = array();

        $result = $this->performMultiCAll(array($element), 'search_all_post');
        if (!isset($result['failure']))
            foreach ($result as $element)
                $VOArray [] = $this->buildPostVO($element);

        return $VOArray;

    }

    public function getOfUtente(int $userId, int $limit = 0, int $offset = 0) : array {

        $VOArray = array();

        $result = $limit > 0 /** Se è impostato un limite allora lo consideriamo con il suo offset.*/
            ? $this->performMultiCAll(array($userId, $limit, $offset),'get_of_utente_post_limited' )
            : $this->performMultiCAll(array($userId), 'get_of_utente_post_all');

        if(!isset($result['failure']))
            foreach ($result as $element)
                $VOArray [] = $this->buildPostVO($element);

            return $VOArray;

    }

    private function getImmagini(int $postId) : array{

        $immagini = array();
        $result = $this->performMultiCAll(array($postId), 'get_images_of_post');

        if(!isset($result['failure']))
            foreach ($result as $element)
                $immagini [] = $element['immagine'];

        return $immagini;
    }

    public function saveImmagine(string $immagine, int $postId) : bool {

        $result = $this->performNoOutputModifyCall(array($postId, $immagine), 'add_missing_immagine' );
        return !isset($result['failure']);

    }

    private function deleteImmagine(string $immagine, int $postId){

        $result = $this->performNoOutputModifyCall(array($postId, $immagine), 'delete_immagine' );
        return !isset($result['failure']);

    }

    public function checkId(VO &$element) : bool {
        return $this->idValid($element, 'post_id');
    }

    public function like(UserVO $userVO, PostVO $postVO) : bool {

        $result = $this->performNoOutputModifyCall(array($userVO->getId(),$postVO->getId()),'like_post');
        return !isset($result['failure']);
    }

    public function dislike(UserVO $userVO, PostVO $postVO) : bool {

        $result = $this->performNoOutputModifyCall(array($userVO->getId(),$postVO->getId()),'dislike_post');
        return !isset($result['failure']);
    }

    public function getLikes(PostVO $postVO) : int {

        $result = $this->performCall(array($postVO->getId()), 'getLikes');
        if(isset($result['failure'])) return 0;

        return $result['likes'];
    }


    /**
     * @inheritDoc
     * @param PostVO $element */
    public function save(VO &$element): bool {

        if($this->checkId($element)){

            $result = $this->performNoOutputModifyCall($element->smartDump(),'update_post');

            /** Sistemazione delle immagini. */
            $delete = array_diff($this->getImmagini($element->getId()), $element->getImmagini());
            $add = array_diff($element->getImmagini(), $this->getImmagini($element->getId()));

            foreach ($delete as $immagine)
                $this->deleteImmagine($immagine, $element->getId());
            foreach ($add as $immagine)
                $this->saveImmagine($immagine, $element->getId());

            return !isset($result['failure']);

        } else {

            $result = $this->performCall($element->smartDump(true), 'create_post');

            if(!isset($result['failure'])) {

                $element = new $element(...$result, ...$element->varDumps(true));

                foreach ($element->getImmagini() as $immagine)
                    $this->saveImmagine($immagine, $element->getId());
            }

            return !is_null($element->getId());

        }

    }

    /**
     * @inheritDoc
     */
    public function delete(VO &$element) : bool {
        return $this->defaultDelete($element, 'delete_content');
    }
}