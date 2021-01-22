<?php


class TagDAO extends DAO
{

    /**
     * @inheritDoc */
    public function get($id) {

        $result = $this->performCall(array($id), 'get_tag');
        return isset($result['failure']) ? new UserVO() : new UserVO(...$result);

    }

    /**
     * @inheritDoc
     */
    public function getAll() {
        // TODO: Implement getAll() method.
    }

    public function checkId(VO &$element): bool {
        // TODO: Implement checkId() method.
    }

    /**
     * @inheritDoc
     */
    public function save(VO &$element): bool {
        // TODO: Implement save() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(VO &$element): bool {
        // TODO: Implement delete() method.
    }
}